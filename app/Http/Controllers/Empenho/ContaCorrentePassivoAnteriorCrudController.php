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
//        dump($this->crud->getCurrentEntryId());
         $minuta_id = \Route::current()->parameter('minuta_id');
//         dd($minuta_id);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ContaCorrentePassivoAnterior');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'empenho/passivo-anterior');
        $this->crud->setEntityNameStrings('Conta Corrente Passivo Anterior', 'Contas Corrente Passivo Anterior');
        $this->crud->addButton('','next','button',false,false);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $array_teste = ['minuta_id'=>$minuta_id];
        $this->fields($array_teste);

        // add asterisk for fields that are required in ContaCorrentePassivoAnteriorRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
//        dd($request->all());
        $minuta = MinutaEmpenho::find($request->minutaempenho_id);
//        dump($request->all());
        $itens = json_decode($request->get('options'), true);
        $minutaempenho_id = $request->minutaempenho_id;

        $itens = array_map(
            function ($itens) use ($minutaempenho_id) {
//                dd($minutaempenho_id);
                $itens['minutaempenho_id'] = $minutaempenho_id;
                return $itens;
            },
            $itens
        );
        dd($itens);

        DB::beginTransaction();
        try {

            ContaCorrentePassivoAnterior::insert($itens);
            $minuta->etapa = 8;
            $minuta->save();
            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
        }

//        dd($itens);

//        dd($request->all());
//        dd($request->all());
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

    private function fields(array $array_teste): void
    {
        $this->setFieldMinutaId($array_teste);
        $this->setFieldPassivoAnterior();
        $this->setFieldContaContabil();
        $this->setTablePassivoAnterior();
    }

    private function setFieldMinutaId($array_teste): void
    {
        $this->crud->addField([
            'name' => 'minutaempenho_id',
            'type' => 'hidden',
            'value' => $array_teste['minuta_id']
        ]);
    }

    private function setFieldPassivoAnterior(): void
    {
        $this->crud->addField([
            'name' => 'flag',
            'label' => 'Passivo Anterior',
            'type' => 'checkbox',
            'attr' => [
                'checked' => true
            ]
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
            'name' => 'options',
            'label' => 'Conta Corrente',
            'type' => 'table',
            'entity_singular' => 'option', // used on the "Add X" button
            'columns' => [
                'conta_corrente' => 'Número da Conta Corrente',
                'valor' => 'Valor'
            ],
            'max' => 5, // maximum rows allowed in the table
            'min' => 0, // minimum rows allowed in the table
        ]);
    }

}
