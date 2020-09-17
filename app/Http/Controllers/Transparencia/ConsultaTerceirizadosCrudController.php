<?php

namespace App\Http\Controllers\Transparencia;


use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ConsultaTerceirizadosCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ConsultaTerceirizadosCrudController extends ConsultaContratoBaseCrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setModel('App\Models\Contratoterceirizado');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/transparencia/terceirizados');
        $this->crud->setEntityNameStrings('Consulta Terceirizados', 'Consulta Terceirizados');

        $this->crud->addClause('select', 'contratoterceirizados.*');
        $this->crud->addClause('join', 'contratos', 'contratos.id', '=', 'contratoterceirizados.contrato_id');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $this->crud->addClause('join', 'orgaos', 'orgaos.id', '=', 'unidades.orgao_id');
        $this->crud->addClause('where', 'contratos.situacao', '=', true);


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->defineConfiguracaoPadrao();

    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('contrato_id');
        $this->crud->removeColumn('funcao_id');
        $this->crud->removeColumn('escolaridade_id');
        $this->crud->removeColumn('custo');
        $this->crud->removeColumn('salario');
        $this->crud->removeColumn('nome');
        $this->crud->removeColumn('cpf');
        $this->crud->removeColumn('aux_transporte');
        $this->crud->removeColumn('vale_alimentacao');

        return $content;
    }


    protected function aplicaFiltrosEspecificos(): void
    {
        $this->crud->addFilter([
            'name' => 'situacao',
            'type' => 'dropdown',
            'label' => 'Situação'
        ], [1 => 'Ativo', 0 => 'Inativo']
            , function ($value) {
                $this->crud->addClause('where', 'contratoterceirizados.situacao', $value);
            });
    }

    protected function adicionaColunasEspecificasNaListagem(): void
    {
        $this->crud->addColumns([
            [
                'name' => 'getFornecedor',
                'label' => 'Fornecedor',
                'type' => 'model_function',
                'function_name' => 'getFornecedor',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'getContrato',
                'label' => 'Número Contrato',
                'type' => 'model_function',
                'function_name' => 'getContrato',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [
                'name' => 'getCpf',
                'label' => 'CPF',
                'type' => 'model_function',
                'function_name' => 'getCpf',
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratoterceirizados.cpf', 'like', '%' . $searchTerm . '%');
                },
            ],
            [
                'name' => 'getNome',
                'label' => 'Nome',
                'type' => 'model_function',
                'function_name' => 'getNome',
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratoterceirizados.nome', 'like', '%' . strtoupper($searchTerm) . '%');
                },
            ],
            [
                'name' => 'getFuncao',
                'label' => 'Função',
                'type' => 'model_function',
                'function_name' => 'getFuncao',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhereHas('funcao', function ($q) use ($searchTerm) {
                        $q->where('descricao', 'like', '%' . $searchTerm . '%');
                    });
                },
            ],
            [
                'name' => 'descricao_complementar',
                'label' => 'Descrição Complementar',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'jornada',
                'label' => 'Jornada',
                'type' => 'number',
            ],
            [
                'name' => 'unidade',
                'label' => 'Unidade',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatVlrSalario',
                'label' => 'Salário',
                'type' => 'model_function',
                'function_name' => 'formatVlrSalario',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],
            [
                'name' => 'formatVlrCusto',
                'label' => 'Custo',
                'type' => 'model_function',
                'function_name' => 'formatVlrCusto',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],

            [
                'name' => 'formatAuxTransporte',
                'label' => 'Auxílio Trasporte', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatAuxTransporte', // the method in your Model
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
                'name' => 'formatValeAlimentacao',
                'label' => 'Vale Alimentação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatValeAlimentacao', // the method in your Model
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
                'name' => 'getEscolaridade',
                'label' => 'Escolaridade',
                'type' => 'model_function',
                'function_name' => 'getEscolaridade',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhereHas('escolaridade', function ($q) use ($searchTerm) {
                        $q->where('descricao', 'like', '%' . $searchTerm . '%');
                    });
                },
            ],
            [   // Date
                'name' => 'data_inicio',
                'label' => 'Data Início',
                'type' => 'date',
            ],
            [   // Date
                'name' => 'data_fim',
                'label' => 'Data Fim',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // optionally override the Yes/No texts
                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],
        ]);
    }

}
