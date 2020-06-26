<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use App\Models\Fornecedor;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Classe ConsultaContratoBaseCrudController
 *
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 * @author Anderson Sathler <asathler@gmail.com>
 */
class ConsultaContratoBaseCrudController extends CrudController
{

    /**
     * Estabelece as definições iniciais da ConsultaContratoX...
     *
     * @example Dentro de setup, chamar o método abaixo $this->defineConfiguracaoPadrao()
     * @author Anderson Sathler <asathler@gmail.com>
     */
    protected function defineConfiguracaoPadrao()
    {
        $this->definePrivilegios();

        $this->aplicaFiltros();
        $this->adicionaColunasNaListagem();

        // As definições abaixo devem ser informadas na Controller de destino.
        // $this->crud->setModel('App\Models\Modelo');
        // $this->crud->setRoute(config('backpack.base.route_prefix') . 'caminho da rota');
        // $this->crud->setEntityNameStrings('Entidade', 'Entidades');
        // $this->crud->setHeading('Título do cabeçalho');

        $this->crud->addFields($this->retornaContratoCampos());
        $this->crud->enableExportButtons();

        $this->aplicaFiltrosEspecificos();
        $this->adicionaColunasEspecificasNaListagem();
    }

    /**
     * Adiciona filtros específicos a serem apresentados
     * Método a ser sobrescrito na classe filha!
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function aplicaFiltrosEspecificos() {}

    /**
     * Adiciona as colunas específicas a serem exibidas bem como suas definições
     * Método a ser sobrescrito na classe filha!
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaColunasEspecificasNaListagem() {}

    /**
     * Retorna o id do contrato, conforme parâmetro da requisição
     *
     * @return number
     * @author Anderson Sathler <asathler@gmail.com>
     */
    protected function retornaContratoId()
    {
        return \Route::current()->parameter('contrato_id');
    }

    /**
     * Retorna registro do contrato atual
     *
     * @return object
     * @author Anderson Sathler <asathler@gmail.com>
     */
    protected function retornaContratoAtual()
    {
        $contratoId = $this->retornaContratoId();

        return $this->retornaContratoPorId($contratoId);
    }

    /**
     * Retorna registro do contrato, conforme $contratoId
     *
     * @param number $contratoId
     * @return object
     * @author Anderson Sathler <asathler@gmail.com>
     */
    protected function retornaContratoPorId($contratoId = 0)
    {
        $unidadeId = session()->get('user_ug_id');

        $modelo = Contrato::select('*');
        $modelo->where('id', $contratoId);
        $modelo->where('unidade_id', $unidadeId);

        return $modelo->first();
    }

    /**
     * Nega os privilégios do CRUD
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    protected function definePrivilegios()
    {
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');
    }

    /**
     * Adiciona os filtros sobre Contratos
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    protected function aplicaFiltros()
    {
        $this->aplicaFiltroNumero();
        $this->aplicaFiltroFornecedor();
    }

    /**
     * Adiciona as colunas sobre contratos a serem exibidas bem como suas definições
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    protected function adicionaColunasNaListagem()
    {
        $this->adicionaColunaUnidade();
        $this->adicionaColunaNumero();
        $this->adicionaColunaFornecedor();
        $this->adicionaColunaObjeto();
        $this->adicionaColunaVigenciaInicio();
        $this->adicionaColunaVigenciaFim();
        $this->adicionaColunaValorGlobal();
        $this->adicionaColunaNumeroParcelas();
        $this->adicionaColunaValorParcela();
    }

    /**
     * Retorna campos para exibição no formulário de inclusão / edição
     *
     * @todo Criar métodos para cada campo
     * @author Anderson Sathler <asathler@gmail.com>
     */
    protected function retornaContratoCampos()
    {
        return [];
    }

    /**
     * Adiciona o filtro ao campo Número do Contrato
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroNumero()
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
    private function aplicaFiltroFornecedor()
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
        $dados->orderBy('id');

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
     * Retorna item de array contendo as definições da coluna Unidade do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaUnidade()
    {
        $this->crud->addColumn([
            'name' => 'getUnidade',
            'label' => 'UG',
            'type' => 'model_function',
            'function_name' => 'getUnidade',
            'priority' => 1,
            'orderable' => false,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Retorna item de array contendo as definições da coluna Número do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaNumero()
    {
        $this->crud->addColumn([
            'name' => 'contrato.numero',
            'label' => 'Número Contrato',
            'type' => 'string',
            'priority' => 2,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('contratos.numero',
                    'ilike', '%' . $searchTerm . '%'
                );
            }
        ]);
    }

    /**
     * Retorna item de array contendo as definições da coluna Fornecedor do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaFornecedor()
    {
        $this->crud->addColumn([
            'name' => 'getFornecedor',
            'label' => 'Fornecedor',
            'type' => 'model_function',
            'function_name' => 'getFornecedor',
            'priority' => 3,
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
        ]);
    }

    /**
     * Retorna item de array contendo as definições da coluna Objeto do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaObjeto()
    {
        $this->crud->addColumn([
            'name' => 'contrato.objeto',
            'label' => 'Objeto',
            'limit' => 150,
            'priority' => 4,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Retorna item de array contendo as definições da coluna Data de Início da Vigência do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaVigenciaInicio()
    {
        $this->crud->addColumn([
            'name' => 'getVigenciaInicio',
            'label' => 'Vig. Início',
            'type' => 'model_function',
            'function_name' => 'getVigenciaInicio',
            'priority' => 7,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Retorna item de array contendo as definições da coluna Data de Fim da Vigência do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaVigenciaFim()
    {
        $this->crud->addColumn([
            'name' => 'getVigenciaFim',
            'label' => 'Vig. Fim',
            'type' => 'model_function',
            'function_name' => 'getVigenciaFim',
            'priority' => 6,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Retorna item de array contendo as definições da coluna Valor Global do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaValorGlobal()
    {
        $this->crud->addColumn([
            'name' => 'getValorGlobal',
            'label' => 'Valor Global',
            'type' => 'model_function',
            'function_name' => 'getvalorGlobal',
            'prefix' => 'R$ ',
            'priority' => 5,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Retorna item de array contendo as definições da coluna Número de Parcelas do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaNumeroParcelas()
    {
        $this->crud->addColumn([
            'name' => 'contrato.num_parcelas',
            'label' => 'Núm. Parcelas',
            'priority' => 9,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Retorna item de array contendo as definições da coluna Valor da Parcelas do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaValorParcela()
    {
        $this->crud->addColumn([
            'name' => 'getValorParcela',
            'label' => 'Valor Parcela',
            'type' => 'model_function',
            'function_name' => 'getValorParcela',
            'prefix' => 'R$ ',
            'priority' => 8,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

}
