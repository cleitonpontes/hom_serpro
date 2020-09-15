<?php

namespace App\Http\Controllers\Gescon;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GlosaRequest as StoreRequest;
use App\Http\Requests\GlosaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Route;

/**
 * Class GlosaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GlosaCrudController extends CrudController
{
    public function setup()
    {
        $contratoitem_servico_indicador_id = Route::current()->parameter('contratoitem_servico_id');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Glosa');
        $this->crud->setRoute(
            config('backpack.base.route_prefix')
            . '/gescon/meus-servicos/' . $contratoitem_servico_indicador_id . '/glosas'
        );
        $this->crud->setEntityNameStrings('glosa', 'glosas');

        //todo arrumar o botao voltar para o local correto
        $this->crud->addButtonFromView('top', 'voltar', 'voltarmeucontrato', 'end');
        $this->crud->enableExportButtons();

        //todo LEMBRAR DE FAZER OS ACESSOS
        $this->crud->allowAccess('show');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        //  remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();

        $this->crud->addFields($this->campos($contratoitem_servico_indicador_id));

        // add asterisk for fields that are required in GlosaRequest
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
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    private function campos(string $contratoitem_servico_indicador_id): array
    {
        return [
            [   // Hidden
                'name' => 'contratoitem_servico_indicador_id',
                'type' => 'hidden',
                'default' => $contratoitem_servico_indicador_id,
            ],
            [   // Range
                'name' => 'range',
                'label' => 'Range',
                'type' => 'range'
            ],
//            [ // select_from_array
//                'name' => 'indicador_id',
//                'label' => 'Indicador',
//                'type' => 'select2_from_array',
//                'options' => $indicadores,
//                'allows_null' => false,
//                'placeholder' => 'Selecione',
////                'allows_multiple' => true,
////                'tab' => 'Dados do serviço',
//            ],
            [
                'name' => 'tipo_afericao',
                'label' => 'Aferição',
                'type' => 'radio',
                'options' => [0 => 'Percentual', 1 => 'Número de Ocorrências'],
                'default' => 0,
                'inline' => true,
//                'tab' => 'Dados do serviço',
            ],
//            [
//                'name' => 'detalhe',
//                'label' => 'Detalhe',
//                'type' => 'textarea',
//                'attributes' => [
//                    'onfocusout' => "maiuscula(this)"
//                ],
////                'tab' => 'Dados do serviço',
//            ],
//            [   // Number
//                'name' => 'valor',
//                'label' => 'Valor',
//                'type' => 'money',
//                // optionals
//                'attributes' => [
//                    'id' => 'valor',
//                ], // allow decimals
//                'prefix' => "R$",
////                'tab' => 'Dados do serviço',
//            ],
//            [
//                'name' => 'situacao',
//                'label' => "Situação",
//                'type' => 'select2_from_array',
//                'options' => [1 => 'Ativo', 0 => 'Inativo'],
//                'allows_null' => false,
////                'tab' => 'Dados do serviço',
//            ],
//            [
//                'name' => 'indicador',
//                'label' => "Indicador",
//                'type' => 'select2_from_array',
//                'options' => [1 => 'Ativo', 0 => 'Inativo'],
//                'allows_null' => false,
//                'tab' => 'Indicador Associado',
//            ],
//            [
//                'name' => 'indicadores',
//                'label' => 'Indicadores',
//                'type' => 'table2',
//                'indicadores' => $indicadores,
//                'periodicidade' => [1 => 'Anual', 2 => 'Mensal', 3 => 'Semanal'],
//                'entity_singular' => 'indicador', // used on the "Add X" button
//                'columns' => [
//                    'name' => 'Indicador',
//                    'desc' => 'Tipo Aferição',
//                    'meta' => 'Meta',
//                    'price' => 'Periodicidade'
//                ],
//                'max' => 50,
//                'min' => 0,
//                'tab' => 'Indicador Associado',
//            ],


        ];
    }
}
