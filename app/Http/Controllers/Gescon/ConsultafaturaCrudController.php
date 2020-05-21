<?php

namespace App\Http\Controllers\Gescon;

use App\Http\Requests\ContratofaturaRequest as UpdateRequest;
use App\Models\BackpackUser;
use App\Models\Contrato;
use App\Models\Fornecedor;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
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
        // $this->crud->denyAccess('update');
        $this->crud->allowAccess('update');
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

        /*
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join', 'users', 'users.id', '=', 'contratofatura.user_id');
        $this->crud->addClause('join', 'codigoitens', 'codigoitens.id', '=', 'contratoocorrencias.situacao');
        $this->crud->addClause('leftJoin', 'codigoitens as codigoitensnova', 'codigoitensnova.id', '=', 'contratoocorrencias.novasituacao');
        $this->crud->addClause('select',
            [
                'contratos.id',
                'contratos.numero',
                'contratos.fornecedor_id',
                'contratos.objeto',
                'contratos.num_parcelas',
                'contratos.vigencia_inicio',
                'contratos.vigencia_fim',
                'contratos.valor_global',
                'contratos.valor_parcela',
                'fornecedores.cpf_cnpj_idgener',
                'fornecedores.nome',
                'users.cpf',
                'users.name',
                'unidades.codigo',
                'codigoitens.id',
                'codigoitens.descricao',
                'contratofaturas.*'
            ]
        );
        */

        // Apenas ocorrências da unidade atual
        $this->crud->addClause('where', 'unidades.codigo', '=', session('user_ug'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns($this->retornaColunas());
        $this->crud->addFields($this->retornaCampos());
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
        ]);

        return $content;
    }

    public function update(UpdateRequest $request)
    {
        // dd($request);
        $this->crud->removeFields([
            'contrato_id',
            'tipolistafatura_id'
        ]);

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
            'name' => 'contrato.id',
            'label' => 'C Id',
            'type' => 'string',
            'priority' => 0,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];
        $colunas[] = [
            'name' => 'contrato.fornecedor.id',
            'label' => 'Fornecedor',
            'type' => 'string',
            'priority' => 0,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];




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
            'name' => 'situacao',
            'label' => 'Situação',
            'type' => 'string',
            'priority' => 27,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];

        return $colunas;
    }

    /**
     * Retorna array dos campos para exibição no form
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function retornaCampos()
    {
        $campos = array();

        // $con = Contrato::find($contrato_id);

        $campos[] = [
            'name' => 'situacao',
            'label' => "Situação",
            'type' => 'select_from_array',
            'options' => config('app.situacao_fatura'),
            'default'    => 'PEN',
            /*
            'attributes' => [
                'readonly'=>'readonly',
                'style' => 'pointer-events: none;touch-action: none;'
            ],
            */
            'allows_null' => false
        ];

        /*
        $campos[] = [
            'label' => "Empenhos",
            'type' => 'select2_multiple',
            'name' => 'empenhos',
            'entity' => 'empenhos',
            'attribute' => 'numero',
            'attribute2' => 'aliquidar',
            'attribute_separator' => ' - Valor a Liquidar: R$ ',
            'model' => "App\Models\Empenho",
            'pivot' => true,
            'options' => (function ($query) use ($con) {
                return $query->orderBy('numero', 'ASC')
                    ->where('unidade_id', session()->get('user_ug_id'))
                    ->where('fornecedor_id', $con->fornecedor_id)
                    ->get();
            })
        ];
        */

        return $campos;
    }

    /**
     * Adiciona todos os filtros desejados para esta funcionalidade
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaFiltros()
    {
        /*
        $this->adicionaFiltroNumeroOcorrencia();
        $this->adicionaFiltroNumeroContrato();
        $this->adicionaFiltroFornecedor();
        $this->adicionaFiltroUsuario();
        $this->adicionaFiltroVigenciaInicio();
        $this->adicionaFiltroVigenciaFim();
        // $this->adicionaFiltroSituacao();
        */
    }





























    /**
     * Adiciona o filtro ao campo Número da Ocorrência
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaFiltroNumeroOcorrencia()
    {
        $campo = [
            'name' => 'numero',
            'type' => 'text',
            'label' => 'Núm. Ocorrência'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $this->crud->addClause('where', 'contratoocorrencias.numero', $value);
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
     * Adiciona o filtro ao campo Usuário
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaFiltroUsuario()
    {
        $campo = [
            'name' => 'usuario',
            'type' => 'select2',
            'label' => 'Usuário'
        ];

        $usuarios = $this->retornaUsuarios();

        $this->crud->addFilter(
            $campo,
            $usuarios,
            function ($value) {
                $this->crud->addClause('where', 'users.cpf', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Data de Início da Vigência
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaFiltroVigenciaInicio()
    {
        $campo = [
            'name' => 'vig_ini',
            'type' => 'date_range',
            'label' => 'Vig. Início'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratos.vigencia_inicio', '>=', $dates->from . ' 00:00:00');
                $this->crud->addClause('where', 'contratos.vigencia_inicio', '<=', $dates->to . ' 23:59:59');
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Data de Fim da Vigência
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaFiltroVigenciaFim()
    {
        $campo = [
            'name' => 'vig_fim',
            'type' => 'date_range',
            'label' => 'Vig. Fim'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratos.vigencia_fim', '>=', $dates->from . ' 00:00:00');
                $this->crud->addClause('where', 'contratos.vigencia_fim', '<=', $dates->to . ' 23:59:59');
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

        $situacoes = $this->retornaSituacoes();

        $this->crud->addFilter(
            $campo,
            $situacoes,
            function ($value) {
                $this->crud->addClause('where', 'contratoocorrencias.situacao', $value);
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
     * Retorna dados de Usuários para exibição no controle de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaUsuarios()
    {
        $dados = BackpackUser::select(
            DB::raw("CONCAT(cpf, ' - ', name) AS descricao"), 'cpf'
        );

        $dados->join('contratoocorrencias as o', 'o.user_id', '=', 'users.id');

        return $dados->pluck('descricao', 'cpf')->toArray();
    }

    /**
     * Retorna dados de Situações para exibição no controle de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaSituacoes()
    {
        $dados = Codigoitem::select('descricao', 'id');

        // $dados->where('codigo_id', Codigo::CODIGO_SITUACAO_OCORRENCIA);
        $dados->orderBy('descricao');

        return $dados->pluck('descricao', 'id')->toArray();
    }

}
