<?php

namespace App\Http\Controllers\Gescon;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation

/**
 * Class MeucontratoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MeucontratoCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contrato');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/meus-contratos');
        $this->crud->setEntityNameStrings('Meu Contrato', 'Meus Contratos');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');

        $this->crud->addClause('whereHas', 'responsaveis', function($query) {
            $query->whereHas('user', function ($query) {
                $query->where('id', '=', backpack_user()->id);
            });
        });

        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));

        $this->crud->addClause('select', 'contratos.*');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');
        $this->crud->addButtonFromView('line', 'moremeucontrato', 'moremeucontrato', 'end');


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Global
        |--------------------------------------------------------------------------
        */
//        $this->crud->addButtonFromView('line', 'morecontrato', 'morecontrato', 'end');



        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Collumns Table
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumns([
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
//                'searchLogic'   => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('unidades.codigo', 'like', "%$searchTerm%");
//                    $query->orWhere('unidades.nome', 'like', "%$searchTerm%");
//                    $query->orWhere('unidades.nomeresumido', 'like', "%$searchTerm%");
//                },
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
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

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
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

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
                'searchLogic'   => function (Builder $query, $column, $searchTerm) {
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
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

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
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

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
        ]);

    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('fornecedor_id');
        $this->crud->removeColumn('tipo_id');
        $this->crud->removeColumn('categoria_id');
        $this->crud->removeColumn('unidade_id');
        $this->crud->removeColumn('info_complementar');
        $this->crud->removeColumn('fundamento_legal');
        $this->crud->removeColumn('modalidade_id');
        $this->crud->removeColumn('licitacao_numero');
        $this->crud->removeColumn('data_assinatura');
        $this->crud->removeColumn('data_publicacao');
        $this->crud->removeColumn('valor_inicial');
        $this->crud->removeColumn('valor_global');
        $this->crud->removeColumn('valor_parcela');
        $this->crud->removeColumn('valor_acumulado');
        $this->crud->removeColumn('situacao_siasg');

        return $content;
    }
}
