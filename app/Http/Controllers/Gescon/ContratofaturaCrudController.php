<?php

namespace App\Http\Controllers\Gescon;

use App\Http\Controllers\Apropriacao\FaturaController;
use App\Models\Contrato;
use App\Models\Empenho;
use App\Models\Tipolistafatura;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratofaturaRequest as StoreRequest;
use App\Http\Requests\ContratofaturaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use DB;

/**
 * Class ContratofaturaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratofaturaCrudController extends CrudController
{
    public function setup()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');

        $contrato = Contrato::where('id', '=', $contrato_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'))->first();
        if (!$contrato) {
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setModel('App\Models\Contratofatura');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/meus-contratos/' . $contrato_id . '/faturas');
        $this->crud->setEntityNameStrings('Fatura do Contrato', 'Faturas - Contrato');
        $this->crud->addClause('join', 'tipolistafatura', 'tipolistafatura.id', '=', 'contratofaturas.tipolistafatura_id');
        $this->crud->addClause('select', 'contratofaturas.*');

        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addButtonFromView('top', 'voltar', 'voltarmeucontrato', 'end');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        $conresp = $contrato->whereHas('responsaveis', function ($query) {
            $query->whereHas('user', function ($query) {
                $query->where('id', '=', backpack_user()->id);
            })->where('situacao', '=', true);
        })->where('id', '=', $contrato_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'))->first();

        if ($conresp) {
            $this->crud->AllowAccess('create');
            $this->crud->AllowAccess('update');
            $this->crud->AllowAccess('delete');
        }

        $this->crud->enableBulkActions();

        $this->crud->addButton(
            'line',
            'apropriacao_fatura',
            'view',
            'crud::buttons.apropriacao_fatura',
            'end'
        );

        $this->crud->addButton(
            'bottom',
            'apropriacao_fatura',
            'view',
            'crud::buttons.bulk_apropriacao_fatura'
        );

        $this->crud->addButton(
            'bottom',
            'del_apropriacao_fatura',
            'view',
            'crud::buttons.bulk_delete'
        );

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $con = $contrato->where('id', '=', $contrato_id)
            ->pluck('numero', 'id')
            ->toArray();

        $tipolistafatura = Tipolistafatura::where('situacao', true)
            ->orderBy('nome', 'ASC')
            ->pluck('nome', 'id')
            ->toArray();

        $campos = $this->Campos($con, $tipolistafatura, $contrato_id);
        $this->crud->addFields($campos);

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getContrato',
                'label' => 'Contrato',
                'type' => 'model_function',
                'function_name' => 'getContrato',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getTipoListaFatura',
                'label' => 'Tipo Lista Fatura',
                'type' => 'model_function',
                'function_name' => 'getTipoListaFatura',
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('tipolistafatura.nome', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'getJustificativaFatura',
                'label' => 'Justificativa',
                'type' => 'model_function',
                'function_name' => 'getJustificativaFatura',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getSfpadrao',
                'label' => 'Doc. Origem Siafi',
                'type' => 'model_function',
                'function_name' => 'getSfpadrao',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'numero',
                'label' => 'Número',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'emissao',
                'label' => 'Dt. Emissão',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'vencimento',
                'label' => 'Dt. Vencimento',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatValor',
                'label' => 'Valor',
                'type' => 'model_function',
                'function_name' => 'formatValor',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatJuros',
                'label' => 'Juros',
                'type' => 'model_function',
                'function_name' => 'formatJuros',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatMulta',
                'label' => 'Multa',
                'type' => 'model_function',
                'function_name' => 'formatMulta',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatGlosa',
                'label' => 'Glosa',
                'type' => 'model_function',
                'function_name' => 'formatGlosa',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatValorLiquido',
                'label' => 'Valor Líquido a pagar',
                'type' => 'model_function',
                'function_name' => 'formatValorLiquido',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'processo',
                'label' => 'Processo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'protocolo',
                'label' => 'Dt. Protocolo',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'ateste',
                'label' => 'Dt. Ateste',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'prazo',
                'label' => 'Dt. Prazo Pagto.',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'empenhos',
                'label' => 'Empenhos',
                'type' => 'select_multiple',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'entity' => 'empenhos',
                'attribute' => 'numero',
                'model' => Empenho::class,
                'pivot' => true,
            ],
            [
                'name' => 'repactuacao',
                'label' => 'Repactuação',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'options' => [0 => 'Não', 1 => 'Sim']
            ],
            [
                'name' => 'infcomplementar',
                'label' => 'Informações Complementares',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'mesref',
                'label' => 'Mês Referência',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'anoref',
                'label' => 'Ano Referência',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'select_from_array',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'options' => config('app.situacao_fatura')
            ],
        ];

        return $colunas;
    }

    public function Campos($contrato, $tipolistafatura, $contrato_id)
    {
        $con = Contrato::find($contrato_id);

        $campos = [
            [
                'name' => 'contrato_id',
                'label' => "Número do instrumento",
                'type' => 'select_from_array',
                'options' => $contrato,
                'allows_null' => false,
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ],
                'tab' => 'Dados Fatura',
            ],
            [
                'name' => 'tipolistafatura_id',
                'label' => "Tipo Lista Fatura",
                'type' => 'select2_from_array',
                'options' => $tipolistafatura,
                'allows_null' => true,
                'tab' => 'Dados Fatura',
            ],
            [
                'name' => 'numero',
                'label' => "Número",
                'type' => 'text',
                'attributes' => [
                    'maxlength' => '17',
                    'onkeyup' => "maiuscula(this)",
                ],
                'tab' => 'Dados Fatura',
            ],
            [
                'name' => 'emissao',
                'label' => "Dt. Emissão",
                'type' => 'date',
                'tab' => 'Dados Fatura',
            ],
            [
                'name' => 'vencimento',
                'label' => "Dt. Vencimento",
                'type' => 'date',
                'tab' => 'Dados Fatura',
            ],

            [
                'name' => 'valor',
                'label' => 'Valor',
                'type' => 'money_fatura',
                'attributes' => [
                    'id' => 'valor',
                ],
                'prefix' => "R$",
                'tab' => 'Dados Fatura',
            ],
            [
                'name' => 'juros',
                'label' => 'Juros',
                'type' => 'money_fatura',
                'attributes' => [
                    'id' => 'juros',
                ],
                'prefix' => "R$",
                'tab' => 'Dados Fatura',
            ],
            [
                'name' => 'multa',
                'label' => 'Multa',
                'type' => 'money_fatura',
                'attributes' => [
                    'id' => 'multa',
                ],
                'prefix' => "R$",
                'tab' => 'Dados Fatura',
            ],
            [
                'name' => 'glosa',
                'label' => 'Glosa',
                'type' => 'money_fatura',
                'attributes' => [
                    'id' => 'glosa',
                ],
                'prefix' => "R$",
                'tab' => 'Dados Fatura',
            ],
            [
                'name' => 'processo',
                'label' => "Processo",
                'type' => 'numprocesso',
                'tab' => 'Outras Informações',
            ],
            [
                'name' => 'protocolo',
                'label' => "Dt. Protocolo",
                'type' => 'date',
                'tab' => 'Outras Informações',
            ],
            [
                'name' => 'ateste',
                'label' => "Dt. Ateste",
                'type' => 'date',
                'tab' => 'Outras Informações',
            ],
            [
                'name' => 'repactuacao',
                'label' => "Repactuação?",
                'type' => 'radio',
                'options' => [0 => 'Não', 1 => 'Sim'],
                'default' => 0,
                'inline' => true,
                'tab' => 'Outras Informações',
            ],
            [
                'name' => 'infcomplementar',
                'label' => "Informações Complementares",
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)",
                ],
                'tab' => 'Outras Informações',
            ],
            [
                'name' => 'mesref',
                'label' => "Mês Referência",
                'type' => 'select2_from_array',
                'options' => config('app.meses_referencia_fatura'),
                'allows_null' => false,
                'tab' => 'Outras Informações',
            ],
            [
                'name' => 'anoref',
                'label' => "Ano Referência",
                'type' => 'select2_from_array',
                'options' => config('app.anos_referencia_fatura'),
                'default' => date('Y'),
                'allows_null' => false,
                'tab' => 'Outras Informações',
            ],
            [
                'label' => "Empenhos",
                'type' => 'select2_multiple',
                'name' => 'empenhos',
                'entity' => 'empenhos',
                'attribute' => 'numero',
                'model' => "App\Models\Empenho",
                'pivot' => true,
                'options' => (function ($query) use ($con) {
                    return $query->orderBy('numero', 'ASC')
                        ->select(['id', DB::raw('case
                           when left(numero, 4) = date_part(\'year\', current_date)::text
                               then numero || \' - Saldo a Liquidar: R$ \' || aliquidar
                           else numero || \' - Saldo RP  a Liquidar: R$ \' || rpaliquidar
                           end as numero')
                        ])
                        ->where('unidade_id', session()->get('user_ug_id'))
                        ->where('fornecedor_id', $con->fornecedor_id)
                        ->get();
                }),
                'tab' => 'Outras Informações',
            ],
            [
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select_from_array',
                'options' => config('app.situacao_fatura'),
                'default' => 'PEN',
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ],
                'allows_null' => false,
                'tab' => 'Outras Informações',
            ],
        ];

        return $campos;
    }

    public function store(StoreRequest $request)
    {
        $v = number_format(floatval(str_replace(',', '.', str_replace('.', '', $request->input('valor')))), 2, '.', '');
        $j = number_format(floatval(str_replace(',', '.', str_replace('.', '', $request->input('juros')))), 2, '.', '');
        $m = number_format(floatval(str_replace(',', '.', str_replace('.', '', $request->input('multa')))), 2, '.', '');
        $g = number_format(floatval(str_replace(',', '.', str_replace('.', '', $request->input('glosa')))), 2, '.', '');
        $vl = number_format(floatval($v + $j + $m - $g), 2, '.', '');

        if ($request->input('vencimento')) {
            $request->request->set('prazo', $request->input('vencimento'));
        } else {
            $tipolistafatura = $request->input('tipolistafatura_id');

            if ($tipolistafatura == '5') {
                $ateste = $request->input('ateste');
                $request->request->set('prazo', date('Y-m-d', strtotime("+5 days", strtotime($ateste))));
            } else {
                $ateste = $request->input('ateste');
                $request->request->set('prazo', date('Y-m-d', strtotime("+30 days", strtotime($ateste))));
            }
        }

        $request->request->set('valor', $v);
        $request->request->set('juros', $j);
        $request->request->set('multa', $m);
        $request->request->set('glosa', $g);
        $request->request->set('valorliquido', $vl);

        $request->request->set('situacao', 'PEN');

        $redirect_location = parent::storeCrud($request);

        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $contrato_id = $request->input('contrato_id');
        $situacao = $request->input('situacao');

        if ($situacao == 'PEN') {
            $v = number_format(floatval(str_replace(',', '.', str_replace('.', '', $request->input('valor')))), 2, '.', '');
            $j = number_format(floatval(str_replace(',', '.', str_replace('.', '', $request->input('juros')))), 2, '.', '');
            $m = number_format(floatval(str_replace(',', '.', str_replace('.', '', $request->input('multa')))), 2, '.', '');
            $g = number_format(floatval(str_replace(',', '.', str_replace('.', '', $request->input('glosa')))), 2, '.', '');
            $vl = number_format(floatval($v + $j + $m - $g), 2, '.', '');

            if ($request->input('vencimento')) {
                $request->request->set('prazo', $request->input('vencimento'));
            } else {
                $tipolistafatura = $request->input('tipolistafatura_id');

                if ($tipolistafatura == '5') {
                    $ateste = $request->input('ateste');
                    $request->request->set('prazo', date('Y-m-d', strtotime("+5 days", strtotime($ateste))));
                } else {
                    $ateste = $request->input('ateste');
                    $request->request->set('prazo', date('Y-m-d', strtotime("+30 days", strtotime($ateste))));
                }
            }

            $request->request->set('valor', $v);
            $request->request->set('juros', $j);
            $request->request->set('multa', $m);
            $request->request->set('glosa', $g);
            $request->request->set('valorliquido', $vl);

            $redirect_location = parent::updateCrud($request);
            return $redirect_location;

        } else {
            \Alert::error('Essa Fatura não pode ser alterada!')->flash();
            return redirect('/gescon/meus-contratos/' . $contrato_id . '/faturas');
        }
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('contrato_id');
        $this->crud->removeColumn('tipolistafatura_id');
        $this->crud->removeColumn('justificativafatura_id');
        $this->crud->removeColumn('sfpadrao_id');
        $this->crud->removeColumn('valor');
        $this->crud->removeColumn('juros');
        $this->crud->removeColumn('multa');
        $this->crud->removeColumn('glosa');
        $this->crud->removeColumn('valorliquido');

        return $content;
    }
}
