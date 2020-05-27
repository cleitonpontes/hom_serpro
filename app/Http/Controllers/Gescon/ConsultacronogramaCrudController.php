<?php

namespace App\Http\Controllers\Gescon;

use App\Models\BackpackUser;
use App\Models\Codigo;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Contratocronograma;
use App\Models\Fornecedor;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ConsultacronogramaCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 * @author Saulo Soares <saulosao@gmail.com>
 */
class ConsultacronogramaCrudController extends CrudController
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

        $this->crud->setModel('App\Models\Contratocronograma');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/consulta/cronogramas');
        $this->crud->setEntityNameStrings('Cronograma', 'Cronogramas');
        $this->crud->setHeading('Consulta Cronogramas por Contrato');
        $this->crud->enableExportButtons();

        $this->crud->allowAccess('show');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        // $this->crud->removeAllButtons();

        $this->crud->addClause('join', 'contratos', 'contratos.id', '=', 'contratocronograma.contrato_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');

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
                'function_name' => 'getFornecedor',
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
                'visibleInShow' => true
            ],
            [
                'name' => 'getContratoHistorico',
                'label' => 'Instrumento - Número', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContratoHistorico', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getReceitaDespesa',
                'label' => 'Receita / Despesa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getReceitaDespesa', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'mesref',
                'label' => 'Mês Referência', // Table column heading
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'anoref',
                'label' => 'Ano Referência', // Table column heading
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'vencimento',
                'label' => 'Vencimento',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatVlr',
                'label' => 'Valor', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlr', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'retroativo',
                'label' => 'Retroativo',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // optionally override the Yes/No texts
                'options' => [0 => 'Não', 1 => 'Sim']
            ],
            [ // select_from_array
                'name' => 'soma_subtrai',
                'label' => "Soma ou Subtrai?",
                'type' => 'radio',
                'options' => [1 => 'Soma', 0 => 'Subtrai'],
                'default'    => 1,
                'inline'      => true,
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
        $this->adicionaFiltroMesReferencia();
        $this->adicionaFiltroAnoReferencia();
        $this->adicionaFiltroVencimento();

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
     * @author Saulo Soares <saulosao@gmail.com>
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
        $dados->orderBy('id'); // 'data_publicacao'

        return $dados->pluck('descricao', 'numero')->toArray();
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
        });

        return $dados->pluck('descricao', 'cpf_cnpj_idgener')->toArray();
    }

    /**
     * Adiciona o filtro ao campo Mes de Referencia
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaFiltroMesReferencia()
    {

        $campo = [
            'name' => 'mesref',
            'type' => 'select2_multiple',
            'label' => 'Mês Ref'
        ];

        $this->crud->addFilter(
            $campo,
            config('app.meses_referencia_fatura'),
            function ($months) {
                $this->crud->addClause('whereIn'
                    , 'contratocronograma.mesref', json_decode($months));
            }
        );

    }

    /**
     * Adiciona o filtro ao campo Ano de Referencia
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaFiltroAnoReferencia()
    {

        $campo = [
            'name' => 'anoref',
            'type' => 'select2_multiple',
            'label' => 'Ano Ref'
        ];

        $this->crud->addFilter(
            $campo,
            config('app.anos_referencia_fatura'),
            function ($years) {

                $this->crud->addClause('whereIn'
                    , 'contratocronograma.anoref', json_decode($years));
            }
        );

    }

    /**
     * Adiciona o filtro ao campo Vencimento
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaFiltroVencimento()
    {

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'vencimento',
            'label' => 'Vencimento'
        ],
            false,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where'
                    , 'contratocronograma.vencimento', '>=', $dates->from);
                $this->crud->addClause('where'
                    , 'contratocronograma.vencimento', '<=', $dates->to . ' 23:59:59');
            });
    }
}
