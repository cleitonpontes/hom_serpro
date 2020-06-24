<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratosfpadraoRequest as StoreRequest;
use App\Http\Requests\ContratosfpadraoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ContratosfpadraoCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 */
class ContratosfpadraoCrudController extends CrudController
{
    public function setup()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        $contrato = Contrato::where('id','=',$contrato_id)
            ->where('unidade_id','=',session()->get('user_ug_id'))->first();
        if(!$contrato){
            abort('403', config('app.erro_permissao'));
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratosfpadrao');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/'.$contrato_id.'/padrao');
        $this->crud->setEntityNameStrings('Padrão do Contrato', 'Padrões de Execução SIAFI - Contrato');
        $this->crud->addClause('join', 'contratos', 'contratos.id','=','sfpadrao.fk');
        $this->crud->addClause('where', 'sfpadrao.fk', '=', $contrato_id);
        $this->crud->addClause('where', 'sfpadrao.categoriapadrao', '=', 'EXECFATURAPADRAO');
        $this->crud->addClause('where', 'sfpadrao.tipo', '=', 'P');
        $this->crud->addClause('select', 'sfpadrao.*');


        $this->crud->enableExportButtons();
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');


        (backpack_user()->can('contratosfpadrao_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contratosfpadrao_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contratosfpadrao_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //SS$this->crud->setFromDb();
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);


        $contratoxfornecedor = Contrato::select(DB::raw('contratos.id,contratos.numero,fornecedores.cpf_cnpj_idgener,fornecedores.nome'))
                                    ->join('fornecedores','fornecedores.id','=','contratos.fornecedor_id')
                                    ->where('contratos.id',$contrato_id)
                                    ->first();

        $campos = $this->Campos($contratoxfornecedor);
        $this->crud->addFields($campos);


        // add asterisk for fields that are required in ContratosfpadraoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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


    public function Colunas()
    {

        $colunas = [
            [
                'name' => 'getNumeroContrato',
                'label' => 'Número Contrato', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getNumeroContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratos.numero', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'decricaopadrao',
                'label' => 'Descrição',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'codugemit',
                'label' => 'UG',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'anodh',
                'label' => 'Ano DH',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'codtipodh',
                'label' => 'Tipo DH',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'numdh',
                'label' => 'Número DH',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'msgretorno',
                'label' => 'Mensagem Retorno',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getSituacao',
                'label' => 'Situação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSituacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
        ];

        return $colunas;
    }


    public function Campos($contratoxfornecedor)
    {

        $campos = [
                        [   // Hidden
                            'name' => 'fk',
                            'type' => 'hidden',
                            'default' => $contratoxfornecedor->id,
                        ],
                        [
                            'name' => 'codugemit',
                            'label' => 'UG',
                            'type' => 'text',
                            'value' => session()->get('user_ug'),
                            'attributes' => [
                                'readonly'=>'readonly',
                                'style' => 'pointer-events: none;touch-action: none;',
                            ]
                        ],
                        [
                            'name' => 'fornecedor',
                            'label' => 'Fornecedor',
                            'type' => 'text',
                            'value' => ($contratoxfornecedor->numero.' | '.$contratoxfornecedor->cpf_cnpj_idgener.' - '.$contratoxfornecedor->nome),
                            'attributes' => [
                                'readonly'=>'readonly',
                                'style' => 'pointer-events: none;touch-action: none;',
                            ]
                        ],
                        [
                            'name' => 'decricaopadrao',
                            'label' => 'Descrição Padrão',
                            'type' => 'text',
                            'attributes' => [
                                'onkeyup' => "maiuscula(this)",
                            ]
                        ],
                        [
                            'name' => 'anodh',
                            'label' => 'Ano DH',
                            'type' => 'anoquatrodigitos',
                        ],
                        [
                            'name' => 'codtipodh',
                            'label' => 'Código Tipo DH',
                            'type' => 'codtipodh',
                            'attributes' => [
                                'onkeyup' => "maiuscula(this)",
                            ]
                        ],
                        [
                            'name' => 'numdh',
                            'label' => 'Número DH',
                            'type' => 'number',
                        ],
                        [  // Hidden
                            'name' => 'categoriapadrao',
                            'type' => 'hidden',
                            'default' => 'EXECFATURAPADRAO',
                        ],
                        [  // Hidden
                            'name' => 'tipo',
                            'type' => 'hidden',
                            'default' => 'P',
                        ],
                        [  // Hidden
                            'name' => 'situacao',
                            'type' => 'hidden',
                            'default' => 'P',
                        ],
                    ];


        return $campos;
    }
}
