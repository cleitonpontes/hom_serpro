<?php
// criado por mvascs@gmail.com em 15/03/2021

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use App\Models\Contratounidadedescentralizada;
use Backpack\CRUD\app\Http\Controllers\CrudController;

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
        $this->crud->setEntityNameStrings('contratounidadedescentralizada', 'Unidades Descentralizadas');
        $this->crud->addClause('select', 'contratounidadesdescentralizadas.*');
        $this->crud->addClause('join', 'contratos', 'contratos.id', '=', 'contratounidadesdescentralizadas.contrato_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratounidadesdescentralizadas.unidade_id');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addClause('orderby', 'unidades.nome');
        $this->crud->denyAccess('update');
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
}
