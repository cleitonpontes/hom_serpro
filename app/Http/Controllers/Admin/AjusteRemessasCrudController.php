<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\BuscaCodigoItens;
use App\Models\MinutaEmpenho;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class AjusteRemessasCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AjusteRemessasCrudController extends CrudController
{
    use BuscaCodigoItens;

    public function setup()
    {
        $this->minuta_id = $this->crud->getCurrentEntryId();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\MinutaEmpenhoRemessa');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/ajusteremessas');
        $this->crud->setEntityNameStrings('Ajuste de remessas', 'Ajuste de remessas');
        $this->crud->setShowView('vendor.backpack.crud.empenho.show');

        $this->crud->allowAccess('update');
        $this->crud->allowAccess('show');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('create');
        $this->crud->addButtonFromView('line', 'atualizaritemcompracontrato', 'atualizaritemcompracontrato', 'end');

        (backpack_user()->can('minuta_ajuste_editar')) ? $this->crud->allowAccess('update') : null;


        $this->crud->addClause('select',
            [
                'minutaempenhos_remessa.*',
                DB::raw("CONCAT(unidades.codigo,' - ',unidades.nomeresumido) AS unidade"),
//                DB::raw("CONCAT(uc.codigo,' - ',uc.nomeresumido) AS unidadeCompra"),
                'uc.codigo AS unidadeCompra',
                'codigoitens.descricao AS tipoEmpenhoPor',
                'compras.numero_ano',
                DB::raw('minutaempenhos_remessa.id as "minutaempenhos_remessa_id"')
            ]
        );
        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));
        $this->crud->addClause('where', 'minutaempenhos_remessa.remessa', '<>', 0);
        $this->crud->addClause('join', 'minutaempenhos', 'minutaempenhos_remessa.minutaempenho_id', '=', 'minutaempenhos.id');
        $this->crud->addClause('join', 'unidades', 'minutaempenhos.unidade_id', '=', 'unidades.id');
        $this->crud->addClause('join', 'compras', 'minutaempenhos.compra_id', '=', 'compras.id');
        $this->crud->addClause('join', 'unidades as uc', 'compras.unidade_origem_id', '=', 'uc.id');
        $this->crud->addClause('join', 'codigoitens', 'minutaempenhos.tipo_empenhopor_id', '=', 'codigoitens.id');
        $this->crud->orderBy('minutaempenhos.updated_at', 'desc');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();
        $this->crud->enableExportButtons();

        $this->adicionaCampos($this->minuta_id);
        $this->adicionaColunas($this->minuta_id);


        // add asterisk for fields that are required in AjusteRemessasRequest
//        $this->crud->setRequiredFields(StoreRequest::class, 'create');
//        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->adicionaBoxItens($id);
        $this->adicionaBoxSaldo($id);

        $this->crud->removeColumn('tipo_empenhopor_id');
        $this->crud->removeColumn('situacao_id');
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
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    protected function adicionaCampos($minuta_id)
    {
        $this->adicionaCampoMensagemSIAF();
        $this->adicionaCampoSituacao($minuta_id);
    }


    protected function adicionaCampoMensagemSIAF()
    {
        $this->crud->addField([
            'name' => 'mensagem_siafi',
            'label' => 'Mensagem SIAF',
            'type' => 'text',
            'allows_null' => false,
        ]);
    }

    protected function adicionaCampoSituacao($minuta_id)
    {
        $minuta = MinutaEmpenho::find($minuta_id);
        $this->crud->addField([
            'name' => 'nova_situacao',
            'label' => "Situação do Empenho",
            'type' => 'select2_from_array',
            'options' => $this->retornaArrayCodigosItens('Situações Minuta Empenho'),
            'allows_null' => false,
            'default' => $minuta ? $minuta->situacao_id : null,
        ]);
    }

    protected function adicionaColunas($minuta_id): void
    {
        $this->adicionaColunaUnidade();
//        $this->adicionaColunaFornecedorEmpenho();
//
//        $this->adicionaColunaTipoCompra();
        $this->adicionaColunaUnidadeCompra();
//        $this->adicionaColunaModalidade();
        $this->adicionaColunaTipoEmpenhoPor();
        $this->adicionaColunaNumeroAnoCompra();
//
//        $this->adicionaColunaTipoEmpenho();
//        $this->adicionaColunaAmparoLegal();
//
//        $this->adicionaColunaIncisoCompra();
//        $this->adicionaColunaLeiCompra();
//        $this->adicionaColunaValorTotal();
//
//
        $this->adicionaColunaMensagemSiafi();
        $this->adicionaColunaRemessa();
        $this->adicionaColunaSituacao();
//        $this->adicionaColunaCreatedAt();
        $this->adicionaColunaUpdatedAt();
//
//
//        $this->adicionaColunaNumeroEmpenho();
//        $this->adicionaColunaCipi();
//        $this->adicionaColunaDataEmissao();
//        $this->adicionaColunaProcesso();
//        $this->adicionaColunaTaxaCambio();
//        $this->adicionaColunaLocalEntrega();
//        $this->adicionaColunaDescricao();
    }

    public function adicionaColunaSituacao(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getSituacao',
            'label' => 'Situação',
            'type' => 'model_function',
            'function_name' => 'getSituacao',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    public function adicionaColunaMensagemSiafi(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'mensagem_siafi',
            'label' => 'Mensagem SIAFI',
            'type' => 'text',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('minutaempenhos.mensagem_siafi', 'like', "%$searchTerm%");
            },
        ]);
    }

    public function adicionaColunaRemessa(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'remessa',
            'label' => 'Remessa',
            'type' => 'text',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('minutaempenhos.mensagem_siafi', 'like', "%$searchTerm%");
            },
        ]);
    }

    /**
     * Configura a coluna Unidade
     */

    public function adicionaColunaUnidade(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'unidade',
            'label' => 'Unidade Gestora',
            'type' => 'text',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('minutaempenhos.mensagem_siafi', 'like', "%$searchTerm%");
            },
        ]);
    }

    public function adicionaColunaUnidadeCompra(): void
    {
        $this->crud->addColumn([
            'name' => 'unidadeCompra',
            'label' => 'UASG Compra',
            'type' => 'text',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('minutaempenhos.mensagem_siafi', 'like', "%$searchTerm%");
            },
        ]);
    }

    public function adicionaColunaUpdatedAt(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'updated_at',
            'label' => 'Atualizado em',
            'type' => 'datetime',
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
            'limit' => 100,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
            /*'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%$searchTerm%");
                $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
            },*/
        ]);
    }

    public function adicionaColunaTipoEmpenhoPor()
    {

        $this->crud->addColumn([
            'name' => 'tipoEmpenhoPor',
            'label' => 'Tipo de Minuta', // Table column heading
            'type' => 'text',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('minutaempenhos.mensagem_siafi', 'like', "%$searchTerm%");
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
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

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

    public function adicionaColunaValorTotal()
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'valor_total',
            'label' => 'Valor Total', // Table column heading
            'type' => 'number',
            'prefix' => 'R$ ',
            'decimals' => 2,
//            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
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
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaColunaNumeroAnoCompra()
    {
        $this->crud->addColumn([
            'name' => 'numero_ano',
            'label' => 'Numero/Ano', // Table column heading
            'type' => 'text',
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
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => false, // would make the modal too big
            'visibleInExport' => false, // not important enough
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
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => false, // would make the modal too big
            'visibleInExport' => false, // not important enough
            'visibleInShow' => true, // sure, why not
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
