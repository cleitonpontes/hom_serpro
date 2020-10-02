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
            'funcao_id',
            'salario',
            'custo',
            'escolaridade_id',
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
        $this->adicionaColunaCpf();
        $this->adicionaColunaNome();
        $this->adicionaColunaFuncao();
        $this->adicionaColunaDescricaoComplementar();
        $this->adicionaColunaJornada();
        $this->adicionaColunaUnidade();
        $this->adicionaColunaSalario();
        $this->adicionaColunaCusto();
        $this->adicionaColunaValeAlimentacao();
        $this->adicionaColunaAuxTransporte();
        $this->adicionaColunaEscolaridade();
    }

    /**
     * Adiciona filtros específicos a serem apresentados
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function aplicaFiltrosEspecificos()
    {
        $this->aplicaFiltroCpf();
        $this->aplicaFiltroNome();
        $this->aplicaFiltroFuncao();
        $this->aplicaFiltroSalario();
        $this->aplicaFiltroValeAlimentacao();
        $this->aplicaFiltroAuxilioTransporte();
        $this->aplicaFiltroEscolaridade();
    }

    /**
     * Adiciona o campo CPF na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaColunaCpf()
    {
        $this->crud->addColumn([
            'name' => 'cpf',
            'label' => 'CPF',
            'type' => 'string',
            'priority' => 10,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Nome na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaColunaNome()
    {
        $this->crud->addColumn([
            'name' => 'nome',
            'label' => 'Nome',
            'type' => 'string',
            'priority' => 11,
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
    public function adicionaColunaFuncao()
    {
        $this->crud->addColumn([
            'name' => 'getFuncao',
            'label' => 'Função',
            'type' => 'model_function',
            'function_name' => 'getFuncao',
            'priority' => 12,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Descrição Complementar na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaColunaDescricaoComplementar()
    {
        $this->crud->addColumn([
            'name' => 'descricao_complementar',
            'label' => 'Desc. Complementar',
            'type' => 'string',
            'priority' => 13,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Jornada na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaColunaJornada()
    {
        $this->crud->addColumn([
            'name' => 'jornada',
            'label' => 'Jornada',
            'type' => 'string',
            'priority' => 14,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Unidade na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaColunaUnidade()
    {
        $this->crud->addColumn([
            'name' => 'unidade',
            'label' => 'Unidade',
            'type' => 'string',
            'priority' => 15,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Salário na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaColunaSalario()
    {
        $this->crud->addColumn([
            'name' => 'formatVlrSalario',
            'label' => 'Salário',
            'type' => 'model_function',
            'function_name' => 'formatVlrSalario',
            'priority' => 16,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Custo na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaColunaCusto()
    {
        $this->crud->addColumn([
            'name' => 'formatVlrCusto',
            'label' => 'Custo',
            'type' => 'model_function',
            'function_name' => 'formatVlrCusto',
            'priority' => 17,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Escolaridade na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaColunaEscolaridade()
    {
        $this->crud->addColumn([
            'name' => 'getEscolaridade',
            'label' => 'Escolaridade',
            'type' => 'model_function',
            'function_name' => 'getEscolaridade',
            'priority' => 18,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    public function adicionaColunaValeAlimentacao()
    {
        $this->crud->addColumn([
            'name' => 'formatValeAlimentacao',
            'label' => 'Vale Alimentação',
            'type' => 'model_function',
            'function_name' => 'formatValeAlimentacao',
            'priority' => 19,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    public function adicionaColunaAuxTransporte()
    {
        $this->crud->addColumn([
            'name' => 'formatAuxTransporte',
            'label' => 'Auxilio Transporte',
            'type' => 'model_function',
            'function_name' => 'formatAuxTransporte',
            'priority' => 20,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o filtro ao campo CPF
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroCpf()
    {
        $campo = [
            'name' => 'cpf',
            'type' => 'text',
            'label' => 'CPF'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $this->crud->addClause('where',
                    DB::raw("replace(replace(contratoterceirizados.cpf, '.', ''), '-', '')"), 'ilike',
                    '%' . str_replace('.', '', str_replace('-', '', $value)) . '%'
                );
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Nome
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroNome()
    {
        $campo = [
            'name' => 'nome',
            'type' => 'text',
            'label' => 'Nome'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $this->crud->addClause('where',
                    'contratoterceirizados.nome', 'ilike',
                    '%' . $value . '%'
                );
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
                $this->crud->addClause('where', 'contratoterceirizados.funcao_id', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Salário
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroSalario()
    {
        $campo = [
            'name' => 'salario',
            'type' => 'range',
            'label' => 'Salário'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $range = json_decode($value);

                if ($range->from) {
                    $this->crud->addClause('where',
                        'contratoterceirizados.salario', '>=', (float)$range->from
                    );
                }

                if ($range->to) {
                    $this->crud->addClause('where',
                        'contratoterceirizados.salario', '<=', (float)$range->to
                    );
                }
            }
        );
    }

    private function aplicaFiltroAuxilioTransporte()
    {
        $campo = [
            'name' => 'aux_transporte',
            'type' => 'range',
            'label' => 'Auxílio Transporte'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $range = json_decode($value);

                if ($range->from) {
                    $this->crud->addClause('where',
                        'contratoterceirizados.aux_transporte', '>=', (float)$range->from
                    );
                }

                if ($range->to) {
                    $this->crud->addClause('where',
                        'contratoterceirizados.aux_transporte', '<=', (float)$range->to
                    );
                }
            }
        );
    }

    private function aplicaFiltroValeAlimentacao()
    {
        $campo = [
            'name' => 'vale_alimentacao',
            'type' => 'range',
            'label' => 'Vale Alimentação'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $range = json_decode($value);

                if ($range->from) {
                    $this->crud->addClause('where',
                        'contratoterceirizados.vale_alimentacao', '>=', (float)$range->from
                    );
                }

                if ($range->to) {
                    $this->crud->addClause('where',
                        'contratoterceirizados.vale_alimentacao', '<=', (float)$range->to
                    );
                }
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Escolaridade
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroEscolaridade()
    {
        $campo = [
            'name' => 'escolaridade',
            'type' => 'select2',
            'label' => 'Escolaridade'
        ];

        $funcoes = $this->retornaEscolaridadesParaCombo();

        $this->crud->addFilter(
            $campo,
            $funcoes,
            function ($value) {
                $this->crud->addClause('where', 'contratoterceirizados.escolaridade_id', $value);
            }
        );
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

        $dados->where('codigo_id', Codigo::MAO_DE_OBRA);
        $dados->orderBy('descricao');

        return $dados->pluck('descricao', 'id')->toArray();
    }

    /**
     * Retorna array de Escolaridades para combo de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaEscolaridadesParaCombo()
    {
        $dados = Codigoitem::select('descricao', 'id');

        $dados->where('codigo_id', Codigo::ESCOLARIDADE);
        $dados->orderBy('descricao');

        return $dados->pluck('descricao', 'id')->toArray();
    }

}
