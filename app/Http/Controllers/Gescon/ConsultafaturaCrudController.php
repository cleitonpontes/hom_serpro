<?php

namespace App\Http\Controllers\Gescon;

use App\Models\BackpackUser;
use App\Models\Contrato;
use App\Models\Fornecedor;
/*
use App\Models\Codigo;
use App\Models\Codigoitem;
*/
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
        // $this->crud->setModel('App\Models\Contratoocorrencia');
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

        $this->crud->addClause('join', 'contratos', 'contratos.id', '=', 'contratofaturas.contrato_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
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
                'contratoocorrencias.*'
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
            'user_id',
            'situacao',
            'novasituacao',
            'numero'
        ]);

        return $content;
    }

    /**
     * Retorna colunas a serem exibidas bem como suas definições
     *
     * @return array[]
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function retornaColunas()
    {
        $colunas = [
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

            // Fatura
            [
                'name' => '',
                'label' => '',
                'type' => ''
            ],

            /*
            [
                'name' => 'getNumero',
                'label' => 'Núm. Ocorrência',
                'type' => 'model_function',
                'function_name' => 'getNumero',
                'priority' => 9,
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
                'name' => 'data',
                'label' => 'Data',
                'type' => 'date',
                'format' => 'd/m/Y',
                'priority' => 11,
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
                'name' => 'getUsuario',
                'label' => 'Usuário',
                'type' => 'model_function',
                'function_name' => 'getUsuario',
                'priority' => 12,
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('users.cpf', 'like', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('users.name', 'like', "%" . strtoupper($searchTerm) . "%");
                }
            ],
            [
                'name' => 'ocorrencia',
                'label' => 'Descrição',
                'limit' => 50000,
                'priority' => 10,
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
                'name' => 'notificapreposto',
                'label' => 'Notifica Preposto',
                'type' => 'boolean',
                'options' => [
                    0 => 'Não',
                    1 => 'Sim'
                ],
                'priority' => 13,
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
                'name' => 'emailpreposto',
                'label' => 'E-mail Preposto',
                'type' => 'email',
                'limit' => 10000,
                'priority' => 14,
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
                'name' => 'numeroocorrencia',
                'label' => 'Ocorrência Alterada',
                'type' => 'number',
                'priority' => 15,
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
                'name' => 'getSituacaoConsulta',
                'label' => 'Situação',
                'type' => 'model_function',
                'function_name' => 'getSituacaoConsulta',
                'priority' => 16,
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
                'name' => 'getSituacaoNovaConsulta',
                'label' => 'Nova Situação',
                'type' => 'model_function',
                'function_name' => 'getSituacaoNovaConsulta',
                'priority' => 17,
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
                'name' => 'arquivos',
                'label' => 'Arquivos',
                'type' => 'arquivos_ico',
                'disk' => 'local',
                'priority' => 18,
                'orderable' => false,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
                'name' => 'contrato.unidade.codigo',
                'label' => 'UG',
                'priority' => 99,
                'orderable' => false,
                'visibleInTable' => false,
                'visibleInModal' => false,
                'visibleInExport' => true,
                'visibleInShow' => false
            ],
            [
                'name' => 'id',
                'label' => '#',
                'type' => 'number',
                'priority' => 100,
                'orderable' => false,
                'visibleInTable' => false,
                'visibleInModal' => false,
                'visibleInExport' => false,
                'visibleInShow' => false
            ]
            */
        ];

        return $colunas;
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
