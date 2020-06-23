<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use Backpack\CRUD\CrudPanel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
// use App\Http\Requests\ContratofaturaRequest as StoreRequest;
// use App\Http\Requests\ContratofaturaRequest as UpdateRequest;
use Illuminate\Database\Eloquent\Builder;

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
     * @var number
     */
    protected $contratoId;

    /**
     * Estabelece as definições iniciais da ConsultaContratoX...
     *
     * @example Dentro de setup, chamar o método abaixo $this->defineConfiguracaoPadrao();
     *          Para adicionar campos específicos, una os arrays de $this->retornaContratoColunas() e
     *          $this->retornaContratoCampos()
     * @author Anderson Sathler <asathler@gmail.com>
     */
    protected function defineConfiguracaoPadrao()
    {
        $this->contratoId = retornaContratoId();
        $this->negaTodosOsPrivilégios();
        $this->retornaContratoFiltros();

        $this->crud->enableExportButtons();

        // As definições abaixo devem ser informadas na Controller de destino.
        // $this->crud->setModel('App\Models\Modelo');
        // $this->crud->setRoute(config('backpack.base.route_prefix') . 'caminho da rota');
        // $this->crud->setEntityNameStrings('Entidade', 'Entidades');
        // $this->crud->setHeading('Título do cabeçalho');

        $this->crud->addColumns($this->retornaContratoColunas());
        $this->crud->addFields($this->retornaContratoCampos());
    }

    /**
     * Nega os privilégios do CRUD
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    protected function negaTodosOsPrivilégios()
    {
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('show');
    }

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
     * Adiciona os filtros sobre Contratos
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    protected function retornaContratoFiltros()
    {
        $this->retornaContratoFiltroNumero();
        $this->retornaContratoFiltroFornecedor();
    }

    /**
     * Retorna colunas a serem exibidas bem como suas definições
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    protected function retornaContratoColunas()
    {
        $colunas[] = $this->retornaContratoColunaNumero();
        $colunas[] = $this->retornaContratoColunaFornecedor();
        $colunas[] = $this->retornaContratoColunaObjeto();
        $colunas[] = $this->retornaContratoColunaVigenciaInicio();
        $colunas[] = $this->retornaContratoColunaVigenciaFim();
        $colunas[] = $this->retornaContratoColunaValorGlobal();
        $colunas[] = $this->retornaContratoColunaNumeroParcelas();
        $colunas[] = $this->retornaContratoColunaValorParcela();

        return $colunas;
    }

    /**
     * Retorna campos para exibição no formulário de inclusão / edição
     *
     * @todo Criar métodos para cada campo
     * @author Anderson Sathler <asathler@gmail.com>
     */
    protected function retornaContratoCampos()
    {
        //
    }

    /**
     * Adiciona o filtro ao campo Número do Contrato
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoFiltroNumero()
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
    private function retornaContratoFiltroFornecedor()
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
     * Retorna item de array contendo as definições da coluna Número do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoColunaNumero()
    {
        return [
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
    }

    /**
     * Retorna item de array contendo as definições da coluna Fornecedor do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoColunaFornecedor()
    {
        return [
            // Método getFornecedor deve estar presente em \App\Models\Contrato
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
    }

    /**
     * Retorna item de array contendo as definições da coluna Objeto do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoColunaObjeto()
    {
        return [
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
    }

    /**
     * Retorna item de array contendo as definições da coluna Data de Início da Vigência do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoColunaVigenciaInicio()
    {
        return [
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
    }

    /**
     * Retorna item de array contendo as definições da coluna Data de Fim da Vigência do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoColunaVigenciaFim()
    {
        return [
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
    }

    /**
     * Retorna item de array contendo as definições da coluna Valor Global do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoColunaValorGlobal()
    {
        return [
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
    }

    /**
     * Retorna item de array contendo as definições da coluna Número de Parcelas do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoColunaNumeroParcelas()
    {
        return [
            'name' => 'contrato.num_parcelas',
            'label' => 'Núm. Parcelas',
            'priority' => 8,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ];
    }

    /**
     * Retorna item de array contendo as definições da coluna Valor da Parcelas do contrato
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoColunaValorParcela()
    {
        return [
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
    }

}
