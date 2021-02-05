<?php

namespace App\Http\Controllers\Admin;

use App\Forms\InserirFornecedorForm;
use App\Http\Requests\AjusteMinutasRequest as UpdateRequest;
use App\Http\Traits\BuscaCodigoItens;
use App\Models\Codigoitem;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\CompraItemUnidade;
use App\Models\ContratoItemMinutaEmpenho;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\MinutaEmpenhoRemessa;
use App\Models\SaldoContabil;
use App\Models\SfOrcEmpenhoDados;
use App\XML\Execsiafi;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AjusteMinutasRequest as StoreRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use FormBuilder;

/**
 * Class AjusteMinutasCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AjusteMinutasCrudController extends CrudController
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
        $this->crud->setModel('App\Models\MinutaEmpenho');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'admin/ajusteminuta');
        $this->crud->setEntityNameStrings('Minuta de Empenho', 'Minutas de Empenho');
        $this->crud->setShowView('vendor.backpack.crud.empenho.show');


        $this->crud->allowAccess('update');
        $this->crud->allowAccess('show');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('create');

        (backpack_user()->can('minuta_ajuste_editar')) ? $this->crud->allowAccess('update') : null;

        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));
        $this->crud->orderBy('updated_at', 'desc');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->enableExportButtons();

        $this->adicionaCampos($this->minuta_id);
        $this->adicionaColunas($this->minuta_id);

        // add asterisk for fields that are required in MinutaEmpenhoRequest
//        $this->crud->setRequiredFields(\App\Http\Requests\MinutaEmpenhoRequest::class, 'create');
        $this->crud->setRequiredFields(\App\Http\Requests\MinutaEmpenhoRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        //$redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        //return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $minuta = MinutaEmpenho::find($request->id);
            $minuta->mensagem_siafi = $request->mensagem_siafi;
            $minuta->situacao_id = $request->nova_situacao;
            $minuta->save();

            $minutaEmpenhoRemessa = MinutaEmpenhoRemessa::where('minutaempenho_id', $request->id)->where('remessa', 0)->first();
            if($minutaEmpenhoRemessa) {
                $minutaEmpenhoRemessa->mensagem_siafi = $request->mensagem_siafi;
                $minutaEmpenhoRemessa->situacao_id = $request->nova_situacao;
                $minutaEmpenhoRemessa->save();
            }

            $redirect_location = parent::updateCrud($request);
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
//        return Redirect::to($rota);

            DB::commit();
            return $redirect_location;
        } catch (Exception $exc) {
            DB::rollback();
        }
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


    /**
     * Configura a grid de visualização
     *
     */
    protected function adicionaColunas($minuta_id): void
    {
        $this->adicionaColunaUnidade();
        $this->adicionaColunaFornecedorEmpenho();

        $this->adicionaColunaTipoCompra();
        $this->adicionaColunaUnidadeCompra();
        $this->adicionaColunaModalidade();
        $this->adicionaColunaTipoEmpenhoPor();
        $this->adicionaColunaNumeroAnoCompra();

        $this->adicionaColunaTipoEmpenho();
        $this->adicionaColunaAmparoLegal();

        $this->adicionaColunaIncisoCompra();
        $this->adicionaColunaLeiCompra();
        $this->adicionaColunaValorTotal();


        $this->adicionaColunaMensagemSiafi();
        $this->adicionaColunaSituacao();
        $this->adicionaColunaCreatedAt();
        $this->adicionaColunaUpdatedAt();


        $this->adicionaColunaNumeroEmpenho();
        $this->adicionaColunaCipi();
        $this->adicionaColunaDataEmissao();
        $this->adicionaColunaProcesso();
        $this->adicionaColunaTaxaCambio();
        $this->adicionaColunaLocalEntrega();
        $this->adicionaColunaDescricao();
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
            'visibleInShow' => true
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

    public function adicionaColunaCreatedAt(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'created_at',
            'label' => 'Criação em',
            'type' => 'datetime',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
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

    public function adicionaColunaUnidadeCompra(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getUnidadeCompra',
            'label' => 'UASG Compra',
            'type' => 'model_function',
            'function_name' => 'getUnidadeCompra',
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
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%$searchTerm%");
                $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
            },
        ]);
    }

    public function adicionaColunaTipoEmpenhoPor()
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getTipoEmpenhoPor',
            'label' => 'Tipo de Minuta', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getTipoEmpenhoPor', // the method in your Model
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

    public function adicionaColunaAmparoLegal()
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getAmparoLegal',
            'label' => 'Amparo Legal', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
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

    public function adicionaBoxItens($minuta_id)
    {
        $modMinuta = MinutaEmpenho::find($minuta_id);

        $codigoitem = Codigoitem::find($modMinuta->tipo_empenhopor_id);

        if ($codigoitem->descricao === 'Contrato') {
            $itens = ContratoItemMinutaEmpenho::join(
                'contratoitens',
                'contratoitens.id',
                '=',
                'contrato_item_minuta_empenho.contrato_item_id'
            )
                ->join('minutaempenhos', 'minutaempenhos.id', '=', 'contrato_item_minuta_empenho.minutaempenho_id')
                ->join('contratos', 'contratos.id', '=', 'minutaempenhos.contrato_id')
                ->join('codigoitens', 'codigoitens.id', '=', 'contratoitens.tipo_id')
                ->join('catmatseritens', 'catmatseritens.id', '=', 'contratoitens.catmatseritem_id')
                ->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id')
                ->join(
                    'minutaempenhos_remessa',
                    'minutaempenhos_remessa.id',
                    '=',
                    'contrato_item_minuta_empenho.minutaempenhos_remessa_id'
                )
                ->where('contrato_item_minuta_empenho.minutaempenho_id', $minuta_id)
                ->where('minutaempenhos_remessa.remessa', 0)
                ->select([
                    DB::raw('fornecedores.cpf_cnpj_idgener AS "CPF/CNPJ/IDGENER do Fornecedor"'),
                    DB::raw('fornecedores.nome AS "Fornecedor"'),
                    DB::raw('codigoitens.descricao AS "Tipo do Item"'),
                    DB::raw('catmatseritens.codigo_siasg AS "Código do Item"'),
                    DB::raw('contratoitens.numero_item_compra AS "Número do Item"'),
                    DB::raw('catmatseritens.descricao AS "Descrição"'),
                    DB::raw('contratoitens.descricao_complementar AS "Descrição Detalhada"'),
                    DB::raw('contrato_item_minuta_empenho.quantidade AS "Quantidade"'),
                    DB::raw('contrato_item_minuta_empenho.Valor AS "Valor Total do Item"'),
                ])
                ->get()->toArray();
        }

        if ($codigoitem->descricao === 'Compra'|| $codigoitem->descricao === 'Suprimento') {
            $itens = CompraItemMinutaEmpenho::join('compra_items', 'compra_items.id', '=', 'compra_item_minuta_empenho.compra_item_id')
                ->join('compra_item_fornecedor', 'compra_item_fornecedor.compra_item_id', '=', 'compra_item_minuta_empenho.compra_item_id')
                ->join('naturezasubitem', 'naturezasubitem.id', '=', 'compra_item_minuta_empenho.subelemento_id')
                ->join('codigoitens', 'codigoitens.id', '=', 'compra_items.tipo_item_id')
                ->join('catmatseritens', 'catmatseritens.id', '=', 'compra_items.catmatseritem_id')
                ->join('compra_item_unidade', 'compra_item_unidade.compra_item_id', '=', 'compra_items.id')
                //            ->join('compra_item_fornecedor', 'compra_item_fornecedor.compra_item_id', '=', 'compra_items.id')
                ->join('fornecedores', 'fornecedores.id', '=', 'compra_item_fornecedor.fornecedor_id')
                ->join('minutaempenhos_remessa', 'minutaempenhos_remessa.id', '=', 'compra_item_minuta_empenho.minutaempenhos_remessa_id')
                ->where('compra_item_minuta_empenho.minutaempenho_id', $minuta_id)
                ->where('minutaempenhos_remessa.remessa', 0)
                ->select([
                    DB::raw('fornecedores.cpf_cnpj_idgener AS "CPF/CNPJ/IDGENER do Fornecedor"'),
                    DB::raw('fornecedores.nome AS "Fornecedor"'),
                    DB::raw('codigoitens.descricao AS "Tipo do Item"'),
                    DB::raw('catmatseritens.codigo_siasg AS "Código do Item"'),
                    DB::raw('compra_items.numero AS "Número do Item"'),
                    DB::raw('catmatseritens.descricao AS "Descrição"'),
                    DB::raw('compra_items.descricaodetalhada AS "Descrição Detalhada"'),
                    DB::raw('naturezasubitem.codigo || \' - \' || naturezasubitem.descricao AS "ND Detalhada"'),
                    DB::raw('compra_item_fornecedor.valor_unitario AS "Valor unitário"'),
                    DB::raw('compra_item_minuta_empenho.quantidade AS "Quantidade"'),
                    DB::raw('compra_item_minuta_empenho.Valor AS "Valor Total do Item"'),
                ]);
            if ($codigoitem->descricao === 'Suprimento') {
                $itens = $itens->where('compra_item_fornecedor.fornecedor_id', $modMinuta->fornecedor_empenho_id);
            }
            $itens = $itens->get()->toArray();
        }

        $this->crud->addColumn([
            'box' => 'itens',
            'name' => 'itens',
            'label' => 'itens', // Table column heading
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => false, // would make the modal too big
            'visibleInExport' => false, // not important enough
            'visibleInShow' => true, // sure, why not
            'values' => $itens
        ]);
    }

    public function adicionaBoxSaldo($minuta_id)
    {

        $saldo = SaldoContabil::join('minutaempenhos', 'minutaempenhos.saldo_contabil_id', '=', 'saldo_contabil.id')
            ->select([
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,1,1) AS "Esfera"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,2,6) AS "PTRS"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,8,10) AS "Fonte"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,18,6) AS "ND"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,24,8) AS "UGR"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,32,11) AS "Plano Interno"'),
                DB::raw('TO_CHAR(saldo_contabil.saldo,\'999G999G000D99\') AS "Crédito orçamentário"'),
                DB::raw('TO_CHAR(minutaempenhos.valor_total,\'999G999G000D99\') AS "Utilizado"'),
                DB::raw('TO_CHAR(saldo_contabil.saldo - minutaempenhos.valor_total, \'999G999G000D99\')  AS "Saldo"'),

            ])
            ->where('minutaempenhos.id', $minuta_id)
            ->get()
            ->toArray();

        $this->crud->addColumn([
            'box' => 'saldo',
            'name' => 'saldo',
            'label' => 'saldo', // Table column heading
//            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => false, // would make the modal too big
            'visibleInExport' => false, // not important enough
            'visibleInShow' => true, // sure, why not
            'values' => $saldo
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

    public function retonaFormModal()
    {
        return FormBuilder::create(InserirFornecedorForm::class, [
            'id' => 'form_modal'
        ]);
    }

    public function inserirFornecedorModal(Request $request)
    {

        DB::beginTransaction();
        try {
            $fornecedor = Fornecedor::firstOrCreate(
                ['cpf_cnpj_idgener' => $request->cpf_cnpj_idgener],
                [
                    'tipo_fornecedor' => $request->fornecedor,
                    'nome' => $request->nome
                ]
            );
            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
        }
        return $fornecedor;
    }

    public function executarAtualizacaoSituacaoMinuta($id)
    {
        $minuta = MinutaEmpenho::find($id);

        if ($minuta->situacao->descricao == 'ERRO') {
            DB::beginTransaction();
            try {
                $situacao = Codigoitem::wherehas('codigo', function ($q) {
                    $q->where('descricao', '=', 'Situações Minuta Empenho');
                })
                    ->where('descricao', 'EM PROCESSAMENTO')
                    ->first();
                $minuta->situacao_id = $situacao->id;
                $minuta->save();

                $modSfOrcEmpenhoDados = SfOrcEmpenhoDados::where('minutaempenho_id', $id)
                    ->where('alteracao', false)
                    ->latest()
                    ->first();

                $execsiafi = new Execsiafi();
                $nonce = $execsiafi->createNonce($modSfOrcEmpenhoDados->ugemitente, $modSfOrcEmpenhoDados->id, 'ORCAMENTARIO');
                $modSfOrcEmpenhoDados->sfnonce_id = $nonce;
                $modSfOrcEmpenhoDados->situacao = 'EM PROCESSAMENTO';
                $modSfOrcEmpenhoDados->save();

                DB::commit();
                Alert::success('Situação da minuta alterada com sucesso!')->flash();
                return redirect('/empenho/minuta');
            } catch (Exception $exc) {
                DB::rollback();
            }
        } else {
            Alert::warning('Situação da minuta não pode ser alterada!')->flash();
            return redirect('/empenho/minuta');
        }
    }

    public function deletarMinuta($id)
    {
        $minuta = MinutaEmpenho::find($id);

        if ($minuta->situacao_descricao == 'ERRO' || $minuta->situacao_descricao == 'EM ANDAMENTO') {
            DB::beginTransaction();
            try {
                if ($minuta->empenho_por === 'Compra') {
                    $cime = $minuta->compraItemMinutaEmpenho();
                    $cime_deletar = $cime->get();
                    $cime->delete();

                    foreach ($cime_deletar as $item) {
                        $compraItemUnidade = CompraItemUnidade::where('compra_item_id', $item->compra_item_id)
                            ->where('unidade_id', session('user_ug_id'))
                            ->first();
                        $compraItemUnidade->quantidade_saldo =
                            $this->retornaSaldoAtualizado($item->compra_item_id)->saldo;
                        $compraItemUnidade->save();
                    }
                    $minuta->forceDelete();
                    DB::commit();
                    Alert::success('Minuta Deletada com sucesso!')->flash();
                    return redirect($this->crud->route);
                }
                // Deletar minuta do contrato
                $minuta->forceDelete();
                DB::commit();

                Alert::success('Minuta Deletada com sucesso!')->flash();
                return redirect($this->crud->route);
            } catch (Exception $exc) {
                DB::rollback();
                Alert::error('Erro! Tente novamente mais tarde!')->flash();
                return redirect($this->crud->route);
            }

            Alert::success('Situação da minuta alterada com sucesso!')->flash();
            return redirect('/empenho/minuta');
        }
        Alert::warning('Operação não permitida!')->flash();
        return redirect($this->crud->route);
    }
}
