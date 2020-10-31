<?php

namespace App\Http\Controllers\Empenho;

use App\Models\CompraItemMinutaEmpenho;
use App\Models\ContaCorrentePassivoAnterior;
use App\Models\MinutaEmpenho;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContaCorrentePassivoAnteriorRequest as StoreRequest;
use App\Http\Requests\ContaCorrentePassivoAnteriorRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class ContaCorrentePassivoAnteriorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContaCorrentePassivoAnteriorCrudController extends CrudController
{
    public function setup()
    {

         if(\Route::current()->parameter('minuta_id')) {
             $minuta_id = \Route::current()->parameter('minuta_id');
         }else{
             $modPassivoAnterior = ContaCorrentePassivoAnterior::find($this->crud->getCurrentEntryId());
             $minuta_id = $modPassivoAnterior->minutaempenho_id;
         }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ContaCorrentePassivoAnterior');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'empenho/passivo-anterior');
        $this->crud->setEntityNameStrings('Conta Corrente Passivo Anterior', 'Contas Corrente Passivo Anterior');
        $this->crud->addClause('join', 'minutaempenhos', 'minutaempenhos.id', '=', 'conta_corrente_passivo_anterior.minutaempenhos_id');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'minutaempenhos.fornecedor_empenho_id');
        $this->crud->addClause('join', 'compras', 'compras.id', '=', 'minutaempenhos.compra_id');
        $this->crud->addClause('join', 'compra_items', 'compra_items.compra_id', '=', 'compras.id');
        $this->crud->addClause(
            'select',
                    'conta_corrente_passivo_anterior.*',
                    'minutaempenhos.id',
                    'minutaempenhos.passivo_anterior',
                    'minutaempenhos.etapa',
                    'fornecedores.cpf_cnpj_idgener',
                    'fornecedores.valortotal'
        );

        $this->crud->setEditView('vendor.backpack.crud.empenho.edit');
        $this->crud->setCreateView('vendor.backpack.crud.empenho.create');

//        dd($this->crud->query->toSql());
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
//dd($minuta_id);
        $this->fields($minuta_id);

        // add asterisk for fields that are required in ContaCorrentePassivoAnteriorRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

    }

    public function store(StoreRequest $request)
    {

        $minuta = MinutaEmpenho::find($request->minutaempenho_id);
        $itens = json_decode($request->get('conta_corrente_json'), true);
        $conta_corrente_json = $request->get('conta_corrente_json');

        $itens = array_map(
            function ($itens) use ($request) {
                $itens['minutaempenho_id'] = $request->minutaempenho_id;
                $itens['conta_corrente_json'] = $request->conta_corrente_json;
                $itens['conta_corrente_json'] = $request->conta_corrente_json;
                return $itens;
            },
            $itens
        );


        DB::beginTransaction();
        try {
            ContaCorrentePassivoAnterior::insert($itens);
            $minuta->etapa = 8;
            $minuta->passivo_anterior = $request->passivo_anterior;
            $minuta->save();
            DB::commit();
        } catch (Exception $exc) {
            dd($exc);
            DB::rollback();
        }

        dd('fim store');
        return redirect()->route('empenho.minuta.gravar.saldocontabil', ['etapa_id' => $minuta->etapa, 'minuta_id' => $minuta_id]);

    }

    public function update(UpdateRequest $request)
    {

        $itens = json_decode($request->get('conta_corrente_json'), true);

        $arrayPassivoAnterior = ContaCorrentePassivoAnterior::where('minutaempenho_id',$request->id)->get()->toArray();

        $itens = array_map(
            function ($itens) use ($request,$arrayPassivoAnterior) {
                $itens['minutaempenho_id'] = $arrayPassivoAnterior[0]['minutaempenho_id'];
                $itens['conta_corrente_json'] = $request->conta_corrente_json;
                return $itens;
            },
            $itens
        );


//        dd($itens);

        DB::beginTransaction();
        try {
            $this->deletaPassivoAnterior($arrayPassivoAnterior);
            ContaCorrentePassivoAnterior::insert($itens);
            DB::commit();
        } catch (Exception $exc) {
            dd($exc);
            DB::rollback();
        }

        dd('fim update');
        return redirect()->route('empenho.minuta.gravar.saldocontabil', ['etapa_id' => 8, 'minuta_id' => $itens['minutaempenho_id']]);

    }


    public function deletaPassivoAnterior(array $modPassivoAnterior)
    {
        foreach ($modPassivoAnterior as $key => $value){
            ContaCorrentePassivoAnterior::where('id',$value['id'])->forceDelete();
        }
    }

    private function fields($minuta_id): void
    {
        $modMinuta = MinutaEmpenho::find($minuta_id);

        $this->setFieldMinutaId($modMinuta->di);
        $this->setFieldPassivoAnterior($modMinuta->passivo_anterior);
        $this->setFieldContaContabil();
        $this->setTablePassivoAnterior();
    }

    private function setFieldMinutaId($minuta_id): void
    {
        $this->crud->addField([
            'name' => 'minutaempenho_id',
            'type' => 'hidden',
            'value' => $minuta_id
        ]);
    }

    private function setFieldPassivoAnterior($passivo_anterior): void
    {
        $this->crud->addField([
            'name' => 'passivo_anterior',
            'label' => 'Passivo Anterior',
            'type' => 'checkbox',
            'value' => $passivo_anterior
        ]);
    }

    private function setFieldContaContabil(): void
    {
        $this->crud->addField([
            'name' => 'conta_contabil',
            'label' => 'Conta Contábil',
            'type' => 'text',
            'attr' => [
                'disabled' => 'disabled'
            ]
        ]);
    }

    private function setTablePassivoAnterior(): void
    {
        $this->crud->addField([
            'name' => 'conta_corrente_json',
            'label' => 'Conta Corrente',
            'type' => 'table',
            'entity_singular' => 'options', // used on the "Add X" button
            'columns' => [
                'conta_corrente' => 'Número Conta Corrente',
                'valor' => 'Valor'
            ],
            'max' => 5, // maximum rows allowed in the table
            'min' => 0, // minimum rows allowed in the table
        ]);
    }

}
