<?php

namespace App\Http\Controllers\Transparencia;

use App\Models\Justificativafatura;
use App\Models\Tipolistafatura;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ConsultaFaturasCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ConsultaFaturasCrudController extends ConsultaContratoBaseCrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setModel('App\Models\Contratofatura');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/transparencia/faturas');
        $this->crud->setEntityNameStrings('Consulta Faturas', 'Consulta Faturas');

        $this->crud->addClause('select', 'contratofaturas.*');
        $this->crud->addClause('join', 'contratos', 'contratos.id', '=', 'contratofaturas.contrato_id');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $this->crud->addClause('join', 'orgaos', 'orgaos.id', '=', 'unidades.orgao_id');
        $this->crud->addClause('where', 'contratos.situacao', '=', true);
        $this->crud->addClause('where', 'unidades.sigilo', '=', false);
        $this->crud->orderBy('contratofaturas.ateste', 'asc');

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

        $this->crud->removeColumns([
            'contrato_id',
            'tipolistafatura_id',
            'justificativafatura_id',
            'valor',
            'juros',
            'multa',
            'glosa',
            'valorliquido',
        ]);

        return $content;
    }

    protected function aplicaFiltrosEspecificos(): void
    {
        $tipolista = Tipolistafatura::where('situacao', true)
            ->pluck('nome', "id")
            ->toArray();

        $this->crud->addFilter([ // dropdown filter
            'name' => 'tipolista',
            'type' => 'select2_multiple',
            'label' => 'Tipo Lista'
        ], $tipolista
            , function ($value) {
                $this->crud->addClause('whereIN'
                    , 'contratofaturas.tipolistafatura_id', json_decode($value));
            });

        $justificativa = Justificativafatura::where('situacao', true)
            ->pluck('nome', "id")
            ->toArray();

        $this->crud->addFilter([ // dropdown filter
            'name' => 'justificativa',
            'type' => 'select2_multiple',
            'label' => 'Justificativa'
        ], $justificativa
            , function ($value) {
                $this->crud->addClause('whereIn'
                    , 'contratofaturas.justificativafatura_id', json_decode($value));
            });

        $this->crud->addFilter([ // dropdown filter
            'name' => 'situacao',
            'type' => 'select2_multiple',
            'label' => 'Situação'
        ], config('app.situacao_fatura')
            , function ($value) { // if the filter is active
                $this->crud->addClause('whereIn'
                    , 'contratofaturas.situacao', json_decode($value));
            });
    }

    protected function adicionaColunasEspecificasNaListagem(): void
    {
        $this->crud->addColumns([

            [
                'name' => 'getTipoLista',
                'label' => 'Tipo Lista',
                'type' => 'model_function',
                'function_name' => 'getTipoLista',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getJustificativa',
                'label' => 'Justificativa',
                'type' => 'model_function',
                'function_name' => 'getJustificativa',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('justificativafatura.nome', 'like', "%" . strtoupper($searchTerm) . "%");
//                },
            ],
            [
                'name' => 'getFornecedor',
                'label' => 'Fornecedor',
                'type' => 'model_function',
                'function_name' => 'getFornecedor',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'getNumero',
                'label' => 'Número',
                'type' => 'model_function',
                'function_name' => 'getNumero',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratofaturas.numero', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'emissao',
                'label' => 'Dt. Emissão',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'ateste',
                'label' => 'Dt. Ateste',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'vencimento',
                'label' => 'Dt. Vencimento',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'prazo',
                'label' => 'Prazo Pagamento',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatValor',
                'label' => 'Valor',
                'type' => 'model_function',
                'function_name' => 'formatValor',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratofaturas.valor', 'like', "%" . $searchTerm . "%");
                },
            ],
            [
                'name' => 'formatJuros',
                'label' => 'Juros',
                'type' => 'model_function',
                'function_name' => 'formatJuros',
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
                'name' => 'formatMulta',
                'label' => 'Multa',
                'type' => 'model_function',
                'function_name' => 'formatMulta',
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
                'name' => 'formatGlosa',
                'label' => 'Glosa',
                'type' => 'model_function',
                'function_name' => 'formatGlosa',
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
                'name' => 'formatValorLiquido',
                'label' => 'Valor Líquido a pagar',
                'type' => 'model_function',
                'function_name' => 'formatValorLiquido',
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
                'name' => 'getProcesso',
                'label' => 'Processo',
                'type' => 'model_function',
                'function_name' => 'getProcesso',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratofaturas.processo', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'protocolo',
                'label' => 'Dt. Protocolo',
                'type' => 'date',
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
                'name' => 'infcomplementar',
                'label' => 'Informações Complementares',
                'type' => 'text',
                'limit' => 255,
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'repactuacao',
                'label' => 'Repactuação',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // optionally override the Yes/No texts
                'options' => [0 => 'Não', 1 => 'Sim']
            ],
            [
                'name' => 'mesref',
                'label' => 'Mês Referência',
                'type' => 'text',
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
                'name' => 'anoref',
                'label' => 'Ano Referência',
                'type' => 'text',
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
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'select_from_array',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // optionally override the Yes/No texts
                'options' => config('app.situacao_fatura')
            ],
        ]);
    }

}
