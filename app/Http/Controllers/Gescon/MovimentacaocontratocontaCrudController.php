<?php

namespace App\Http\Controllers\Gescon;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use App\Models\Contrato;
use App\Models\Contratoconta;
use App\Models\Movimentacaocontratoconta;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MovimentacaocontratocontaRequest as StoreRequest;
use App\Http\Requests\MovimentacaocontratocontaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// inserido
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


/**
 * Class MovimentacaocontratocontaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MovimentacaocontratocontaCrudController extends CrudController
{
    public function setup()
    {

        // em caso de exclusão de movimentação, tudo o que temos é o id da movimentação
        if( \Route::current()->parameter('contratoconta_id') == null  && \Route::current()->parameter('movimentacao_id') != null ){
            // aqui temos o id da movimentação
            $contratoconta_id = Movimentacaocontratoconta::where('id', \Route::current()->parameter('movimentacao_id'))->first()->contratoconta_id;
        } else {
            $contratoconta_id = \Route::current()->parameter('contratoconta_id');
        }


        $contratoConta = Contratoconta::where('id','=',$contratoconta_id)->first();
        if(!$contratoConta){
            abort('403', config('app.erro_permissao'));
        }
        $contrato_id = $contratoConta->contrato_id;
        $contrato = Contrato::where('id','=',$contrato_id)
            ->where('unidade_id','=',session()->get('user_ug_id'))->first();
        if(!$contrato){
            abort('403', config('app.erro_permissao'));
        }

        //vamos setar o contrato_id como parâmetro e utilizá-lo no botão
        \Route::current()->setParameter('contrato_id', $contrato_id);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Movimentacaocontratoconta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/contratoconta/' . $contratoconta_id . '/movimentacaocontratoconta');
        $this->crud->setEntityNameStrings('movimentacaocontratoconta', 'Movimentações da Conta-Depósito Vinculada');

        $this->crud->addButtonFromView('line', 'deletemovimentacao', 'deletemovimentacao', 'beginning');
        $this->crud->addButtonFromView('line', 'moremovimentacaocontratoconta', 'moremovimentacaocontratoconta', 'end');
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontavinculada', 'end');
        $this->crud->addButtonFromView('top', 'adicionardeposito', 'adicionardeposito', 'end');
        $this->crud->addButtonFromView('top', 'adicionarretirada', 'adicionarretirada', 'end');
        $this->crud->addButtonFromView('top', 'adicionarrepactuacao', 'adicionarrepactuacao', 'end');
        $this->crud->addButtonFromView('bottom', 'voltar', 'voltarcontavinculada', 'end');
        $this->crud->addButtonFromView('bottom', 'adicionardeposito', 'adicionardeposito', 'end');
        $this->crud->addButtonFromView('bottom', 'adicionarretirada', 'adicionarretirada', 'end');
        $this->crud->addButtonFromView('bottom', 'adicionarrepactuacao', 'adicionarrepactuacao', 'end');

        // $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        // $this->crud->denyAccess('show');

        // cláusulas para possibilitar buscas
        $this->crud->addClause('select', 'movimentacaocontratocontas.*', 'codigoitens.descricao');
        $this->crud->addClause('join', 'codigoitens', 'codigoitens.id',  '=',  'movimentacaocontratocontas.tipo_id');
        $this->crud->addClause('where', 'movimentacaocontratocontas.contratoconta_id', '=', $contratoconta_id);
        $this->crud->addClause('orderby', 'movimentacaocontratocontas.id', 'desc');
        // filtros na listagem
        $this->adicionaFiltros();


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        // add asterisk for fields that are required in MovimentacaocontratocontaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
    public function adicionaFiltros()
    {
    //     $this->adicionaFiltroFuncionario();
        $this->adicionaFiltroMovimentacao();
    //     $this->adicionaFiltroEncargos();   // são os encargos
        $this->adicionaFiltroMes();
        $this->adicionaFiltroAno();
    }
    // INÍCIO MÉTODOS FILTROS
    public function adicionaFiltroAno()
    {
        $campo = [
            'name' => 'ano_competencia',
            'type' => 'select2_multiple',
            'label' => 'Ano'
        ];
        $anos = $this->getAnos();
        $this->crud->addFilter(
            $campo,
            $anos,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'movimentacaocontratocontas.ano_competencia', json_decode($value));
            }
        );
    }
    public function adicionaFiltroMes()
    {
        $campo = [
            'name' => 'mes_competencia',
            'type' => 'select2_multiple',
            'label' => 'Mês'
        ];
        $meses = $this->getMeses();
        $this->crud->addFilter(
            $campo,
            $meses,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'movimentacaocontratocontas.mes_competencia', json_decode($value));
            }
        );
    }
    public function adicionaFiltroMovimentacao()
    {
        $campo = [
            'name' => 'movimentacao',
            'type' => 'select2_multiple',
            'label' => 'Tipo Movimentação'
        ];
        $tiposMovimentacao = $this->getTiposMovimentacao();
        $this->crud->addFilter(
            $campo,
            $tiposMovimentacao,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'codigoitens.id', json_decode($value));
            }
        );
    }

    private function getMeses()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        $contratoconta_id = \Route::current()->parameter('contratoconta_id');
        $dados = \DB::table('movimentacaocontratocontas as m')
        // ->select('ci.descricao')->distinct()
        // ->join('movimentacaocontratocontas as m', 'm.tipo_id', '=', 'ci.id')
        ->where('m.contratoconta_id', '=', $contratoconta_id)
        ->pluck('m.mes_competencia', 'm.mes_competencia')
        ->toArray();
        // dd($dados);
        return $dados;
    }
    private function getAnos()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        $contratoconta_id = \Route::current()->parameter('contratoconta_id');
        $dados = \DB::table('movimentacaocontratocontas as m')
        // ->select('ci.descricao')->distinct()
        // ->join('movimentacaocontratocontas as m', 'm.tipo_id', '=', 'ci.id')
        ->where('m.contratoconta_id', '=', $contratoconta_id)
        ->pluck('m.ano_competencia', 'm.ano_competencia')
        ->toArray();
        // dd($dados);
        return $dados;
    }
    private function getTiposMovimentacao()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        $contratoconta_id = \Route::current()->parameter('contratoconta_id');
        $dados = \DB::table('codigoitens as ci')
        ->join('movimentacaocontratocontas as m', 'm.tipo_id', '=', 'ci.id')
        ->where('m.contratoconta_id', '=', $contratoconta_id)
        ->pluck('ci.descricao', 'ci.id')
        ->toArray();
        return $dados;
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getTipoMovimentacao',
                'label' => 'Tipo da movimentação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoMovimentacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
                // },
            ],
            [
                'name'  => 'mes_competencia',
                'label' => 'Mês',
                'type'  => 'text',
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('movimentacaocontratocontas.mes_competencia', 'ilike', "%$searchTerm%");
                },
            ],
            [
                'name'  => 'ano_competencia',
                'label' => 'Ano',
                'type'  => 'text',
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('movimentacaocontratocontas.ano_competencia', 'ilike', "%$searchTerm%");
                },
            ],
            [
                'name'  => 'situacao_movimentacao',
                'label' => 'Situação da movimentação',
                'type'  => 'text',
            ],
            [
                'name' => 'getTotalMovimentacao',
                'label' => 'Total movimentado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTotalMovimentacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'prefix' => "R$ ",
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('situacao', 'ilike', "%$searchTerm%");
                // },
            ],
            [
                'name'  => 'created_at',
                'label' => 'Data / Hora',
                'type'  => 'text',
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('movimentacaocontratocontas.created_at', 'ilike', "%$searchTerm%");
                },
            ],
        ];
        return $colunas;
    }
    // este método é chamado pelo custom.php - via rota.
    public function excluirMovimentacao(int $movimentacao_id){
        $objMovimentacao = Movimentacaocontratoconta::where('id', $movimentacao_id)->first();
        $contratoconta_id = $objMovimentacao->contratoconta_id;
        if($objMovimentacao->excluirMovimentacao($movimentacao_id)){
            $mensagem = 'Movimentação excluída com sucesso!';
            \Alert::success($mensagem)->flash();
        } else {
            $mensagem = 'Erro ao excluir a movimentação!';
            \Alert::error($mensagem)->flash();
        }
        $linkLocation = '/gescon/contrato/contratoconta/'.$contratoconta_id.'/movimentacaocontratoconta';
        return redirect($linkLocation);
    }
    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
