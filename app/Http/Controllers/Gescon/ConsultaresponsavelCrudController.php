<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contratoresponsavel;

/*
use App\Models\Contrato;
use App\Models\Contratofatura;
use App\Models\Fornecedor;
use App\Models\Justificativafatura;
use App\Models\Tipolistafatura;
// use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
*/

/**
 * Class ConsultaresponsavelCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 * @author Anderson Sathler <asathler@gmail.com>
 */
class ConsultaresponsavelCrudController extends ConsultaContratoBaseCrudController
{
    /**
     * Configurações iniciais do Backpack
     *
     * @throws \Exception
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setModel('App\Models\Contratoresponsavel');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/consulta/responsaveis');
        $this->crud->setEntityNameStrings('Responsável', 'Responsáveis');
        $this->crud->setHeading('Consulta Responsáveis por Contrato');

        $this->crud->addClause('leftJoin', 'contratos',
            'contratos.id', '=', 'contratoresponsaveis.contrato_id'
        );
        $this->crud->addClause('select', [
            'contratoresponsaveis.*'
        ]);

        // Apenas ocorrências da unidade atual
        $this->crud->addClause('where', 'contratos.unidade_id', '=', session('user_ug_id'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->defineConfiguracaoPadrao();
        $this->adicionaColunasEspecificasNaListagem();
    }

    /**
     * Action para exibição de um único registro
     *
     * @param int $id
     * @return \Backpack\CRUD\app\Http\Controllers\Operations\Response
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'contrato_id',
            'user_id',
            'funcao_id',
            'instalacao_id',
            'data_inicio',
            'data_fim',
            'situacao'
        ]);

        return $content;
    }

    /**
     * Adiciona as colunas específicas a serem exibidas bem como suas definições
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaColunasEspecificasNaListagem()
    {
        $this->adicionaColunaResponsavel();
        $this->adicionaColunaFuncao();
        $this->adicionaColunaInstalacao();
        $this->adicionaColunaPortaria();
        $this->adicionaColunaDataInicio();
        $this->adicionaColunaDataFim();
        $this->adicionaColunaSituacao();
    }

    private function adicionaColunaResponsavel()
    {
        $this->crud->addColumn([
            'name' => 'getUser',
            'label' => 'Responsável',
            'type' => 'model_function',
            'function_name' => 'getUser',
            'priority' => 10,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    private function adicionaColunaFuncao()
    {
        $this->crud->addColumn([
            'name' => 'getFuncao',
            'label' => 'Função',
            'type' => 'model_function',
            'function_name' => 'getFuncao',
            'priority' => 11,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    private function adicionaColunaInstalacao()
    {
        $this->crud->addColumn([
            'name' => 'getInstalacao',
            'label' => 'Instalação',
            'type' => 'model_function',
            'function_name' => 'getInstalacao',
            'priority' => 12,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    private function adicionaColunaPortaria()
    {
        $this->crud->addColumn([
            'name' => 'portaria',
            'label' => 'Portaria',
            'type' => 'string',
            'priority' => 13,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    private function adicionaColunaDataInicio()
    {
        $this->crud->addColumn([
            'name' => 'getDataInicio',
            'label' => 'Data Início',
            'type' => 'model_function',
            'function_name' => 'getDataInicio',
            'priority' => 14,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    private function adicionaColunaDataFim()
    {
        $this->crud->addColumn([
            'name' => 'getDataFim',
            'label' => 'Data Fim',
            'type' => 'model_function',
            'function_name' => 'getDataFim',
            'priority' => 15,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    private function adicionaColunaSituacao()
    {
        $this->crud->addColumn([
            'name' => 'getSituacao',
            'label' => 'Situação',
            'type' => 'model_function',
            'function_name' => 'getSituacao',
            'priority' => 16,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }





























    /**
     * Retorna colunas a serem exibidas bem como suas definições
     *
     * @return array[]
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaColunas()
    {
        $colunas = array();

        $colunas[] = [
            'name' => 'contrato.numero',
            'label' => 'Número Contrato',
            'type' => 'string',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'getFornecedor',
            'label' => 'Fornecedor',
            'type' => 'model_function',
            'function_name' => 'getFornecedor',
            'priority' => 2,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('fornecedores.cpf_cnpj_idgener',
                    'ilike', '%' . $searchTerm . '%'
                );
                $query->orWhere('fornecedores.nome',
                    'ilike', '%' . $searchTerm . '%'
                );
            }
        ];

        $colunas[] = [
            'name' => 'contrato.objeto',
            'label' => 'Objeto',
            'limit' => 150,
            'priority' => 3,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'getVigenciaInicio',
            'label' => 'Vig. Início',
            'type' => 'model_function',
            'function_name' => 'getVigenciaInicio',
            'priority' => 6,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'getVigenciaFim',
            'label' => 'Vig. Fim',
            'type' => 'model_function',
            'function_name' => 'getVigenciaFim',
            'priority' => 5,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'getValorGlobal',
            'label' => 'Valor Global',
            'type' => 'model_function',
            'function_name' => 'getvalorGlobal',
            'prefix' => 'R$ ',
            'priority' => 4,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'contrato.num_parcelas',
            'label' => 'Núm. Parcelas',
            'priority' => 8,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'getValorParcela',
            'label' => 'Valor Parcela',
            'type' => 'model_function',
            'function_name' => 'getValorParcela',
            'prefix' => 'R$ ',
            'priority' => 7,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'getTipoListaFatura',
            'label' => 'Tipo Lista',
            'type' => 'model_function',
            'function_name' => 'getTipoListaFatura',
            'priority' => 9,
            'limit' => 150,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('tipolistafatura.nome', 'ilike',
                    '%' . $searchTerm . '%'
                );
            }
        ];

        $colunas[] = [
            'name' => 'getJustificativa',
            'label' => 'Justificativa',
            'type' => 'model_function',
            'function_name' => 'getJustificativa',
            'priority' => 10,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('justificativafatura.nome', 'ilike',
                    '%' . $searchTerm . '%'
                );
            }
        ];

        $colunas[] = [
            'name' => 'numero',
            'label' => 'Número',
            'type' => 'string',
            'priority' => 11,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'emissao',
            'label' => 'Dt. Emissão',
            'type' => 'date',
            'priority' => 12,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'ateste',
            'label' => 'Dt. Ateste',
            'type' => 'date',
            'priority' => 13,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'vencimento',
            'label' => 'Dt. Vencimento',
            'type' => 'date',
            'priority' => 14,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'prazo',
            'label' => 'Prazo Pagamento',
            'type' => 'date',
            'priority' => 15,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'formatValor',
            'label' => 'Valor',
            'type' => 'model_function',
            'function_name' => 'formatValor',
            'priority' => 16,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'formatJuros',
            'label' => 'Juros',
            'type' => 'model_function',
            'function_name' => 'formatJuros',
            'priority' => 17,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'formatMulta',
            'label' => 'Multa',
            'type' => 'model_function',
            'function_name' => 'formatMulta',
            'priority' => 18,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'formatGlosa',
            'label' => 'Glosa',
            'type' => 'model_function',
            'function_name' => 'formatGlosa',
            'priority' => 19,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'formatValorLiquido',
            'label' => 'Vr. Líquido a pagar',
            'type' => 'model_function',
            'function_name' => 'formatValorLiquido',
            'priority' => 20,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'processo',
            'label' => 'Processo',
            'type' => 'string',
            'priority' => 21,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'protocolo',
            'label' => 'Dt. Protocolo',
            'type' => 'date',
            'priority' => 22,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'infcomplementar',
            'label' => 'Inform. Complementares',
            'type' => 'string',
            'priority' => 23,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'repactuacao',
            'label' => 'Repactuação',
            'type' => 'boolean',
            'options' => [
                0 => 'Não',
                1 => 'Sim'
            ],
            'priority' => 24,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'mesref',
            'label' => 'Mês Ref.',
            'type' => 'number',
            'priority' => 25,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'anoref',
            'label' => 'Ano Ref.',
            'type' => 'string',
            'priority' => 26,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        $colunas[] = [
            'name' => 'retornaSituacao',
            'label' => 'Situação',
            'type' => 'model_function',
            'function_name' => 'retornaSituacao',
            'priority' => 27,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        return $colunas;
    }

    /**
     * Retorna array dos campos para exibição no form
     *
     * @param int $fornecedorId
     * @param string $fornecedorDesc
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaCampos($fornecedorId = 0, $fornecedorDesc = '')
    {
        $justificativas = $this->retornaJustificativasCombo();

        $campos = array();

        $campos[] = [
            'name' => 'num_contrato',
            'label' => 'Número Contrato',
            'attributes' => [
                'readonly'=>'readonly',
                'style' => 'pointer-events: none;touch-action: none;',
                'class' => 'form-control mostraCamposRelacionados',
                'data-campo' => 'numero'
            ]
        ];

        $campos[] = [
            'name' => 'desc_fornecedor',
            'label' => 'Fornecedor',
            'type' => 'text',
            'value' => $fornecedorDesc,
            'attributes' => [
                'readonly'=>'readonly',
                'style' => 'pointer-events: none;touch-action: none;',
            ]
        ];

        $campos[] = [
            'name' => 'justificativafatura_id',
            'label' => 'Justificativa',
            'type' => 'select_from_array',
            'options' => $justificativas,
            'default'    => null,
            'placeholder'    => '123',
            'allows_null' => false
        ];

        $campos[] = [
            'name' => 'situacao',
            'label' => 'Situação',
            'type' => 'select_from_array',
            'options' => config('app.situacao_fatura'),
            'default'    => 'PEN',
            'allows_null' => false
        ];

        $campos[] = [
            'name' => 'empenhos',
            'label' => 'Empenhos',
            'type' => 'select2_multiple',
            'model' => 'App\Models\Empenho',
            'entity' => 'empenhos',
            'attribute' => 'numero',
            'attribute2' => 'aliquidar',
            'attribute_separator' => ' - Valor a Liquidar: R$ ',
            'pivot' => true,
            'options' => (function ($query) use ($fornecedorId) {
                return $query->orderBy('numero', 'ASC')
                    ->where('unidade_id', session()->get('user_ug_id'))
                    ->where('fornecedor_id', $fornecedorId)
                    ->get();
            })
        ];

        $campos[] = [
            'name' => 'contrato',
            'label' => 'dados_contrato',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'dados_contrato'
            ]
        ];

        return $campos;
    }

    /**
     * Adiciona todos os filtros desejados para esta funcionalidade
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaFiltros()
    {
        /*
        $this->adicionaFiltroNumeroFatura();
        // $this->adicionaFiltroNumeroContrato();
        // $this->adicionaFiltroFornecedor();
        $this->adicionaFiltroTipoLista();
        $this->adicionaFiltroJustificativa();
        $this->adicionaFiltroDataEmissao();
        $this->adicionaFiltroDataAteste();
        $this->adicionaFiltroDataVencimento();
        $this->adicionaFiltroDataPrazoPagamento();
        $this->adicionaFiltroDataProtocolo();
        $this->adicionaFiltroSituacao();
        */
    }

    /**
     * Adiciona o filtro ao campo Número da Fatura
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaFiltroNumeroFatura()
    {
        $campo = [
            'name' => 'numero',
            'type' => 'text',
            'label' => 'Núm. Fatura'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $this->crud->addClause('where', 'contratofaturas.numero', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Tipo de Lista
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaFiltroTipoLista()
    {
        $campo = [
            'name' => 'tipo_lista',
            'type' => 'select2',
            'label' => 'Tipo Lista'
        ];

        $tiposLista = $this->retornaTiposLista();

        $this->crud->addFilter(
            $campo,
            $tiposLista,
            function ($value) {
                $this->crud->addClause('where', 'contratofaturas.tipolistafatura_id', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Justificativa
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaFiltroJustificativa()
    {
        $campo = [
            'name' => 'justificativa',
            'type' => 'select2',
            'label' => 'Justificativa'
        ];

        $justificativas = $this->retornaJustificativas();

        $this->crud->addFilter(
            $campo,
            $justificativas,
            function ($value) {
                $this->crud->addClause('where', 'contratofaturas.justificativafatura_id', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Data de Emissão
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaFiltroDataEmissao()
    {
        $campo = [
            'name' => 'dt_emissao',
            'type' => 'date_range',
            'label' => 'Dt. Emissão'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratofaturas.emissao', '>=', $dates->from . ' 00:00:00');
                $this->crud->addClause('where', 'contratofaturas.emissao', '<=', $dates->to . ' 23:59:59');
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Data de Ateste
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaFiltroDataAteste()
    {
        $campo = [
            'name' => 'dt_ateste',
            'type' => 'date_range',
            'label' => 'Dt. Ateste'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratofaturas.ateste', '>=', $dates->from . ' 00:00:00');
                $this->crud->addClause('where', 'contratofaturas.ateste', '<=', $dates->to . ' 23:59:59');
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Data de Vencimento
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaFiltroDataVencimento()
    {
        $campo = [
            'name' => 'dt_vencimento',
            'type' => 'date_range',
            'label' => 'Dt. Vencimento'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratofaturas.vencimento', '>=', $dates->from . ' 00:00:00');
                $this->crud->addClause('where', 'contratofaturas.vencimento', '<=', $dates->to . ' 23:59:59');
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Data do Prazo de Pagamento
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaFiltroDataPrazoPagamento()
    {
        $campo = [
            'name' => 'dt_prazo',
            'type' => 'date_range',
            'label' => 'Prazo Pagamento'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratofaturas.prazo', '>=', $dates->from . ' 00:00:00');
                $this->crud->addClause('where', 'contratofaturas.prazo', '<=', $dates->to . ' 23:59:59');
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Data do Protocolo
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaFiltroDataProtocolo()
    {
        $campo = [
            'name' => 'dt_protocolo',
            'type' => 'date_range',
            'label' => 'Dt. Protocolo'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratofaturas.protocolo', '>=', $dates->from . ' 00:00:00');
                $this->crud->addClause('where', 'contratofaturas.protocolo', '<=', $dates->to . ' 23:59:59');
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Situação
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaFiltroSituacao()
    {
        $campo = [
            'name' => 'situacao',
            'type' => 'select2',
            'label' => 'Situação'
        ];

        $situacoes = config('app.situacao_fatura');

        $this->crud->addFilter(
            $campo,
            $situacoes,
            function ($value) {
                $this->crud->addClause('where', 'contratofaturas.situacao', $value);
            }
        );
    }

    /**
     * Retorna dados dos Contratos para exibição no controle de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    /*
    private function retornaContratos()
    {
        $dados = Contrato::select(
            DB::raw("LEFT(CONCAT(numero, ' - ', objeto), 80) AS descricao"), 'numero'
        );

        $dados->where('situacao', true);
        $dados->whereHas('unidade', function ($u) {
            $u->where('codigo', session('user_ug'));
        });
        $dados->orderBy('id'); // 'data_publicacao'

        return $dados->pluck('descricao', 'numero')->toArray();
    }
    */

    /**
     * Retorna dados de Fornecedores para exibição no controle de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    /*
    private function retornaFornecedores()
    {
        $dados = Fornecedor::select(
            DB::raw("CONCAT(cpf_cnpj_idgener, ' - ', nome) AS descricao"), 'cpf_cnpj_idgener'
        );

        $dados->whereHas('contratos', function ($c) {
            $c->where('situacao', true);
        });

        return $dados->pluck('descricao', 'cpf_cnpj_idgener')->toArray();
    }
    */

    /**
     * Retorna dados de Tipos de Lista para exibição no controle de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaTiposLista()
    {
        $dados = Tipolistafatura::select('nome as descricao', 'id');

        return $dados->pluck('descricao', 'id')->toArray();
    }

    /**
     * Retorna dados das Justificativas para exibição no controle de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaJustificativas()
    {
        $dados = Justificativafatura::select(
            'id',
            DB::raw("CONCAT(nome, ' - ', LEFT(descricao, 80)) as descricao")
        );

        return $dados->pluck('descricao', 'id')->toArray();
    }

    /**
     * Retorna dados das Justificativas para exibição no combo de edição
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaJustificativasCombo()
    {
        $justificativas = $this->retornaJustificativas();
        $justificativas[''] = 'Selecione a justificativa';

        ksort($justificativas);

        return $justificativas;
    }

}
