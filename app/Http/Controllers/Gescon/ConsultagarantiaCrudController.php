<?php

namespace App\Http\Controllers\Gescon;

//use App\Models\BackpackUser;
use App\Models\Codigo;
use App\Models\Codigoitem;
use App\Http\Controllers\Gescon\ConsultaContratoBaseCrudController as CrudController;
use Backpack\CRUD\CrudPanel;

/**
 * Class ConsultagarantiaCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 * @author Saulo Soares <saulosao@gmail.com>
 */
class ConsultagarantiaCrudController extends CrudController
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

        $this->crud->setModel('App\Models\Contratogarantia');
        $this->crud->setRoute(
            config('backpack.base.route_prefix') . '/gescon/consulta/garantias');
        $this->crud->setEntityNameStrings('Garantia', 'Garantias');
        $this->crud->setHeading('Consulta Garantias por Contrato');


        $this->crud->addClause('select', 'contratogarantias.*');
        $this->crud->addClause('join'
            , 'contratos', 'contratos.id', '=', 'contratogarantias.contrato_id');
        $this->crud->addClause('join'
            , 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id'
        );

        // Apenas ocorrências da unidade atual
        $this->crud->addClause('where', 'contratos.unidade_id', '=', session('user_ug_id'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->defineConfiguracaoPadrao();
//        $this->adicionaColunasEspecificasNaListagem();
//        $this->aplicaFiltrosEspecificos();

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

    public function adicionaColunasEspecificasNaListagem()
    {
        $this->adicionaColunaTipo();
        $this->adicionaColunaValor();
        $this->adicionaColunaVencimento();

    }

    private function adicionaColunaTipo()
    {
        $this->crud->addColumn([
            'name' => 'getTipo',
            'label' => 'Tipo',
            'type' => 'model_function',
            'function_name' => 'getTipo',
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
            'name' => 'formatVlr',
            'label' => 'Valor',
            'type' => 'model_function',
            'function_name' => 'formatVlr',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

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
//            'searchLogic'   => function ($query, $column, $searchTerm) {
//                $query->orWhere('vencimento', '$searchTerm');
//            },
        ]);
    }

    public function aplicaFiltrosEspecificos()
    {
        $this->aplicaFiltroTipoGarantia();
        $this->aplicaFiltroVencimento();
    }

    private function aplicaFiltroTipoGarantia()
    {
        $campo = [
            'name' => 'garantia',
            'type' => 'select2',
            'label' => 'Tipo Garantia'
        ];

        $garantias = $this->retornaGarantias();

        $this->crud->addFilter(
            $campo,
            $garantias,
            function ($value) {
                $this->crud->addClause('where', 'contratogarantias.tipo', $value);
            }
        );
    }

    private function aplicaFiltroVencimento()
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
                    , 'contratogarantias.vencimento', '>=', $dates->from);
                $this->crud->addClause('where'
                    , 'contratogarantias.vencimento', '<=', $dates->to . ' 23:59:59');
            });
    }

    private function retornaGarantias()
    {

        $dados = Codigoitem::select('descricao', 'id');

        $dados->where('codigo_id', Codigo::CODIGO_TIPO_GARANTIA);
        $dados->orderBy('descricao');
//        dd($dados->pluck('descricao', 'id')->toArray());

        return $dados->pluck('descricao', 'id')->toArray();

    }


}
