<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratoprepostoRequest as StoreRequest;
use App\Http\Requests\ContratoprepostoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ContratoprepostoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratoprepostoCrudController extends CrudController
{
    public function setup()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');

        $contrato = Contrato::where('id','=',$contrato_id)
            ->where('unidade_id','=',session()->get('user_ug_id'))->first();
        if(!$contrato) {
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratopreposto');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/'.$contrato_id.'/prepostos');
        $this->crud->setEntityNameStrings('Preposto do Contrato', 'Prepostos do Contrato');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('preposto_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('preposto_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('preposto_deletar')) ? $this->crud->allowAccess('delete') : null;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $con = $contrato->where('id', '=', $contrato_id)
            ->pluck('numero', 'id')
            ->toArray();

        $campos = $this->Campos($con);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in ContratoprepostoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getContrato',
                'label' => 'Contrato', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [
                'name' => 'cpf',
                'label' => 'CPF',
                'type' => 'text',
            ],
            [
                'name' => 'nome',
                'label' => 'Nome',
                'type' => 'text',
            ],
            [
                'name' => 'email',
                'label' => 'E-mail',
                'type' => 'email',
            ],
            [
                'name' => 'telefonefixo',
                'label' => 'Telefone Fixo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'celular',
                'label' => 'Celular',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'doc_formalizacao',
                'label' => 'Doc. Formalização',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'informacao_complementar',
                'label' => 'Inform. Complementar',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'data_inicio',
                'label' => 'Data Início',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'data_fim',
                'label' => 'Data Fim',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],[
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],

        ];

        return $colunas;

    }

    public function campos($con)
    {
        $campos = [

            [
                'name' => 'cpf',
                'label' => 'CPF',
                'type' => 'cpf',
//                'wrapperAttributes' => [
//                    'class' => 'form-group col-md-3'
//                ],
                'tab' => 'Dados Preposto',
            ],
            [
                'name' => 'nome',
                'label' => 'Nome Completo',
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ],
                'tab' => 'Dados Preposto',
//                'wrapperAttributes' => [
//                    'class' => 'form-group col-md-3'
//                ],
            ],
            [
                'name' => 'email',
                'label' => 'E-mail',
                'type' => 'email',
                'attributes' => [
                    'onkeyup' => "minusculo(this)"
                ],
                'tab' => 'Dados Preposto',
            ],
            [
                'name' => 'telefonefixo',
                'label' => 'Telefone Fixo',
                'type' => 'telefone',
                'tab' => 'Dados Preposto',
            ],
            [
                'name' => 'celular',
                'label' => 'Celular',
                'type' => 'celular',
                'tab' => 'Dados Preposto',
            ],
            [ // select_from_array
                'name' => 'contrato_id',
                'label' => "Contrato",
                'type' => 'select_from_array',
                'options' => $con,
                'allows_null' => false,
                'tab' => 'Outras Informações',
                'attributes' => [
                    'readonly'=>'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ], // chan
            ],
            [
                'name' => 'doc_formalizacao',
                'label' => 'Doc. Formalização',
                'type' => 'text',
                'tab' => 'Outras Informações',
            ],
            [
                'name' => 'informacao_complementar',
                'label' => 'Inform. Complementar',
                'type' => 'text',
                'tab' => 'Outras Informações',
            ],
            [
                'name' => 'data_inicio',
                'label' => 'Data Início',
                'type' => 'date',
                'tab' => 'Outras Informações',
            ],
            [
                'name' => 'data_fim',
                'label' => 'Data Fim',
                'type' => 'date',
                'tab' => 'Outras Informações',
            ],
            [ // select_from_array
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select_from_array',
                'options' => [1 => 'Ativo', 0 => 'Inativo'],
                'allows_null' => false,
                'tab' => 'Outras Informações',
            ],
        ];

        return $campos;
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

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('contrato_id');
        $this->crud->removeColumn('user_id');


        return $content;
    }
}
