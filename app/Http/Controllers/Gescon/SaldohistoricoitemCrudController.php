<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Contratohistorico;
use App\Models\Contratoitem;
use App\Models\Saldohistoricoitem;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SaldohistoricoitemRequest as StoreRequest;
use App\Http\Requests\SaldohistoricoitemRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use function foo\func;

/**
 * Class SaldohistoricoitemCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SaldohistoricoitemCrudController extends CrudController
{
    public function setup()
    {
        $contratohistorico_id = \Route::current()->parameter('contratohistorico_id');

        $contratohistorico = Contratohistorico::where('id', '=', $contratohistorico_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'))->first();
        if (!$contratohistorico) {
            abort('403', config('app.erro_permissao'));
        }

        session(['saldohistoricoitens_contratohistorico_id' => $contratohistorico->id]);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Saldohistoricoitem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contratohistorico/' . $contratohistorico_id . '/itens');
        $this->crud->setEntityNameStrings('Contrato Histórico Item', 'Contrato Histórico Itens');
        $this->crud->addClause('where', 'saldoable_type', '=', 'App\Models\Contratohistorico');
        $this->crud->addClause('where', 'saldoable_id', '=', $contratohistorico_id);


        (backpack_user()->can('saldohistoricoitens_carregaritens')) ? $this->crud->addButtonFromView('top', 'carregaritens',
            'carregaritens', 'end') : null;

        $this->crud->addButtonFromView('top', 'voltar', 'voltar', 'end');

        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');

        (backpack_user()->can('saldohistoricoitens_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('saldohistoricoitens_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('saldohistoricoitens_deletar')) ? $this->crud->allowAccess('delete') : null;


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);


        $itens = DB::table('contratoitens')
            ->leftJoin('catmatseritens', 'contratoitens.catmatseritem_id', '=', 'catmatseritens.id')
            ->where('contrato_id', $contratohistorico->contrato->id)
            ->select(DB::raw("CONCAT(catmatseritens.codigo_siasg,' - ',catmatseritens.descricao) AS nome"), 'contratoitens.id AS num')
            ->pluck('nome', 'num')
            ->toArray();

        $campos = $this->Campos($contratohistorico->contrato->id, $itens);
        $this->crud->addFields($campos);


        // add asterisk for fields that are required in SaldohistoricoitemRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Campos($contratohistorico_id, $itens)
    {

        $campos = [
            [   // Hidden
                'name' => 'saldoable_type',
                'type' => 'hidden',
                'default' => 'App\Models\Contratohistorico',
            ],
            [   // Hidden
                'name' => 'saldoable_id',
                'type' => 'hidden',
                'default' => $contratohistorico_id,
            ],
            [
                // select_from_array
                'name' => 'contratoitem_id',
                'label' => "Item",
                'type' => 'select_from_array',
                'options' => $itens,
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [   // Number
                'name' => 'quantidade',
                'label' => 'Quantidade',
                'type' => 'number',
                // optionals
//                'attributes' => [
//                    'id' => 'valorunitario',
//                ], // allow decimals
//                'prefix' => "R$",
            ],
            [   // Number
                'name' => 'valorunitario',
                'label' => 'Valor Unitário',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'valorunitario',
                ], // allow decimals
                'prefix' => "R$",
            ],
            [   // Number
                'name' => 'valortotal',
                'label' => 'Valor Total',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'valortotal',
                ], // allow decimals
                'prefix' => "R$",
            ],

        ];

        return $campos;
    }


    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getContrato',
                'label' => 'Número Contrato', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [
                'name' => 'getTipoItem',
                'label' => 'Tipo Item', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoItem', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [
                'name' => 'getContratoItem',
                'label' => 'Item', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContratoItem', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [
                'name' => 'getDescricaoComplementar',
                'label' => 'Descriçao Complementar',
                'type' => 'model_function',
                'function_name' => 'getDescricaoComplementar', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'quantidade',
                'label' => 'Quantidade', // Table column heading
                'type' => 'number',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],
            [
                'name' => 'formatValorUnitarioItem',
                'label' => 'Valor Unitário', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatValorUnitarioItem', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'formatValorTotalItem',
                'label' => 'Valor Total', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatValorTotalItem', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
        ];

        return $colunas;
    }


    public function store(StoreRequest $request)
    {
        $valorunitario = str_replace(',', '.', str_replace('.', '', $request->input('valorunitario')));
        $request->request->set('valorunitario', number_format(floatval($valorunitario), 2, '.', ''));

        $valortotal = str_replace(',', '.', str_replace('.', '', $request->input('valortotal')));
        $request->request->set('valortotal', number_format(floatval($valortotal), 2, '.', ''));

        $saldoable_id = $request->input('saldoable_id');
        $saldoable_type = $request->input('saldoable_type');


        $contratohistorico = Contratohistorico::find($saldoable_id);
        $soma_cadastrados = Saldohistoricoitem::where('saldoable_id',$contratohistorico->id)
                ->where('saldoable_type',$saldoable_type)
                ->sum('valortotal') ?? 0;
        $vlr_total = number_format(floatval($valortotal), 2, '.', '');

        if(($soma_cadastrados+$vlr_total) > $contratohistorico->valor_global){

            \Alert::error('O "Valor Total" Extrapola o "Valor Global" do Contrato Histórico!')->flash();

            return redirect()->back();
        }

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $valorunitario = str_replace(',', '.', str_replace('.', '', $request->input('valorunitario')));
        $request->request->set('valorunitario', number_format(floatval($valorunitario), 2, '.', ''));

        $valortotal = str_replace(',', '.', str_replace('.', '', $request->input('valortotal')));
        $request->request->set('valortotal', number_format(floatval($valortotal), 2, '.', ''));

        $saldoable_id = $request->input('saldoable_id');
        $saldoable_type = $request->input('saldoable_type');


        $contratohistorico = Contratohistorico::find($saldoable_id);
        $soma_cadastrados = Saldohistoricoitem::where('saldoable_id',$contratohistorico->id)
                ->where('saldoable_type',$saldoable_type)
                ->sum('valortotal') ?? 0;
        $vlr_total = number_format(floatval($valortotal), 2, '.', '');

        if(($soma_cadastrados+$vlr_total) > $contratohistorico->valor_global){

            \Alert::error('O "Valor Total" Extrapola o "Valor Global" do Contrato Histórico!')->flash();

            return redirect()->back();
        }

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function carregarItens(string $tipo, int $contratohistorico_id)
    {
        $contratohistorico = Contratohistorico::find($contratohistorico_id);


        if ($tipo == 'inicial') {

            $contratohistorico1 = Contratohistorico::whereHas('tipo', function ($query) {
                $query->where('descricao', '<>', 'Termo Aditivo')
                    ->where('descricao', '<>', 'Termo de Apostilamento');
            })
                ->where('contrato_id', $contratohistorico->contrato_id)
                ->first();

            $contratoitens = $this->buscaSaldoInicial($contratohistorico1);

        } else {

            $contratoitens = $this->buscaSaldoAtual($contratohistorico);

            $contratoitens = Contratoitem::where('contrato_id', $contratohistorico->contrato_id)
                ->get();
        }

        $tiposaldo = $this->buscaTipoSaldo($contratohistorico);


        if (!count($contratoitens)) {
            \Alert::error('Esse contrato não possui itens!')->flash();
            return redirect('/gescon/contratohistorico/' . $contratohistorico->id . '/itens');
        }

        foreach ($contratoitens as $contratoitem) {

            if (is_object($contratoitem)) {
                $contratoitem = $contratoitem->toArray();
            }

            $saldohistoricoitem = $this->buscaSaldoHistoricoItem($contratohistorico, $contratoitem, $tiposaldo);

            if (!isset($saldohistoricoitem->id)) {
                $saldohistoricoitem = $contratohistorico->saldosItens()->create([
                    'contratoitem_id' => ($tipo == 'inicial') ? $contratoitem['contratoitem_id'] : $contratoitem['id'],
                    'tiposaldo_id' => $tiposaldo->id,
                    'quantidade' => $contratoitem['quantidade'],
                    'valorunitario' => $contratoitem['valorunitario'],
                    'valortotal' => $contratoitem['valortotal']
                ]);
            }

        }

        \Alert::success('Itens carregados com Sucesso!')->flash();

        return redirect('/gescon/contratohistorico/' . $contratohistorico->id . '/itens');

    }

    private function buscaSaldoHistoricoItem(Contratohistorico $contratohistorico, array $contratoitem, Codigoitem $codigoitem)
    {
        $saldohistoricoitem = $contratohistorico->saldosItens()->where([
            'contratoitem_id' => $contratoitem['id'],
            'tiposaldo_id' => $codigoitem->id
        ])
            ->first();

        return $saldohistoricoitem;
    }

    private function buscaTipoSaldo(Contratohistorico $contratohistorico)
    {
        if ($contratohistorico->tipo->descricao == 'Termo Aditivo' or $contratohistorico->tipo->descricao == 'Termo de Apostilamento') {
            $codigoitem = Codigoitem::whereHas('codigo', function ($query) {
                $query->where('descricao', 'Tipo Saldo Itens');
            })
                ->where('descricao', 'Saldo Alteracao Contrato Historico')
                ->first();
        } else {
            $codigoitem = Codigoitem::whereHas('codigo', function ($query) {
                $query->where('descricao', 'Tipo Saldo Itens');
            })
                ->where('descricao', 'Saldo Inicial Contrato Historico')
                ->first();
        }

        return $codigoitem;
    }

    private function buscaSaldoInicial(Contratohistorico $contratohistorico)
    {
        $saldoinicial = $contratohistorico->saldosItens->toArray();

        return $saldoinicial;
    }

    private function buscaSaldoAtual(Contratohistorico $contratohistorico)
    {
        $contratoitens = Contratoitem::where('contrato_id', $contratohistorico->contrato_id)
            ->get();

        return $contratoitens->toArray();
    }


}
