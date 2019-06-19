<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use App\Models\Contratocronograma;
use App\Models\Contratohistorico;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratocronogramaRequest as StoreRequest;
use App\Http\Requests\ContratocronogramaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ContratocronogramaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratocronogramaCrudController extends CrudController
{
    public function setup()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');

        $contrato = Contrato::where('id','=',$contrato_id)
            ->where('unidade_id','=',session()->get('user_ug_id'))->first();
        if(!$contrato){
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratocronograma');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/'.$contrato_id.'/cronograma');
        $this->crud->setEntityNameStrings('Cronograma Contrato', 'Cronograma Contrato');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->orderBy('vencimento', 'asc');
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('contratocronograma_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contratocronograma_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contratocronograma_deletar')) ? $this->crud->allowAccess('delete') : null;

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


        $historico = new Contratocronograma();
        $arrayhistorico = $historico->montaArrayTipoDescricaoNumeroInstrumento($contrato_id);

        $campos = $this->Campos($con, $arrayhistorico, $contrato_id);
        $this->crud->addFields($campos);


        // add asterisk for fields that are required in ContratocronogramaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getReceitaDespesa',
                'label' => 'Receita / Despesa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getReceitaDespesa', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getContratoNumero',
                'label' => 'Número Contrato', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContratoNumero', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getContratoHistorico',
                'label' => 'Instrumento - Número', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContratoHistorico', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'mesref',
                'label' => 'Mês Referência', // Table column heading
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'anoref',
                'label' => 'Ano Referência', // Table column heading
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'vencimento',
                'label' => 'Vencimento',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlr',
                'label' => 'Valor', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlr', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
        ];

        return $colunas;

    }

    public function Campos($contrato, $historico, $contrato_id)
    {

        $con = Contrato::find($contrato_id);

        $campos = [
            [ // select_from_array
                'name' => 'receita_despesa',
                'label' => "Receita / Despesa",
                'type' => 'select_from_array',
                'options' => [
                    'D' => 'Despesa',
                    'R' => 'Receita',
                ],
                'default' => $con->receita_despesa,
                'allows_null' => false,
                'attributes' => [
                    'readonly'=>'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ],
//                'attributes' => [
//                    'disabled' => 'disabled',
//                ],
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'contrato_id',
                'label' => "Contrato",
                'type' => 'select_from_array',
                'options' => $contrato,
                'allows_null' => false,
                'attributes' => [
                    'readonly'=>'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ], // chan
//                'tab' => 'Dados Fatura',
            ],
            [ // select_from_array
                'name' => 'contratohistorico_id',
                'label' => "Instrumento - Número (Histórico)",
                'type' => 'select_from_array',
                'options' => $historico,
                'allows_null' => true,
//                'attributes' => [
//                    'readonly'=>'readonly',
//                    'style' => 'pointer-events: none;touch-action: none;'
//                ], // chan
//                'tab' => 'Dados Fatura',
            ],
            [ // select_from_array
                'name' => 'mesref',
                'label' => "Mês Referência",
                'type' => 'select2_from_array',
                'options' => config('app.meses_referencia_fatura'),
                'allows_null' => false,
            ],
            [ // select_from_array
                'name' => 'anoref',
                'label' => "Ano Referência",
                'type' => 'select2_from_array',
                'options' => config('app.anos_referencia_fatura'),
                'default'    => date('Y'),
                'allows_null' => false,
            ],
            [ // select_from_array
                'name' => 'vencimento',
                'label' => "Vencimento",
                'type' => 'date',
            ],
            [   // Number
                'name' => 'valor',
                'label' => 'Valor',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'valor',
                ], // allow decimals
                'prefix' => "R$",
            ],

        ];

        return $campos;
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('contrato_id');
        $this->crud->removeColumn('contratohistorico_id');
        $this->crud->removeColumn('receita_despesa');
        $this->crud->removeColumn('valor');

        return $content;
    }

    public function store(StoreRequest $request)
    {
        $v = number_format(floatval(str_replace(',', '.', str_replace('.','',$request->input('valor')))),2,'.','');
        $request->request->set('valor', $v);
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $v = number_format(floatval(str_replace(',', '.', str_replace('.','',$request->input('valor')))),2,'.','');
        $request->request->set('valor', $v);
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
