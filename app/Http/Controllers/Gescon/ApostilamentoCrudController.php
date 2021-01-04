<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Catmatseritem;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Contratohistorico;
use App\Models\Contratoitem;
use App\Models\Fornecedor;
use App\Models\Saldohistoricoitem;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ApostilamentoRequest as StoreRequest;
use App\Http\Requests\ApostilamentoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\Formatador;
use Route;

/**
 * Class ApostilamentoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ApostilamentoCrudController extends CrudController
{
    use Formatador;

    public function setup()
    {
        $contrato_id = Route::current()->parameter('contrato_id');
        $apostilamento_id = Route::current()->parameter('apostilamento');

        $contrato = Contrato::where('id', '=', $contrato_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'))->first();
        if (!$contrato) {
            abort('403', config('app.erro_permissao'));
        }

        $tps = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
//            ->where('descricao', '=', 'Termo Aditivo')
            ->Where('descricao', '=', 'Termo de Apostilamento')
            ->pluck('id')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratohistorico');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/' . $contrato_id . '/apostilamentos');
        $this->crud->setEntityNameStrings('Termo de Apostilamento', 'Termos de Apostilamentos');
        $this->crud->setCreateContentClass('col-md-12');
        $this->crud->setEditContentClass('col-md-12');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratohistorico.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratohistorico.unidade_id');
        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));
        $this->crud->addClause('select', 'contratohistorico.*');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        foreach ($tps as $t) {
            $this->crud->addClause('where', 'tipo_id', '=', $t);
        }
        $this->crud->orderBy('data_assinatura', 'asc');

        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
//        $this->crud->addButtonFromView('line', 'morecontratohistorico', 'morecontratohistorico', 'end');
        $this->crud->enableExportButtons();
//        $this->crud->disableResponsiveTable();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('contratoapostilamento_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contratoapostilamento_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contratoapostilamento_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $unidade = [session()->get('user_ug_id') => session()->get('user_ug')];

        $campos = $this->Campos($contrato_id, $unidade, $apostilamento_id);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in ApostilamentoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'observacao',
                'label' => 'Objeto do Apostilamento',
                'type' => 'text',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'numero',
                'label' => 'Número Apostilamento',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'data_inicio_novo_valor',
                'label' => 'Início Novo Valor',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatNovoVlrGlobalHistorico',
                'label' => 'Novo Valor Global', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatNovoVlrGlobalHistorico', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatNovoVlrParcelaHistorico',
                'label' => 'Novo Valor Parcela', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatNovoVlrParcelaHistorico', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'retroativo',
                'label' => 'Retroativo',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => [0 => 'Não', 1 => 'Sim']
            ],
            [
                'name' => 'getRetroativoMesAnoReferenciaDe',
                'label' => 'Retroativo Mês Ref. De', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getRetroativoMesAnoReferenciaDe', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getRetroativoMesAnoReferenciaAte',
                'label' => 'Retroativo Mês Ref. Até', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getRetroativoMesAnoReferenciaAte', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'retroativo_vencimento',
                'label' => 'Vencimento Retroativo',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRetroativoValor',
                'label' => 'Valor Retroativo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRetroativoValor', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
        ];

        return $colunas;

    }

    public function Campos($contrato_id, $unidade, $apostilamento_id)
    {
        $contrato = Contrato::find($contrato_id);

        $tipo =   Codigoitem::find($contrato->tipo_id);

        $campos = [
            [   // Hidden
                'name' => 'tipo_contrato',
                'type' => 'hidden',
                'default' => $tipo->descricao,
                'attributes' => ['id' => 'tipo_contrato']
            ],
            [   // Hidden
                'name' => 'receita_despesa',
                'type' => 'hidden',
                'default' => $contrato->receita_despesa,
            ],
            [   // Hidden
                'name' => 'apostilamento_id',
                'type' => 'hidden',
                'default' => $apostilamento_id,
            ],
            [   // Hidden
                'name' => 'contrato_id',
                'type' => 'hidden',
                'default' => $contrato->id,
            ],
            [   // Hidden
                'name' => 'tipo_id',
                'type' => 'hidden',
                'default' => $contrato->tipo_id,
            ],
            [   // Hidden
                'name' => 'fornecedor_id',
                'type' => 'hidden',
                'default' => $contrato->fornecedor_id,
            ],
            [   // Date
                'name' => 'vigencia_inicio',
                'type' => 'hidden',
                'default' => $contrato->vigencia_inicio,
            ],
            [   // Date
                'name' => 'vigencia_fim',
                'type' => 'hidden',
                'default' => $contrato->vigencia_fim,
            ],
            [
                'name' => 'numero',
                'label' => 'Número Termo Apostilamento',
                'type' => 'numcontrato',
                'tab' => 'Dados Gerais',

            ],
            [   // Date
                'name' => 'data_publicacao',
                'label' => 'Data da Publicação',
                'type' => 'date',
                'tab' => 'Dados Gerais',
            ],
            [
                'name' => 'observacao',
                'label' => 'Objeto do Apostilamento',
                'type' => 'textarea',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ],
                'tab' => 'Dados Gerais',
            ],
            [ // select_from_array
                'name' => 'unidade_id',
                'label' => "Unidade Gestora",
                'type' => 'select_from_array',
                'options' => $unidade,
                'allows_null' => false,
//                'attributes' => [
//                    'disabled' => 'disabled',
//                ],
                'tab' => 'Dados Gerais',
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                'name' => 'itens',
                'type' => 'itens_contrato_apostilamento_list',
                'tab' => 'Itens do contrato',
            ],
            [
                'label' => "adicionaCampoRecuperaGridItens",
                'type' => "hidden",
                'name' => 'adicionaCampoRecuperaGridItens',
                'default' => "{{old('name')}}",
                'tab' => 'Itens do contrato'
            ],
            [   // Date
                'name' => 'data_assinatura',
                'label' => 'Data Assinatura Apostilamento',
                'type' => 'date',
                'tab' => 'Vigência / Valores',
            ],
            [   // Date
                'name' => 'data_inicio_novo_valor',
                'label' => 'Data Início Novo Valor',
                'type' => 'date',
//                'default' => date('Y-m-d'),
                'tab' => 'Vigência / Valores',
            ],
            [   // Number
                'name' => 'novo_valor_global',
                'label' => 'Novo Valor Global',
                'type' => 'number',
                // optionals
                'attributes' => [
                    'id' => 'novo_valor_global',
                    'step' => '0.0001',
                    'readOnly' => 'readOnly'
                ], // allow decimals
                'prefix' => "R$",
                'default' => $contrato->valor_global,
                'tab' => 'Vigência / Valores',
                // 'suffix' => ".00",
            ],
            [   // Number
                'name' => 'novo_num_parcelas',
                'label' => 'Novo Núm. Parcelas',
                'type' => 'number',
                // optionals
//                'attributes' => [
//                    "step" => "any",
//                    "min" => '1',
//                ], // allow decimals
                'default' => $contrato->num_parcelas,
//                'prefix' => "R$",
                'tab' => 'Vigência / Valores',
                // 'suffix' => ".00",
            ],
            [   // Number
                'name' => 'novo_valor_parcela',
                'label' => 'Novo Valor Parcela',
                'type' => 'number',
                // optionals
                'attributes' => [
                    'id' => 'novo_valor_parcela',
                    'step' => '0.0001',
                    'readOnly' => 'readOnly'
                ], // allow decimals
                'prefix' => "R$",
                'default' => $contrato->valor_parcela,
                'tab' => 'Vigência / Valores',
                // 'suffix' => ".00",
            ],
            [ // select_from_array
                'name' => 'retroativo',
                'label' => "Retroativo?",
                'type' => 'radio',
                'options' => [0 => 'Não', 1 => 'Sim'],
                'default' => 0,
                'inline' => true,
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_mesref_de',
                'label' => "Mês Referência De",
                'type' => 'select2_from_array',
                'options' => config('app.meses_referencia_fatura'),
                'allows_null' => true,
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_anoref_de',
                'label' => "Ano Referência De",
                'type' => 'select2_from_array',
                'options' => config('app.anos_referencia_fatura'),
//                'default'    => date('Y'),
                'allows_null' => true,
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_mesref_ate',
                'label' => "Mês Referência Até",
                'type' => 'select2_from_array',
                'options' => config('app.meses_referencia_fatura'),
                'allows_null' => true,
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_anoref_ate',
                'label' => "Ano Referência Até",
                'type' => 'select2_from_array',
                'options' => config('app.anos_referencia_fatura'),
//                'default'    => date('Y'),
                'allows_null' => true,
                'tab' => 'Retroativo',
            ],
            [   // Date
                'name' => 'retroativo_vencimento',
                'label' => 'Vencimento Retroativo',
                'type' => 'date',
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_soma_subtrai',
                'label' => "Soma ou Subtrai?",
                'type' => 'radio',
                'options' => [1 => 'Soma', 0 => 'Subtrai'],
                'default' => 1,
                'inline' => true,
                'tab' => 'Retroativo',
            ],
            [   // Number
                'name' => 'retroativo_valor',
                'label' => 'Valor Retroativo',
                'type' => 'money',
                // optionals
                'attributes' => [
                    'id' => 'retroativo_valor',
                ], // allow decimals
                'prefix' => "R$",
                'tab' => 'Retroativo',
                // 'suffix' => ".00",
            ],

        ];

        return $campos;
    }

    public function store(StoreRequest $request)
    {
        $request->input('novo_valor_parcela');
        $novo_valor_parcela = $request->input('novo_valor_parcela');
        $request->request->set('novo_valor_parcela', $novo_valor_parcela);
        $request->request->set('valor_parcela', $novo_valor_parcela);

        $novo_num_parcelas = $request->input('novo_num_parcelas');
        $request->request->set('num_parcelas', $novo_num_parcelas);

        $novo_valor_global = $request->input('novo_valor_global');
        $request->request->set('novo_valor_global', $novo_valor_global);
        $request->request->set('valor_global', $novo_valor_global);
        $request->request->set('valor_inicial', $novo_valor_global);

        $soma_subtrai = $request->input('retroativo_soma_subtrai');

        $retroativo_valor = str_replace(',', '.', str_replace('.', '', $request->input('retroativo_valor')));

        if ($soma_subtrai == '0') {
            $retroativo_valor = number_format(floatval($retroativo_valor), 2, '.', '') * -1;
        } else {
            $retroativo_valor = number_format(floatval($retroativo_valor), 2, '.', '');
        }

        $tipo_id = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Tipo de Contrato');
        })
            ->where('descricao', 'Termo de Apostilamento')
            ->first();

        $request->request->set('tipo_id', $tipo_id->id);


        $request->request->set('retroativo_valor', $retroativo_valor);
        DB::beginTransaction();
        try {
            // your additional operations before save here

            $redirect_location = parent::storeCrud($request);
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry

            // altera os itens do contrato
            if (!empty($request->get('qtd_item'))) {
                $this->criarSaldoHistoricoItens($request->all(), $this->crud->entry);
            }
            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
            dd($exc);
        }
        // your additional operations after save here

        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $novo_valor_parcela = $request->input('novo_valor_parcela');
        $request->request->set('novo_valor_parcela', $novo_valor_parcela);
        $request->request->set('valor_parcela', $novo_valor_parcela);

        $novo_num_parcelas = $request->input('novo_num_parcelas');
        $request->request->set('num_parcelas', $novo_num_parcelas);

        $novo_valor_global = $request->input('novo_valor_global');
        $request->request->set('novo_valor_global', $novo_valor_global);
        $request->request->set('valor_global', $novo_valor_global);
        $request->request->set('valor_inicial', $novo_valor_global);

        $soma_subtrai = $request->input('retroativo_soma_subtrai');

        $retroativo_valor = str_replace(',', '.', str_replace('.', '', $request->input('retroativo_valor')));

        if ($soma_subtrai == '0') {
            $retroativo_valor = number_format(floatval($retroativo_valor), 2, '.', '') * -1;
        } else {
            $retroativo_valor = number_format(floatval($retroativo_valor), 2, '.', '');
        }

        $tipo_id = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Tipo de Contrato');
        })
            ->where('descricao', 'Termo de Apostilamento')
            ->first();

        $request->request->set('tipo_id', $tipo_id->id);


        $request->request->set('retroativo_valor', $retroativo_valor);

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry

        if (!empty($request->get('qtd_item'))) {
            $this->alterarItensContrato($request->all(), $this->crud->entry);
        }

        return $redirect_location;
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'retroativo_mesref_de',
            'retroativo_anoref_de',
            'retroativo_mesref_ate',
            'retroativo_anoref_ate',
            'retroativo_valor',
            'fornecedor_id',
            'tipo_id',
            'categoria_id',
            'unidade_id',
            'fundamento_legal',
            'modalidade_id',
            'licitacao_numero',
            'data_assinatura',
            'data_publicacao',
            'valor_inicial',
            'valor_global',
            'valor_parcela',
            'valor_acumulado',
            'situacao_siasg',
            'contrato_id',
            'receita_despesa',
            'processo',
            'objeto',
            'novo_valor_global',
            'novo_valor_parcela',
            'novo_num_parcelas',
            'data_inicio_novo_valor',
        ]);
        return $content;
    }

    public function alterarItensContrato($request)
    {
        DB::beginTransaction();
        try {
            foreach ($request['qtd_item'] as $key => $qtd) {
                if ($request['item_id'][$key] !== 'undefined') {
                    $saldoHistoricoIten = Saldohistoricoitem::find($request['item_id'][$key]);
                    $saldoHistoricoIten->valorunitario = $request['vl_unit'][$key];
                    $saldoHistoricoIten->valortotal = $request['vl_total'][$key];
                    $saldoHistoricoIten->save();
                }
            }
            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
            dd($exc);
        }
    }

    private function criarSaldoHistoricoItens($request, Contratohistorico $contratoHistorico)
    {
        $codigoitem = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Tipo Saldo Itens');
        })
            ->where('descricao', 'Saldo Inicial Contrato Historico')
            ->first();

        foreach ($request['qtd_item'] as $key => $qtd) {
            $saldoHistoricoIten = new Saldohistoricoitem();
            $saldoHistoricoIten->saldoable_type = 'App\Models\Contratohistorico';
            $saldoHistoricoIten->saldoable_id = $contratoHistorico->id;
            $saldoHistoricoIten->contratoitem_id = $request['item_id'][$key];
            $saldoHistoricoIten->tiposaldo_id = $codigoitem->id;
            $saldoHistoricoIten->quantidade = (double)$qtd;
            $saldoHistoricoIten->valorunitario = $request['vl_unit'][$key];
            $saldoHistoricoIten->valortotal = $request['vl_total'][$key];
            $saldoHistoricoIten->periodicidade = $request['periodicidade'][$key];
            $saldoHistoricoIten->data_inicio = $request['data_inicio'][$key];
            $saldoHistoricoIten->numero_item_compra = $request['numero_item_compra'][$key];
            $saldoHistoricoIten->save();
        }
    }
}
