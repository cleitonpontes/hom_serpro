<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratohistoricoRequest as StoreRequest;
use App\Http\Requests\ContratohistoricoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ContratohistoricoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratohistoricoCrudController extends CrudController
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
        $this->crud->setModel('App\Models\Contratohistorico');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/'.$contrato_id.'/historico');
        $this->crud->setEntityNameStrings('Histórico do Contrato', 'Histórico - Contrato');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->orderBy('data_assinatura', 'asc');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        // add asterisk for fields that are required in ContratohistoricoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getReceitaDespesa',
                'label' => 'Receita / Despesa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getReceitaDespesa', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'numero',
                'label' => 'Número Contrato',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getUnidade',
                'label' => 'Unidade Gestora', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getTipo',
                'label' => 'Tipo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipo', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getCategoria',
                'label' => 'Categoria', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getCategoria', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getFornecedor',
                'label' => 'Fornecedor', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getFornecedor', // the method in your Model
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%$searchTerm%");
                    $query->orWhere('fornecedores.nome', 'like', "%$searchTerm%");
                },
            ],
            [
                'name' => 'processo',
                'label' => 'Processo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'objeto',
                'label' => 'Objeto',
                'type' => 'text',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'info_complementar',
                'label' => 'Informações Complementares',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'vigencia_inicio',
                'label' => 'Vig. Início',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'vigencia_fim',
                'label' => 'Vig. Fim',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrGlobal',
                'label' => 'Valor Global', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrGlobal', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'num_parcelas',
                'label' => 'Núm. Parcelas',
                'type' => 'number',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrParcela',
                'label' => 'Valor Parcela', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrParcela', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
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
