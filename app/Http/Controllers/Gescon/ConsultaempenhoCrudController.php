<?php

namespace App\Http\Controllers\Gescon;

use App\Models\BackpackUser;
use App\Models\Codigo;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Contratocronograma;
use App\Models\Empenhos;
use App\Models\Fornecedor;
use App\Models\Naturezadespesa;
use App\Models\Planointerno;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ConsultaempenhoCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 * @author Saulo Soares <saulosao@gmail.com>
 */
class ConsultaempenhoCrudController extends CrudController
{
    /**
     * Configurações iniciais do Backpack
     *
     * @throws \Exception
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setModel('App\Models\Contratoempenho');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/consulta/empenhos');
        $this->crud->setEntityNameStrings('Empenho', 'Empenhos');
        $this->crud->setHeading('Consulta Empenhos por Contrato');
        $this->crud->enableExportButtons();

        $this->crud->allowAccess('show');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        // $this->crud->removeAllButtons();

        $this->crud->addClause('select', 'contratoempenhos.*');
        $this->crud->addClause('join', 'contratos', 'contratos.id', '=', 'contratoempenhos.contrato_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join'
            , DB::raw ('fornecedores as fornecedores_empenhos')
            , 'fornecedores_empenhos.id', '=', 'contratoempenhos.fornecedor_id');
        $this->crud->addClause('join', 'empenhos', 'empenhos.id', '=', 'contratoempenhos.empenho_id');
        $this->crud->addClause('join', 'planointerno', 'planointerno.id', '=', 'empenhos.planointerno_id');
        $this->crud->addClause('join', 'naturezadespesa', 'naturezadespesa.id', '=', 'empenhos.naturezadespesa_id');

        // Apenas ocorrências da unidade atual
        $this->crud->addClause('where', 'unidades.codigo', '=', session('user_ug'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns($this->retornaColunas());
        $this->adicionaFiltros();
    }

    /**
     * Action para exibição de um único registro
     *
     * @param int $id
     * @return \Backpack\CRUD\app\Http\Controllers\Operations\Response
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'contrato_id',
            'user_id',
            'situacao',
            'novasituacao'
        ]);

        return $content;
    }

    /**
     * Retorna colunas a serem exibidas bem como suas definições
     *
     * @return array[]
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function retornaColunas()
    {
        $colunas = [
            [
                'name' => 'contrato.unidade.codigo',
                'label' => 'UG',
                'priority' => 99,
                'orderable' => false,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => false
            ],
            [
                'name' => 'contrato.numero',
                'label' => 'Número Contrato',
                'type' => 'string',
                'priority' => 1,
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
                'name' => 'getFornecedor',
                'label' => 'Fornecedor',
                'type' => 'model_function',
                'function_name' => 'getFornecedorContrato',
                'priority' => 2,
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
                }
            ],
            [
                'name' => 'contrato.objeto',
                'label' => 'Objeto',
                'limit' => 150,
                'priority' => 3,
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
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
            ],
            [
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
            ],
            [
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
            ],
            [
                'name' => 'contrato.num_parcelas',
                'label' => 'Núm. Parcelas',
                'priority' => 8,
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
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
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('empenhos.numero', 'like', "%$searchTerm%");
                },
            ],
            [
                'name' => 'empenho.numero',
                'label' => 'Número Empenho',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getFornecedorEmpenho',
                'label' => 'Fornecedor Empenho',
                'type' => 'model_function',
                'function_name' => 'getFornecedorEmpenho',
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%$searchTerm%");
                    $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'getPi',
                'label' => 'Plano Interno',
                'type' => 'model_function',
                'function_name' => 'getPi',
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('planointerno.codigo', 'like', "%" . strtoupper($searchTerm) . "%");
//                    $query->orWhere('planointerno.descricao', 'like', "%" . strtoupper($searchTerm) . "%");
//                },
            ],
            [
                'name' => 'getNatureza',
                'label' => 'Natureza Despesa',
                'type' => 'model_function',
                'function_name' => 'getNatureza',
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('naturezadespesa.codigo', 'like', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('naturezadespesa.descricao', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],

            [
                'name' => 'formatVlrEmpenhado',
                'label' => 'Empenhado',
                'type' => 'model_function',
                'function_name' => 'formatVlrEmpenhado',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatVlraLiquidar',
                'label' => 'a Liquidar',
                'type' => 'model_function',
                'function_name' => 'formatVlraLiquidar',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatVlrLiquidado',
                'label' => 'Liquidado',
                'type' => 'model_function',
                'function_name' => 'formatVlrLiquidado',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatVlrPago',
                'label' => 'Pago',
                'type' => 'model_function',
                'function_name' => 'formatVlrPago',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatVlrRpInscrito',
                'label' => 'RP Inscrito',
                'type' => 'model_function',
                'function_name' => 'formatVlrRpInscrito',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatVlrRpaLiquidar',
                'label' => 'RP a Liquidar',
                'type' => 'model_function',
                'function_name' => 'formatVlrRpaLiquidar',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatVlrRpLiquidado',
                'label' => 'RP Liquidado',
                'type' => 'model_function',
                'function_name' => 'formatVlrRpLiquidado',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatVlrRpPago',
                'label' => 'RP Pago',
                'type' => 'model_function',
                'function_name' => 'formatVlrRpPago',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
        ];

        return $colunas;
    }

    /**
     * Adiciona todos os filtros desejados para esta funcionalidade
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaFiltros()
    {
        $this->adicionaFiltroNumeroContrato();
        $this->adicionaFiltroFornecedor();
        $this->adicionaFiltroFornecedorEmpenho();
        $this->adicionaFiltroNumeroEmpenho();
        $this->adicionaFiltroPlanoInterno();
        $this->adicionaFiltroNaturezaDespesa();

    }

    /**
     * Adiciona o filtro ao campo Número do Contrato
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaFiltroNumeroContrato()
    {
        $campo = [
            'name' => 'contrato',
            'type' => 'select2_multiple',
            'label' => 'Núm. Contrato'
        ];

        $contratos = $this->retornaContratos();

        $this->crud->addFilter(
            $campo,
            $contratos,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'contratos.numero', json_decode($value));
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Número do Contrato
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaFiltroNumeroEmpenho()
    {
        $campo = [
            'name' => 'numEmpenho',
            'type' => 'select2_multiple',
            'label' => 'Núm. Empenho'
        ];

        $empenhos = $this->retornaEmpenhos();

        $this->crud->addFilter(
            $campo,
            $empenhos,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'empenhos.numero', json_decode($value));
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Fornecedor
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaFiltroFornecedor()
    {
        $campo = [
            'name' => 'fornecedor',
            'type' => 'select2_multiple',
            'label' => 'Fornecedor'
        ];

        $fornecedores = $this->retornaFornecedores();

        $this->crud->addFilter(
            $campo,
            $fornecedores,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'fornecedores.cpf_cnpj_idgener', json_decode($value));
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Fornecedor do Empenho
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaFiltroFornecedorEmpenho()
    {
        $campo = [
            'name' => 'fornecedor_contrato',
            'type' => 'select2_multiple',
            'label' => 'Fornecedor Empenho'
        ];

        $fornecedores = $this->retornaFornecedoresEmpenhos();

        $this->crud->addFilter(
            $campo,
            $fornecedores,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'fornecedores_empenhos.cpf_cnpj_idgener'
                    , json_decode($value));
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Plano Interno
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaFiltroPlanoInterno()
    {
        $campo = [
            'name' => 'plano_interno',
            'type' => 'select2_multiple',
            'label' => 'Plano Interno'
        ];

        $planos = $this->retornaPlanos();

        $this->crud->addFilter(
            $campo,
            $planos,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'planointerno.codigo', json_decode($value));
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Natureza Despesa
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaFiltroNaturezaDespesa()
    {
        $campo = [
            'name' => 'natureza_despesa',
            'type' => 'select2_multiple',
            'label' => 'Natureza Despesa'
        ];

        $naturezas = $this->retornaNaturezas();

        $this->crud->addFilter(
            $campo,
            $naturezas,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'naturezadespesa.codigo', json_decode($value));
            }
        );
    }


    /**
     * Retorna dados dos Contratos para exibição no controle de filtro
     *
     * @return array
     * @author Saulo Soares <saulosao@gmail.com>
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
        $dados->orderBy('id');

        return $dados->pluck('descricao', 'numero')->toArray();
    }

    /**
     * Retorna dados dos Empenhos para exibição no controle de filtro
     *
     * @return array
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function retornaEmpenhos()
    {
        $dados = Empenhos::select('numero');
        $dados->where('unidade_id', session('user_ug_id'));

        $dados->orderBy('id');

        return $dados->pluck( 'numero','numero')->toArray();
    }

    /**
     * Retorna dados de Fornecedores para exibição no controle de filtro
     *
     * @return array
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function retornaFornecedores()
    {
        $dados = Fornecedor::select(
            DB::raw("CONCAT(cpf_cnpj_idgener, ' - ', nome) AS descricao"), 'cpf_cnpj_idgener'
        );

        $dados->whereHas('contratos', function ($c) {
            $c->where('situacao', true);
            $c->where('unidade_id', session('user_ug_id'));
        });

        return $dados->pluck('descricao', 'cpf_cnpj_idgener')->toArray();
    }

    /**
     * Retorna dados de Fornecedores com empenhos para exibição no controle de filtro
     *
     * @return array
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function retornaFornecedoresEmpenhos()
    {
        $dados = Fornecedor::select(
            DB::raw("CONCAT(cpf_cnpj_idgener, ' - ', nome) AS descricao"), 'cpf_cnpj_idgener'
        );

        $dados->has('empenhos');
       $dados->whereHas('contratos', function ($c) {
            $c->where('situacao', true);
            $c->where('unidade_id', session('user_ug_id'));
        });

        return $dados->pluck('descricao', 'cpf_cnpj_idgener')->toArray();
    }

    /**
     * Retorna dados de Planos Internos para exibição no controle de filtro
     *
     * @return array
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function retornaPlanos()
    {
        $dados = Planointerno::select(
            DB::raw("CONCAT(codigo, ' - ', descricao) AS descricao"), 'codigo'
        );

        $dados->whereHas('empenhos', function ($c) {
            $c->where('situacao', true);
            $c->where('unidade_id', session('user_ug_id'));
        });

        return $dados->pluck('descricao', 'codigo')->toArray();
    }

    /**
     * Retorna dados de Natureza Despesas para exibição no controle de filtro
     *
     * @return array
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function retornaNaturezas()
    {
        $dados = Naturezadespesa::select(
            DB::raw("CONCAT(codigo, ' - ', descricao) AS descricao"), 'codigo'
        );

        $dados->whereHas('empenhos', function ($c) {
            $c->where('situacao', true);
            $c->where('unidade_id', session('user_ug_id'));
        });

        return $dados->pluck('descricao', 'codigo')->toArray();
    }

}
