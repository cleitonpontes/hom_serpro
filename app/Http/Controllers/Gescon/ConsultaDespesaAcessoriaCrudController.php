<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use Backpack\CRUD\app\Http\Controllers\Operations\Response;
use Exception;
use Backpack\CRUD\CrudPanel;

/**
 * Class ConsultaDespesaAcessoriaCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 * @author Saulo Soares <saulosao@gmail.com>
 */
class ConsultaDespesaAcessoriaCrudController extends ConsultaContratoBaseCrudController
{
    /**
     * Adiciona as colunas específicas a serem exibidas bem como suas definições
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaColunasEspecificasNaListagem()
    {
        $this->adicionaColunaTipoDespesa();
        $this->adicionaColunaRecorrenciaDespesa();
        $this->adicionaColunaDescricaoComplementar();
        $this->adicionaColunaVencimento();
        $this->adicionaColunaValor();
    }

    /**
     * Adiciona filtros específicos a serem apresentados
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function aplicaFiltrosEspecificos()
    {
        $this->adicionaFiltroTipoDespesa();
        $this->adicionaFiltroRecorrenciaDespesa();
        $this->adicionaFiltroDataVencimento();
    }

    /**
     * Configurações iniciais do Backpack
     *
     * @throws Exception
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setModel('App\Models\Contratodespesaacessoria');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/consulta/despesasacessorias');
        $this->crud->setEntityNameStrings('Despesa Acessória', 'Despesas Acessórias');
        $this->crud->setHeading('Consulta Despesas Acessórias por Contrato');

        $this->crud->addClause('leftJoin', 'contratos',
            'contratos.id', '=', 'contratodespesaacessoria.contrato_id'
        );
        $this->crud->addClause('leftJoin', 'fornecedores',
            'fornecedores.id', '=', 'contratos.fornecedor_id'
        );
        $this->crud->addClause('select', [
            'contratos.*',
            'fornecedores.*',
            // Tabela principal deve ser sempre a última da listagem!
            'contratodespesaacessoria.*'
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
     * @return Response
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('tipo_id');
        $this->crud->removeColumn('contrato_id');
        $this->crud->removeColumn('recorrencia_id');
        $this->crud->removeColumn('valor');

        return $content;
    }

    private function adicionaColunaTipoDespesa()
    {
        $this->crud->addColumn([
            'name' => 'getTipoDespesa',
            'label' => 'Tipo Despesa',
            'type' => 'model_function',
            'function_name' => 'getTipoDespesa',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaRecorrenciaDespesa()
    {
        $this->crud->addColumn([
            'name' => 'getRecorrenciaDespesa',
            'label' => 'Recorrência Despesa',
            'type' => 'model_function',
            'function_name' => 'getRecorrenciaDespesa',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaDescricaoComplementar()
    {
        $this->crud->addColumn([
            'name' => 'descricao_complementar',
            'label' => 'Descrição Complementar',
            'type' => 'test',
            'limit' => 255,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaVencimento()
    {
        $this->crud->addColumn([
            'name' => 'vencimento',
            'label' => 'Vencimento',
            'type' => 'date',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaValor()
    {
        $this->crud->addColumn([
            'name' => 'formatValor',
            'label' => 'Valor',
            'type' => 'model_function',
            'function_name' => 'formatValor',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,

        ]);
    }

    public function adicionaFiltroDataVencimento()
    {
        $campo = [
            'name' => 'vencimento',
            'type' => 'date_range',
            'label' => 'Vencimento'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratodespesaacessoria.vencimento', '>=', $dates->from . ' 00:00:00');
                $this->crud->addClause('where', 'contratodespesaacessoria.vencimento', '<=', $dates->to . ' 23:59:59');
            }
        );
    }

    public function adicionaFiltroTipoDespesa()
    {
        $campo = [
            'name' => 'tipo_id',
            'type' => 'select2_multiple',
            'label' => 'Tipo Despesa'
        ];

        $tiposLista = $this->retornaTipoDespesa();

        $this->crud->addFilter(
            $campo,
            $tiposLista,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'contratodespesaacessoria.tipo_id', json_decode($value));
            }
        );
    }

    public function adicionaFiltroRecorrenciaDespesa()
    {
        $campo = [
            'name' => 'recorrencia_id',
            'type' => 'select2_multiple',
            'label' => 'Recorrência Despesa'
        ];

        $tiposLista = $this->retornaRecorrenciaDespesa();

        $this->crud->addFilter(
            $campo,
            $tiposLista,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'contratodespesaacessoria.recorrencia_id', json_decode($value));
            }
        );
    }

    private function retornaTipoDespesa()
    {
        $tipoDespesa = Codigoitem::whereHas('codigo', function ($c) {
            $c->where('descricao', 'Tipo Despesa Acessória');
        })
            ->orderBy('descricao')
            ->pluck('descricao', 'id');

        return $tipoDespesa->toArray();
    }

    private function retornaRecorrenciaDespesa()
    {
        $recorrenciaDespesa = Codigoitem::whereHas('codigo', function ($c) {
            $c->where('descricao', 'Recorrência Despesa Acessória');
        })
            ->orderBy('descricao')
            ->pluck('descricao', 'id');

        return $recorrenciaDespesa->toArray();
    }
}
