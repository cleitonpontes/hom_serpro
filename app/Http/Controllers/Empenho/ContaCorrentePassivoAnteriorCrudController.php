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
use Redirect;
use Route;

/**
 * Class ContaCorrentePassivoAnteriorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContaCorrentePassivoAnteriorCrudController extends CrudController
{
    public function setup()
    {
        $minuta_id = Route::current()->parameter('minuta_id');

        $minuta_id = isset($minuta_id) ? $minuta_id : '';

        $passivoAnterior = '';
        $contaContabilPassivoAnterior = '';


        if (!(null !== Route::current()->parameter('minuta_id'))
            && $this->crud->getCurrentEntryId() !== false
        ) {
            $modPassivoAnterior = ContaCorrentePassivoAnterior::find($this->crud->getCurrentEntryId());
            $modMinuta = $modPassivoAnterior->minutaempenho()->first();
            $minuta_id = $modMinuta->id;

            $passivoAnterior = $modMinuta->passivo_anterior;
            $contaContabilPassivoAnterior = $modMinuta->conta_contabil_passivo_anterior;
        }




//        $passivoAnteriorM = ContaCorrentePassivoAnterior::find(35);
//        $itens = json_decode($passivoAnteriorM->conta_corrente_json, true);
//
//        $arrayPassivoAnterior = ContaCorrentePassivoAnterior::where('minutaempenho_id', $minuta_id)->get()->toArray();
//        $itens = array_map(
//            function ($itens) use ($passivoAnteriorM, $arrayPassivoAnterior) {
//                $itens['conta_corrente'] = '00000';
//                $itens['minutaempenho_id'] = 1;
//                $itens['conta_corrente_json'] = 2;
//                return $itens;
//            },
//            $itens
//        );

//        dd($minuta_id);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ContaCorrentePassivoAnterior');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'empenho/passivo-anterior');
        $this->crud->setEntityNameStrings('Conta Corrente Passivo Anterior', 'Contas Corrente Passivo Anterior');
        $this->crud->addClause('join', 'minutaempenhos', 'minutaempenhos.id', '=', 'conta_corrente_passivo_anterior.minutaempenho_id');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'minutaempenhos.fornecedor_empenho_id');
        $this->crud->addClause('join', 'compras', 'compras.id', '=', 'minutaempenhos.compra_id');
        $this->crud->addClause('where', 'minutaempenhos.id', 11);
//        $this->crud->addClause('join', 'compra_items', 'compra_items.compra_id', '=', 'compras.id');
        $this->crud->addClause(
            'select',
            'conta_corrente_passivo_anterior.*',
            'minutaempenhos.id',
            'minutaempenhos.passivo_anterior',
            'minutaempenhos.etapa',
            'fornecedores.cpf_cnpj_idgener',
            'minutaempenhos.valor_total',
            'minutaempenhos.conta_contabil_passivo_anterior',
            DB::raw("replace(replace(replace(fornecedores.cpf_cnpj_idgener,'-',''),'.',''),'/','') as conta_corrente_padrao")
        );
//        dd($this->crud->query->getBindings(),$this->crud->query->toSql());
//        dd($this->crud->query->first());
        $query = $this->crud->query->first();
        $params = ['valor_total' => $query->valor_total, 'conta_corrente_padrao' => $query->conta_corrente_padrao ];

        $this->crud->params = $params;

        $this->crud->setEditView('vendor.backpack.crud.empenho.edit');
        $this->crud->setCreateView('vendor.backpack.crud.empenho.create');

//        dd($this->crud->query->toSql());
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->fields($minuta_id, $passivoAnterior, $contaContabilPassivoAnterior);

        // add asterisk for fields that are required in ContaCorrentePassivoAnteriorRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        dd($request->all());
        $minuta = MinutaEmpenho::find($request->minutaempenho_id);

        DB::beginTransaction();
        try {
            if ($request->passivo_anterior == true) {
                $itens = json_decode($request->get('conta_corrente_json'), true);
                $conta_corrente_json = $request->get('conta_corrente_json');

                $itens = array_map(
                    function ($itens) use ($request) {
                        $itens['minutaempenho_id'] = $request->minutaempenho_id;
                        $itens['conta_corrente_json'] = $request->conta_corrente_json;
                        return $itens;
                    },
                    $itens
                );

                ContaCorrentePassivoAnterior::insert($itens);
            }

            $minuta->etapa = 8;
            $minuta->passivo_anterior = $request->passivo_anterior;
            $minuta->conta_contabil_passivo_anterior = $request->conta_contabil_passivo_anterior;

            $minuta->save();
            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
        }

        return Redirect::to('empenho/minuta/' . $minuta->id);
    }

    public function update(UpdateRequest $request)
    {
        dd($request->all());

        $minuta = MinutaEmpenho::find($request->minutaempenho_id);
        $itens = json_decode($request->get('conta_corrente_json'), true);
        $arrayPassivoAnterior = ContaCorrentePassivoAnterior::where('minutaempenho_id', $request->minutaempenho_id)->get()->toArray();

        $itens = array_map(
            function ($itens) use ($request, $arrayPassivoAnterior) {
                $itens['minutaempenho_id'] = $arrayPassivoAnterior[0]['minutaempenho_id'];
                $itens['conta_corrente_json'] = $request->conta_corrente_json;
                return $itens;
            },
            $itens
        );

        DB::beginTransaction();
        try {
            $this->deletaPassivoAnterior($arrayPassivoAnterior);
            ContaCorrentePassivoAnterior::insert($itens);
            $minuta->etapa = 8;
            $minuta->passivo_anterior = $request->passivo_anterior;
            $minuta->conta_contabil_passivo_anterior = $request->conta_contabil_passivo_anterior;

            $minuta->save();
            DB::commit();
        } catch (Exception $exc) {
            //  dd($exc);
            DB::rollback();
        }

        return Redirect::to('empenho/minuta/' . $minuta->id);
    }


    public function deletaPassivoAnterior(array $modPassivoAnterior)
    {
        foreach ($modPassivoAnterior as $key => $value) {
            ContaCorrentePassivoAnterior::where('id', $value['id'])->forceDelete();
        }
    }

    private function fields($minuta_id, $passivoAnterior, $contaContabilPassivoAnterior): void
    {
        $this->setFieldMinutaId($minuta_id);
        $this->setFieldPassivoAnterior($passivoAnterior);
        $this->setFieldContaContabil($contaContabilPassivoAnterior, $passivoAnterior);
        $this->setFieldTablePassivoAnterior();
        $this->setFieldTablePassivoAnterior();
    }

    private function setFieldMinutaId($minuta_id): void
    {
        $this->crud->addField([
            'name' => 'minutaempenho_id',
            'type' => 'hidden',
            'value' => $minuta_id
        ]);
    }

    private function setFieldPassivoAnterior($passivoAnterior): void
    {
        $this->crud->addField([
            'name' => 'passivo_anterior',
            'label' => 'Passivo Anterior',
            'type' => 'passivo_anterior_checkbox',
            'attributes' => [
                'id' => 'passivo'
            ],
            'value' => $passivoAnterior
        ]);
    }

    private function setFieldContaContabil($contaContabilPassivoAnterior, $passivoAnterior): void
    {

        $this->crud->addField([
            'name' => 'conta_contabil_passivo_anterior',
            'label' => 'Conta Contábil',
            'type' => 'conta_contabil_text',
            'attributes' => [
                'id'=>'contabil'
            ],
            'value' => $contaContabilPassivoAnterior,
        ]);
    }

    private function setFieldTablePassivoAnterior(): void
    {

        $this->crud->addField([
            'name' => 'conta_corrente_json',
            'label' => 'Conta Corrente',
            'type' => 'empenho_table',
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
