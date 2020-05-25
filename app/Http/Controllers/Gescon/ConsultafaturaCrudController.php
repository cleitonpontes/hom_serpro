<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use App\Models\Contratofatura;
use App\Models\Fornecedor;
use App\Models\Justificativafatura;
use App\Models\Tipolistafatura;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ConsultafaturaCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 * @author Anderson Sathler <asathler@gmail.com>
 */
class ConsultafaturaCrudController extends CrudController
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
        $this->crud->setModel('App\Models\Contratofatura');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/consulta/faturas');
        $this->crud->setEntityNameStrings('Fatura', 'Faturas');
        $this->crud->setHeading('Consulta Faturas por Contrato');
        $this->crud->enableExportButtons();

        $this->crud->allowAccess('show');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');

        (backpack_user()->can('contratofatura_editar')) ? $this->crud->allowAccess('update') : null;
        // $this->crud->removeAllButtons();

        $this->crud->addClause('join', 'contratos',
            'contratos.id', '=', 'contratofaturas.contrato_id'
        );
        $this->crud->addClause('join', 'fornecedores',
            'fornecedores.id', '=', 'contratos.fornecedor_id'
        );
        $this->crud->addClause('join', 'unidades',
            'unidades.id', '=', 'contratos.unidade_id'
        );
        $this->crud->addClause('join', 'tipolistafatura',
            'tipolistafatura.id', '=', 'contratofaturas.tipolistafatura_id'
        );
        $this->crud->addClause('join', 'justificativafatura',
            'justificativafatura.id', '=', 'contratofaturas.justificativafatura_id'
        );
        $this->crud->addClause('select', [
            'contratofaturas.*'
        ]);

        // Apenas ocorrências da unidade atual
        $this->crud->addClause('where', 'unidades.codigo', '=', session('user_ug'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $fornecedorId = 0;
        $fornecedorDesc = '';
        $faturaId = \Route::current()->parameter('fatura');

        if ($faturaId) {
            $fatura = Contratofatura::find($faturaId);

            $fornecedorId = $fatura->contrato->fornecedor_id;
            $fornecedorDesc = $fatura->getFornecedor();
        }

        $this->crud->addColumns($this->retornaColunas());
        $this->crud->addFields($this->retornaCampos($fornecedorId, $fornecedorDesc));
        $this->adicionaFiltros();
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
            'tipolistafatura_id',
            'justificativafatura_id',
            'valor',
            'juros',
            'multa',
            'glosa',
            'valorliquido',
            'situacao'
        ]);

        return $content;
    }

    public function update(Request $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    /**
     * Retorna colunas a serem exibidas bem como suas definições
     *
     * @return array[]
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function retornaColunas()
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
    public function retornaCampos($fornecedorId = 0, $fornecedorDesc = '')
    {
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
            'label' => "Fornecedor",
            'type' => 'text',
            'value' => $fornecedorDesc,
            'attributes' => [
                'readonly'=>'readonly',
                'style' => 'pointer-events: none;touch-action: none;',
            ]
        ];

        $campos[] = [
            'name' => 'situacao',
            'label' => "Situação",
            'type' => 'select_from_array',
            'options' => config('app.situacao_fatura'),
            'default'    => 'PEN',
            'allows_null' => false
        ];

        $campos[] = [
            'name' => 'empenhos',
            'label' => "Empenhos",
            'type' => 'select2_multiple',
            'model' => "App\Models\Empenho",
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
    public function adicionaFiltros()
    {
        $this->adicionaFiltroNumeroFatura();
        $this->adicionaFiltroNumeroContrato();
        $this->adicionaFiltroFornecedor();
        $this->adicionaFiltroTipoLista();
        $this->adicionaFiltroJustificativa();
        $this->adicionaFiltroDataEmissao();
        $this->adicionaFiltroDataAteste();
        $this->adicionaFiltroDataVencimento();
        $this->adicionaFiltroDataPrazoPagamento();
        $this->adicionaFiltroDataProtocolo();
        $this->adicionaFiltroSituacao();
    }

    /**
     * Adiciona o filtro ao campo Número da Fatura
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaFiltroNumeroFatura()
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
     * Adiciona o filtro ao campo Número do Contrato
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaFiltroNumeroContrato()
    {
        $campo = [
            'name' => 'contrato',
            'type' => 'select2',
            'label' => 'Núm. Contrato'
        ];

        $contratos = $this->retornaContratos();

        $this->crud->addFilter(
            $campo,
            $contratos,
            function ($value) {
                $this->crud->addClause('where', 'contratos.numero', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Fornecedor
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaFiltroFornecedor()
    {
        $campo = [
            'name' => 'cpf_cnpj',
            'type' => 'select2',
            'label' => 'Fornecedor'
        ];

        $fornecedores = $this->retornaFornecedores();

        $this->crud->addFilter(
            $campo,
            $fornecedores,
            function ($value) {
                $this->crud->addClause('where', 'fornecedores.cpf_cnpj_idgener', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Tipo de Lista
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaFiltroTipoLista()
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
    public function adicionaFiltroJustificativa()
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
    public function adicionaFiltroDataEmissao()
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
    public function adicionaFiltroDataAteste()
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
    public function adicionaFiltroDataVencimento()
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
    public function adicionaFiltroDataPrazoPagamento()
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
    public function adicionaFiltroDataProtocolo()
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
    public function adicionaFiltroSituacao()
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

    /**
     * Retorna dados de Fornecedores para exibição no controle de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
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
        $dados = Justificativafatura::select('nome as descricao', 'id');

        return $dados->pluck('descricao', 'id')->toArray();
    }

}
