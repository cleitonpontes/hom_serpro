<?php

namespace App\Http\Controllers\Transparencia;

use App\Models\Fornecedor;
use App\Models\Orgao;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;

/**
 * Classe ConsultaContratoBaseCrudController
 *
 * @package App\Http\Controllers\Transparencia
 * @property-read CrudPanel $crud
 * @author Saulo Soares <saulosao@gmail.com>
 */

abstract class ConsultaContratoBaseCrudController extends CrudController
{
    /**
     * Estabelece as definições iniciais da Consulta
     *
     * @example Dentro de setup, chamar o método abaixo $this->defineConfiguracaoPadrao()
     * @author Saulo Soares <saulosao@gmail.com>
     */
    final protected function defineConfiguracaoPadrao(): void
    {
        $this->definePrivilegios();
        $this->crud->enableExportButtons();
        $this->adicionaColunasNaListagem();
        $this->adicionaColunasEspecificasNaListagem();
        $this->aplicaFiltros();
        $this->aplicaFiltrosEspecificos();

        // As definições abaixo devem ser informadas na Controller de destino.
        // $this->crud->setModel('App\Models\Modelo');
        // $this->crud->setRoute(config('backpack.base.route_prefix') . 'caminho da rota');
        // $this->crud->setEntityNameStrings('Entidade', 'Entidades');
        // $this->crud->setHeading('Título do cabeçalho');

    }

    /**
     * Adiciona filtros específicos a serem apresentados
     * Método a ser sobrescrito na classe filha!
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    abstract protected function aplicaFiltrosEspecificos(): void;

    /**
     * Adiciona as colunas específicas a serem exibidas bem como suas definições
     * Método a ser sobrescrito na classe filha!
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    abstract protected function adicionaColunasEspecificasNaListagem(): void;

    /**
     * Nega os privilégios do CRUD
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    protected function definePrivilegios(): void
    {
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');
    }

    /**
     * Adiciona os filtros padrões das consultas
     * este método pode ser sobrescrito na classe filha
     * caso estes filtros não sejam necessários
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    protected function aplicaFiltros(): void
    {
        $this->aplicaFiltroOrgao();
        $this->aplicaFiltroUnidade();
        $this->aplicaFiltroFornecedor();
    }

    /**
     * Adiciona as colunas sobre contratos a serem exibidas bem como suas definições
     * este método pode ser sobrescrito na classe filha
     * caso estes campos não sejam necessários
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    protected function adicionaColunasNaListagem(): void
    {
        $this->adicionaColunaOrgao();
        $this->adicionaColunaUnidadeGestora();
    }

    /**
     * Adiciona o filtro ao campo Órgão
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function aplicaFiltroOrgao(): void
    {
        $campos = [
            'name' => 'orgao',
            'type' => 'select2_multiple',
            'label' => 'Órgão'
        ];

        $orgaos = Orgao::select(DB::raw("CONCAT(codigo,' - ',nome) AS nome"), 'codigo')
            ->whereHas('unidades', function ($u) {
                $u->whereHas('contratos', function ($c) {
                    $c->where('situacao', true);
                });
            })
            ->pluck('nome', "codigo")
            ->toArray();

        $this->crud->addFilter(
            $campos,
            $orgaos,
            function ($value) {
                $this->crud->addClause('whereIn',
                    'orgaos.codigo',
                    json_decode($value)
                );
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Unidade
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function aplicaFiltroUnidade(): void
    {
        $orgao_cod = request()->input('orgao') ?? '';

        $campo = [
            'name' => 'unidade',
            'type' => 'select2_multiple',
            'label' => 'Unidade Gestora'
        ];

        $unidades = Unidade::select(DB::raw("CONCAT(codigo,' - ',nomeresumido) AS nome"), 'codigo')
            ->whereHas('contratos', function ($u) {
                $u->where('situacao', true);
            });

        if ($orgao_cod) {
            $unidades->whereHas('orgao', function ($o) use ($orgao_cod) {
                $o->where('codigo', $orgao_cod);
            });
        }

        $this->crud->addFilter(
            $campo,
            $unidades->pluck('nome', "codigo")->toArray(),
            function ($value) {
                $value;

                $this->crud->addClause('whereIn',
                    'unidades.codigo',
                    json_decode($value)
                );
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Fornecedor
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    protected function aplicaFiltroFornecedor()
    {
        $fornecedores = Fornecedor::select(DB::raw("CONCAT(cpf_cnpj_idgener,' - ',nome) AS nome"), 'cpf_cnpj_idgener')
            ->whereHas('contratos', function ($u) {
                $u->where('situacao', true);
            })
            ->pluck('nome', "cpf_cnpj_idgener")
            ->toArray();

        $this->crud->addFilter([
            'name' => 'fornecedor',
            'type' => 'select2_multiple',
            'label' => 'Fornecedor'
        ], $fornecedores
            , function ($value) {
                $this->crud->addClause('whereIn',
                    'fornecedores.cpf_cnpj_idgener',
                    json_decode($value)
                );
            });

    }

    /**
     * Adiciona as definições da coluna Orgao do contrato
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */

    private function adicionaColunaOrgao(): void
    {
        $this->crud->addColumn([
            'name' => 'getOrgao',
            'label' => 'Órgão', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getOrgao', // the method in your Model
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    /**
     * Adiciona as definições da coluna Unidade do contrato
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function adicionaColunaUnidadeGestora(): void
    {
        $this->crud->addColumn([
            'name' => 'getUnidade',
            'label' => 'Unidade Gestora',
            'type' => 'model_function',
            'function_name' => 'getUnidade',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

}
