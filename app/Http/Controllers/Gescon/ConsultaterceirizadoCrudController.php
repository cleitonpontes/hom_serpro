<?php

namespace App\Http\Controllers\Gescon;

use App\Models\BackpackUser;
use App\Models\Codigo;
use App\Models\Codigoitem;
use App\Models\Contratoterceirizado;
use App\Models\Instalacao;
use Illuminate\Support\Facades\DB;

/**
 * Class ConsultaterceirizadoCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 * @author Anderson Sathler <asathler@gmail.com>
 */
class ConsultaterceirizadoCrudController extends ConsultaContratoBaseCrudController
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

        $this->crud->setModel('App\Models\Contratoterceirizado');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/consulta/terceirizados');
        $this->crud->setEntityNameStrings('Terceirizado', 'Terceirizados');
        $this->crud->setHeading('Consulta Terceirizados por Contrato');

        $this->crud->addClause('leftJoin', 'contratos',
            'contratos.id', '=', 'contratoterceirizados.contrato_id'
        );
        $this->crud->addClause('leftJoin', 'fornecedores',
            'fornecedores.id', '=', 'contratos.fornecedor_id'
        );
        $this->crud->addClause('select', [
            'contratos.*',
            'fornecedores.*',
            // Tabela principal deve ser sempre a última da listagem!
            'contratoterceirizados.*'
        ]);

        // Apenas ocorrências da unidade atual
        $this->crud->addClause('where', 'contratos.unidade_id', '=', session('user_ug_id'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->defineConfiguracaoPadrao();
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
            /*
            'user_id',
            'funcao_id',
            'instalacao_id',
            'data_inicio',
            'data_fim'
            */
        ]);

        return $content;
    }

    /**
     * Adiciona filtros específicos a serem apresentados
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function aplicaFiltrosEspecificos()
    {
        /*
        $this->aplicaFiltroUsuario();
        $this->aplicaFiltroFuncao();
        $this->aplicaFiltroInstalacao();
        $this->aplicaFiltroPortaria();
        */
    }

    /**
     * Adiciona as colunas específicas a serem exibidas bem como suas definições
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaColunasEspecificasNaListagem()
    {
        /*
        $this->adicionaColunaUsuario();
        $this->adicionaColunaFuncao();
        $this->adicionaColunaInstalacao();
        $this->adicionaColunaPortaria();
        $this->adicionaColunaDataInicio();
        $this->adicionaColunaDataFim();
        $this->adicionaColunaSituacao();
        */
    }







































    /**
     * Adiciona o filtro ao campo Usuário
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroUsuario()
    {
        $campo = [
            'name' => 'usuario',
            'type' => 'select2',
            'label' => 'Usuário'
        ];

        $usuarios = $this->retornaUsuariosParaCombo();

        $this->crud->addFilter(
            $campo,
            $usuarios,
            function ($value) {
                $this->crud->addClause('where', 'contratoresponsaveis.user_id', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Função
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroFuncao()
    {
        $campo = [
            'name' => 'funcao',
            'type' => 'select2',
            'label' => 'Função'
        ];

        $funcoes = $this->retornaFuncoesParaCombo();

        $this->crud->addFilter(
            $campo,
            $funcoes,
            function ($value) {
                $this->crud->addClause('where', 'contratoresponsaveis.funcao_id', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Instalação
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroInstalacao()
    {
        $campo = [
            'name' => 'instalacao',
            'type' => 'select2',
            'label' => 'Instalação / Unidade'
        ];

        $instalacoes = $this->retornaInstalacaoesParaCombo();

        $this->crud->addFilter(
            $campo,
            $instalacoes,
            function ($value) {
                $this->crud->addClause('where', 'contratoresponsaveis.instalacao_id', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Portaria
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroPortaria()
    {
        $campo = [
            'name' => 'portaria',
            'type' => 'text',
            'label' => 'Portaria'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $this->crud->addClause('where',
                    'contratoresponsaveis.portaria', 'ilike',
                    '%' . $value . '%'
                );
            }
        );
    }

    /**
     * Adiciona o campo Usuário na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaUsuario()
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

    /**
     * Adiciona o campo Função na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
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

    /**
     * Adiciona o campo Instalação na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaInstalacao()
    {
        $this->crud->addColumn([
            'name' => 'getInstalacao',
            'label' => 'Instalação / Unidade',
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

    /**
     * Adiciona o campo Portaria na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
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

    /**
     * Adiciona o campo Data Início na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
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

    /**
     * Adiciona o campo Data Fim na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
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

    /**
     * Adiciona o campo Situação na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaSituacao()
    {
        $this->crud->addColumn([
            'name' => 'situacao',
            'label' => 'Situação',
            'type' => 'boolean',
            'options' => [
                0 => 'Inativo',
                1 => 'Ativo'
            ],
            'priority' => 16,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Retorna array de Usuários para combo de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaUsuariosParaCombo()
    {
        $dados = BackpackUser::select(
            DB::raw("CONCAT(cpf, ' - ', LEFT(name, 80)) as descricao"),
            'id'
        );

        $dados->where('ugprimaria', session()->get('user_ug_id'));
        $dados->where('situacao', true);
        $dados->orWhereHas('unidades', function ($query) {
            $query->where('unidade_id', session()->get('user_ug_id'));
        });
        $dados->orderBy('name');

        return $dados->pluck('descricao', 'id')->toArray();
    }

    /**
     * Retorna array de Funções para combo de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaFuncoesParaCombo()
    {
        $dados = Codigoitem::select('descricao', 'id');

        $dados->where('codigo_id', Codigo::CODIGO_FUNÇAO_CONTRATO);
        $dados->orderBy('descricao');

        return $dados->pluck('descricao', 'id')->toArray();
    }

    /**
     * Retorna array de Instalações para combo de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaInstalacaoesParaCombo()
    {
        $dados = Instalacao::select('nome as descricao', 'id');

        $dados->orderBy('nome');

        return $dados->pluck('descricao', 'id')->toArray();
    }

}
