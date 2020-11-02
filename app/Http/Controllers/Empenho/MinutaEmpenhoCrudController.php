<?php

namespace App\Http\Controllers\Empenho;

use App\Models\AmparoLegal;
use App\Models\Codigoitem;
use App\Models\Compra;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MinutaEmpenhoRequest as StoreRequest;
use App\Http\Requests\MinutaEmpenhoRequest as UpdateRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;
use Redirect;
use Route;
use App\Http\Traits\Formatador;

/**
 * Class MinutaEmpenhoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MinutaEmpenhoCrudController extends CrudController
{
    use Formatador;

    public function setup()
    {
        $this->minuta_id = $this->crud->getCurrentEntryId();
        // dd($this->minuta_id);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\MinutaEmpenho');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'empenho/minuta');
        $this->crud->setEntityNameStrings('Minuta de Empenho', 'Minutas de Empenho');
        $this->crud->setEditView('vendor.backpack.crud.empenho.edit');
        $this->crud->setShowView('vendor.backpack.crud.empenho.show');
        $this->crud->allowAccess('update');
        $this->crud->addButtonFromView('top', 'create', 'createbuscacompra');

        $this->crud->allowAccess('show');
        $this->crud->denyAccess('delete');

//        $this->crud->denyAccess('update');
        // $this->crud->denyAccess('show');

        // $this->crud->addButton('', 'Nova Minuta', 'view', 'teste', '');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
        $this->adicionaCampos($this->minuta_id);
        $this->adicionaColunas($this->minuta_id);

        // add asterisk for fields that are required in MinutaEmpenhoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $request->request->set('taxa_cambio', $this->retornaFormatoAmericano($request->taxa_cambio));
        $request->request->set('etapa', 7);

        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return Redirect::to('empenho/passivo-anterior/' . $this->minuta_id);
//        return $redirect_location;
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('situacao');
        $this->crud->removeColumn('unidade_id');
        $this->crud->removeColumn('compra_id');

        $this->crud->removeColumn('fornecedor_compra_id');
        $this->crud->removeColumn('fornecedor_empenho_id');
        $this->crud->removeColumn('saldo_contabil_id');

        $this->crud->removeColumn('tipo_empenho_id');
        $this->crud->removeColumn('amparo_legal_id');

        $this->crud->removeColumn('numero_empenho_sequencial');
        $this->crud->removeColumn('passivo_anterior');
        $this->crud->removeColumn('conta_contabil_passivo_anterior');
        $this->crud->removeColumn('tipo_minuta_empenho');

        return $content;
    }

    protected function adicionaCampos($minuta_id)
    {
        $this->adicionaCampoNumeroEmpenho();
        $this->adicionaCampoCipi();
        $this->adicionaCampoDataEmissao();
        $this->adicionaCampoTipoEmpenho();
        $this->adicionaCampoFornecedor();
        $this->adicionaCampoProcesso();
        $this->adicionaCampoAmparoLegal($minuta_id);
        $this->adicionaCampoTaxaCambio();
        $this->adicionaCampoLocalEntrega();
        $this->adicionaCampoDescricao();
    }

    protected function adicionaCampoNumeroEmpenho()
    {
        $this->crud->addField([
            'name' => 'numero_empenho_sequencial',
            'label' => 'Número Empenho',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function adicionaCampoCipi()
    {
        $this->crud->addField([
            'name' => 'cipi',
            'label' => 'CIPI',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'attributes' => [
                'disabled' => true
            ]
        ]);
    }

    protected function adicionaCampoDataEmissao()
    {
        $this->crud->addField([
            'name' => 'data_emissao',
            'label' => 'Data Emissão',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function adicionaCampoTipoEmpenho()
    {
        $tipo_empenhos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo Minuta Empenho');
        })->where('visivel', false)->orderBy('descricao')->pluck('descricao', 'id')->toArray();

        $this->crud->addField([
            'name' => 'tipo_empenho_id',
            'label' => "Tipo Empenho",
            'type' => 'select2_from_array',
            'options' => $tipo_empenhos,
            'allows_null' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function adicionaCampoFornecedor()
    {
        $this->crud->addField([
            'label' => "Credor",
            'type' => "select2_from_ajax_credor",
            'name' => 'fornecedor_empenho_id',
            'entity' => 'fornecedor_empenho',
            'attribute' => "cpf_cnpj_idgener",
            'attribute2' => "nome",
            'process_results_template' => 'gescon.process_results_fornecedor',
            'model' => "App\Models\Fornecedor",
            'data_source' => url("api/fornecedor"),
            'placeholder' => "Selecione o fornecedor",
            'minimum_input_length' => 2,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function adicionaCampoProcesso()
    {
        $this->crud->addField([
            'name' => 'processo',
            'label' => 'Número Processo',
            'type' => 'text',
            'limit' => 20,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function adicionaCampoAmparoLegal($minuta_id)
    {
        $modelo = MinutaEmpenho::find($minuta_id);

        $this->crud->addField([
            'name' => 'amparo_legal_id',
            'label' => "Amparo Legal",
            'type' => 'select2_from_array',
            'options' => $minuta_id ? $modelo->retornaAmparoPorMinuta() : [],
            'allows_null' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function adicionaCampoTaxaCambio()
    {
        $this->crud->addField([
            'name' => 'taxa_cambio',
            'label' => 'Taxa de Cambio',
            'type' => 'taxa_cambio',
            'attributes' => [
                'id' => 'taxa_cambio'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function adicionaCampoLocalEntrega()
    {
        $this->crud->addField([
            'name' => 'local_entrega',
            'label' => 'Local de Entrega',
            'attributes' => [
                'onblur' => "maiuscula(this)"
            ]
        ]);
    }

    protected function adicionaCampoDescricao()
    {
        $this->crud->addField([
            'name' => 'descricao',
            'label' => 'Descrição / Observação',
            'type' => 'textarea',
            'attributes' => [
                'onblur' => "maiuscula(this)"
            ]
        ]);
    }

    /**
     * Configura a grid de visualização
     *
     */
    protected function adicionaColunas($minuta_id): void
    {
        $this->adicionaColunaUnidade();
        $this->adicionaColunaFornecedorEmpenho();
        $this->adicionaColunaTipoEmpenho();
        $this->adicionaColunaAmparoLegal();

        $this->adicionaColunaModalidade();
        $this->adicionaColunaTipoCompra();
        $this->adicionaColunaNumeroAnoCompra();
        $this->adicionaColunaIncisoCompra();
        $this->adicionaColunaLeiCompra();

        $this->adicionaColunasItens($minuta_id);
//        dd('tese');

//        $this->adicionaColunaSituacao();
        $this->adicionaColunaNumeroEmpenho();
        $this->adicionaColunaCipi();
        $this->adicionaColunaDataEmissao();
        $this->adicionaColunaProcesso();
        $this->adicionaColunaTaxaCambio();
        $this->adicionaColunaLocalEntrega();
        $this->adicionaColunaDescricao();
    }

    protected function adicionaColunaSituacao()
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'situacao',
            'label' => 'Situação',
            'type' => 'boolean',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'options' => [0 => 'Inativo', 1 => 'Ativo']
        ]);
    }

    /**
     * Configura a coluna Unidade
     */

    public function adicionaColunaUnidade(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getUnidade',
            'label' => 'Unidade Gestora',
            'type' => 'model_function',
            'function_name' => 'getUnidade',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    public function adicionaColunaFornecedorEmpenho(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getFornecedorEmpenho',
            'label' => 'Credor', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getFornecedorEmpenho', // the method in your Model
            'orderable' => true,
            'limit' => 1000,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%$searchTerm%");
                $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
            },
        ]);
    }

    public function adicionaColunaTipoEmpenho()
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getTipoEmpenho',
            'label' => 'Tipo de Empenho', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getTipoEmpenho', // the method in your Model
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

        ]);
    }

    public function adicionaColunaAmparoLegal()
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getAmparoLegal',
            'label' => 'Amparo Legal', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }


    public function adicionaColunaModalidade()
    {
        $this->crud->addColumn([
            'box' => 'compra',
            'name' => 'compra_modalidade',
            'label' => 'Modalidade', // Table column heading
            'type' => 'text',
//            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaColunaTipoCompra()
    {
        $this->crud->addColumn([
            'box' => 'compra',
            'name' => 'tipo_compra',
            'label' => 'Tipo da Compra', // Table column heading
            'type' => 'text',
//            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaColunaNumeroAnoCompra()
    {
        $this->crud->addColumn([
            'box' => 'compra',
            'name' => 'numero_ano',
            'label' => 'Numero/Ano', // Table column heading
            'type' => 'text',
//            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaColunaIncisoCompra()
    {
        $this->crud->addColumn([
            'box' => 'compra',
            'name' => 'inciso',
            'label' => 'Inciso', // Table column heading
            'type' => 'text',
//            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaColunaLeiCompra()
    {
        $this->crud->addColumn([
            'box' => 'compra',
            'name' => 'lei',
            'label' => 'Lei', // Table column heading
            'type' => 'text',
//            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaColunasItens($minuta_id)
    {
        $itens = CompraItemMinutaEmpenho::join('compra_items', 'compra_items.id', '=', 'compra_item_minuta_empenho.compra_item_id')
            ->join('naturezasubitem', 'naturezasubitem.id', '=', 'compra_item_minuta_empenho.subelemento_id')
            ->join('codigoitens', 'codigoitens.id', '=', 'compra_items.tipo_item_id')
            ->join('catmatseritens', 'catmatseritens.id', '=', 'compra_items.catmatseritem_id')
            ->join('fornecedores', 'fornecedores.id', '=', 'compra_items.fornecedor_id')
            ->where('compra_item_minuta_empenho.minutaempenho_id', $minuta_id)
            ->select([
                DB::raw('codigoitens.descricao AS "Tipo do Item"'),
                DB::raw('compra_items.descricaodetalhada AS "Descrição Detalhada"'),

                DB::raw('catmatseritens.descricao AS "CatMatSerItem"'),
                DB::raw('catmatseritens.codigo_siasg AS "Código"'),
                DB::raw('fornecedores.nome AS "Fornecedor"'),
                DB::raw('fornecedores.cpf_cnpj_idgener AS "CPF/CNPJ/IDGENER do Fornecedor"'),
                DB::raw('naturezasubitem.descricao AS "Natureza/Despeza"'),
                DB::raw('compra_items.valorunitario AS "Valor unitário"'),
                DB::raw('compra_item_minuta_empenho.quantidade AS "Quantidade"'),
                DB::raw('compra_item_minuta_empenho.Valor AS "Valor Total do Item"'),
            ])
            ->get()->toArray();

        $this->crud->addColumn([
            'box' => 'itens',
            'name' => 'itens',
            'label' => 'itens', // Table column heading
//            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
            'values' => $itens
        ]);
    }

    public function adicionaColunaNumeroEmpenho()
    {
    }

    public function adicionaColunaCipi()
    {
    }

    public function adicionaColunaDataEmissao()
    {
    }


    public function adicionaColunaProcesso()
    {
    }


    public function adicionaColunaTaxaCambio()
    {
    }

    public function adicionaColunaLocalEntrega()
    {
    }

    public function adicionaColunaDescricao()
    {
    }
}
