<?php
namespace App\Http\Controllers\Gescon;
use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ExtratocontratocontaRequest as StoreRequest;
use App\Http\Requests\ExtratocontratocontaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use App\Models\Contratoconta;
use App\Models\Contratoterceirizado;
use App\Models\Movimentacaocontratoconta;
// inserido
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
/**
 * Class ExtratocontratocontaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ExtratocontratocontaCrudController extends CrudController
{
    public function setup()
    {
        $contratoconta_id = \Route::current()->parameter('contratoconta_id');
        $objContratoConta = Contratoconta::where('id', '=', $contratoconta_id)->first();
        $idContrato = $objContratoConta->contrato_id;
        $idContratoConta = $objContratoConta->id;
        \Route::current()->setParameter('contrato_id', $idContrato);
        \Route::current()->setParameter('contratoconta_id', $idContratoConta);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Extratocontratoconta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/contratoconta/' . $contratoconta_id . '/extratocontratoconta');
        $this->crud->setEntityNameStrings('extratocontratoconta', 'Extrato da Conta');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('show');
        $this->crud->addButtonFromView('top', 'voltarcontavinculada', 'voltarcontavinculada', 'end');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);
        // add asterisk for fields that are required in ExtratocontratocontaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        // adicionar cl??usulas para trabalharmos com os lan??amentos e seus relacionamentos.
        $this->crud->addClause('select', 'lancamentos.*', 'lancamentos.created_at as data_lancamento', 'contratoterceirizados.*', 'codigoitens.descricao', 'movimentacaocontratocontas.*');
        $this->crud->addClause('join', 'movimentacaocontratocontas', 'movimentacaocontratocontas.id',  '=',  'lancamentos.movimentacao_id');
        $this->crud->addClause('join', 'codigoitens', 'codigoitens.id',  '=',  'movimentacaocontratocontas.tipo_id');
        $this->crud->addClause('join', 'contratoterceirizados', 'contratoterceirizados.id',  '=',  'lancamentos.contratoterceirizado_id');
        $this->crud->addClause('orderby', 'lancamentos.id', 'desc');
        // filtros na listagem
        $this->adicionaFiltros();
    }
    public function adicionaFiltros()
    {
        $this->adicionaFiltroFuncionario();
        $this->adicionaFiltroMovimentacao();
        // $this->adicionaFiltroEncargos();   // s??o os encargos
        $this->adicionaFiltroMes();
        $this->adicionaFiltroAno();
    }
    public function Colunas()
    {
        $colunas = [
            [
                'name'  => 'nome',
                'label' => 'Empregado',
                'type'  => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('nome', 'ilike', "%$searchTerm%");
                },
            ],
            [
                'name' => 'getTipoMovimentacao',
                'label' => 'Tipo da movimenta????o', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoMovimentacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name'  => 'mes_competencia',
                'label' => 'M??s',
                'type'  => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('c2.descricao', 'ilike', "%$searchTerm%");
                // },
            ],
            [
                'name'  => 'ano_competencia',
                'label' => 'Ano',
                'type'  => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('c2.descricao', 'ilike', "%$searchTerm%");
                // },
            ],
            [
                'name' => 'getTipoEncargoOuGrupoA',
                'label' => 'Verba', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoEncargoOuGrupoA', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('cod_encargo.descricao', 'ilike', "%$searchTerm%");
                // },
            ],
            [
                'name'  => 'valor',
                'label' => 'Valor',
                'type'  => 'text',
                'prefix' => 'R$ '
            ],
            [
                'name' => 'getNomeResumidoUnidadeMovimentacao',
                'label' => 'Unidade do servidor que cadastrou', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getNomeResumidoUnidadeMovimentacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name'  => 'data_lancamento',
                'label' => 'Data / Hora',
                'type'  => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('lancamentos.created_at', 'ilike', "%$searchTerm%");
                },
            ],
        ];
        return $colunas;
    }
    // IN??CIO M??TODOS FILTROS
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
            'label' => 'M??s'
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
    // public function adicionaFiltroEncargos()
    // {
    //     $campo = [
    //         'name' => 'encargo',
    //         'type' => 'select2_multiple',
    //         'label' => 'Tipo Verba'
    //     ];
    //     $encargos = $this->getEncargos();
    //     $this->crud->addFilter(
    //         $campo,
    //         $encargos,
    //         function ($value) {
    //             $this->crud->addClause('whereIn'
    //                 , 'c1.id', json_decode($value));
    //         }
    //     );
    // }
    public function adicionaFiltroFuncionario()
    {
        $campo = [
            'name' => 'funcionario',
            'type' => 'select2_multiple',
            'label' => 'Empregado'
        ];
        $funcionarios = $this->getFuncionarios();
        $this->crud->addFilter(
            $campo,
            $funcionarios,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'contratoterceirizados.id', json_decode($value));
            }
        );
    }
    public function adicionaFiltroMovimentacao()
    {
        $campo = [
            'name' => 'movimentacao',
            'type' => 'select2_multiple',
            'label' => 'Tipo Movimenta????o'
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
        ->where('m.contratoconta_id', '=', $contratoconta_id)
        ->pluck('m.mes_competencia', 'm.mes_competencia')
        ->toArray();
        return $dados;
    }
    private function getAnos()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        $contratoconta_id = \Route::current()->parameter('contratoconta_id');
        $dados = \DB::table('movimentacaocontratocontas as m')
        ->where('m.contratoconta_id', '=', $contratoconta_id)
        ->pluck('m.ano_competencia', 'm.ano_competencia')
        ->toArray();
        return $dados;
    }
    private function getEncargos()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        $contratoconta_id = \Route::current()->parameter('contratoconta_id');
        $dados = \DB::table('codigoitens as ci')
        ->join('encargos as e', 'e.tipo_id', '=', 'ci.id')
        ->pluck('ci.descricao', 'ci.id')
        ->toArray();
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
    private function getFuncionarios()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        $dados = Contratoterceirizado::where('contrato_id', '=', $contrato_id)
            ->orderBy('nome')
            ->pluck('nome', 'id')
            ->toArray();
        return $dados;
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
