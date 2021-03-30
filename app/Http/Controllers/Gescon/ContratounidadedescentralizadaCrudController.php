<?php
// criado por mvascs@gmail.com em 15/03/2021

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use App\Models\ContratoItemMinutaEmpenho;
use App\Models\Contratounidadedescentralizada;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Traits\BuscaCodigoItens;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratounidadedescentralizadaRequest as StoreRequest;
use App\Http\Requests\ContratounidadedescentralizadaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ContratounidadedescentralizadaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratounidadedescentralizadaCrudController extends CrudController
{
    use BuscaCodigoItens;

    public function setup()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        $contrato = Contrato::where('id','=',$contrato_id)
            ->first();
        if(!$contrato){
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratounidadedescentralizada');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/'.$contrato_id.'/contratounidadedescentralizada');
        $this->crud->setEntityNameStrings('unidades', 'Unidades Descentralizadas');
        $this->crud->setShowView('vendor.backpack.crud.unidadedescentralizada.show');
        $this->crud->addClause('select', 'contratounidadesdescentralizadas.*');
        $this->crud->addClause('join', 'contratos', 'contratos.id', '=', 'contratounidadesdescentralizadas.contrato_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratounidadesdescentralizadas.unidade_id');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addClause('orderby', 'unidades.nome');
        $this->crud->denyAccess('update');
        $this->crud->allowAccess('show');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);
        $campos = $this->Campos($contrato);
        $this->crud->addFields($campos);
        // add asterisk for fields that are required in ContratounidadedescentralizadaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function show($id)
    {
        $content = parent::show($id);
        $this->crud->removeColumn('unidade_id');
        $this->adicionaBoxTotalPorAno(92720, 1);
//        $this->adicionaBoxTotalPorAno($contrato_id, $unidade_id);

        return $content;
    }

    public function Campos($contrato)
    {
        $campos = [
            [   // Hidden
                'name' => 'contrato_id',
                'type' => 'hidden',
                'default' => $contrato->id,
            ],
            [
                // n-n relationship
                'label' => "Unidades", // Table column heading
                'type' => "select2_from_ajax_multiple_single",
                'name' => 'unidades', // the column that contains the ID of that connected entity
                'entity' => 'unidades', // the method that defines the relationship in your Model
                'attribute' => "codigo", // foreign key attribute that is shown to user
                'attribute2' => "nomeresumido", // foreign key attribute that is shown to user
                'process_results_template' => 'gescon.process_results_multiple_unidade',
                'model' => "App\Models\Unidade", // foreign key model
                'data_source' => url("api/unidade"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Selecione a(s) Unidade(s)", // placeholder for the select
                'minimum_input_length' => 2, // minimum characters to type before querying results
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            ],
        ];

        return $campos;
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getContrato',
                'label' => 'Número do Instrumento', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('contratos.numero', 'ilike', "%" . strtoupper($searchTerm) . "%");
                // },

            ],
            [
                'name' => 'getUnidade',
                'label' => 'Unidade', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('unidades.nome', 'ilike', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('unidades.nomeresumido', 'ilike', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('unidades.codigo', 'ilike', "%" . strtoupper($searchTerm) . "%");
                },

            ],
            [
                'name' => 'getValorEmpenhado',
                'label' => 'Total empenhado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getValorEmpenhado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
        ];
        return $colunas;
    }
    public function store(StoreRequest $request)
    {
        $idContrato = $request->input('contrato_id');
        // vamos receber o array de unidades e varrê-lo salvando cada unidade
        $arrayUnidades = $request->input('unidades');
        foreach($arrayUnidades as $unidade_id){
            $request->request->set('unidade_id', $unidade_id);
            if( !$this->salvarUnidadeDescentralizada($request) ){
                // aqui quer dizer que o registro não foi salvo
                $mensagem = 'Problemas ao salvar as undiades!';
                \Alert::success($mensagem)->flash();
                $linkLocation = '/gescon/contrato/'.$idContrato.'/contratounidadedescentralizada';
                return redirect($linkLocation);
            }
        }
        // aqui quer dizer que os registros foram salvos
        $mensagem = 'Unidades salvas com sucesso!';
        \Alert::success($mensagem)->flash();
        // vamos redirecionar o usuário
        $linkLocation = '/gescon/contrato/'.$idContrato.'/contratounidadedescentralizada';
        return redirect($linkLocation);
    }
    public function salvarUnidadeDescentralizada($request){
        $objContratounidadeDescentralizada = new Contratounidadedescentralizada();
        $objContratounidadeDescentralizada->contrato_id = $request->input('contrato_id');
        $objContratounidadeDescentralizada->unidade_id = $request->input('unidade_id');
        if($objContratounidadeDescentralizada->save()){return true;} else {return false;}
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    private function adicionaBoxTotalPorAno($contrato_id, $unidade_id)
    {
       $situacao_empenho_emitido_id = $this->retornaIdCodigoItem('Situações Minuta Empenho', 'EMPENHO EMITIDO');

        $valores = ContratoItemMinutaEmpenho::distinct()
            ->select(DB::raw( "left(me.mensagem_siafi, 4) as ano, CONCAT('R$ ' , coalesce(sum(contrato_item_minuta_empenho.valor ),'0.00')) AS valor"))
            ->join('contratoitens AS ci','ci.id', '=', 'contrato_item_minuta_empenho.contrato_item_id')
            ->join('minutaempenhos AS me','me.id', '=', 'contrato_item_minuta_empenho.minutaempenho_id')
            ->where('ci.contrato_id', $contrato_id)
            ->where('me.unidade_id', $unidade_id)
            ->where('me.situacao_id', $situacao_empenho_emitido_id)
            ->groupBy(DB::raw('left(me.mensagem_siafi, 4)'))->get()->toArray();

        $this->crud->addColumn([
            'box' => 'valores',
            'name' => 'valores',
            'label' => 'Valores totais por ano', // Table column heading
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => false, // would make the modal too big
            'visibleInExport' => false, // not important enough
            'visibleInShow' => true, // sure, why not
            'values' => $valores
        ]);

    }
}
