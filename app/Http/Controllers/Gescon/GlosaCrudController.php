<?php

namespace App\Http\Controllers\Gescon;

use App\Http\Traits\Formatador;
use App\Models\ContratoItemServicoIndicador;
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
    use Formatador;

    public function setup()
    {
        $contratoitem_servico_indicador_id = Route::current()->parameter('cis_i_id');
        $contratoitem_servico_indicador = ContratoItemServicoIndicador::find($contratoitem_servico_indicador_id);
//        dd ($contratoitem_servico_indicador->tipo_afericao);
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
        $this->colunas();
        $this->campos($contratoitem_servico_indicador_id, $contratoitem_servico_indicador->tipo_afericao);

//        $this->crud->addFields($this->campos($contratoitem_servico_indicador_id));

        // add asterisk for fields that are required in GlosaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
//        dd($request->all());

        $request->request->set('from', $this->retornaFormatoAmericano($request->from));
        $request->request->set('to', $this->retornaFormatoAmericano($request->to));


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

    private function campos(string $contratoitem_servico_indicador_id, bool $tipo_afericao): void
    {
        $this->setFieldContratoItemServicoIndicador($contratoitem_servico_indicador_id);
//        $this->setFieldSlider($indicadores);

        if ($tipo_afericao) {
            $this->setFieldFrom();
            $this->setFieldTo();
        } else {
            $this->setFieldSlider();
        }

        $this->setFieldValorGlosa();
        $this->setFieldEscopo();
//        return [
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

//        ];
    }

    private function colunas(): void
    {
        $this->setColumnAPartirDe();
        $this->setColumnAte();
        $this->setColumnValorGlosa();
        $this->setColumnEscopo();

    }

    private function setColumnAPartirDe(): void
    {
        $this->crud->addColumn([
            'name' => 'from',
            'label' => 'A Partir de',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);

    }

    private function setColumnAte(): void
    {
        $this->crud->addColumn([
            'name' => 'to',
            'label' => 'Até',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    private function setColumnValorGlosa(): void
    {
        $this->crud->addColumn([
            'name' => 'valor_glosa',
            'label' => 'Valor da Glosa (%)',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    private function setColumnEscopo(): void
    {
        $this->crud->addColumn([
            'name' => 'escopo',
            'label' => 'Escopo',
            'type' => 'select_from_array',
            'options' => [
                0 => 'Serviço',
                1 => 'Fatura',
                2 => 'Contrato'
            ],
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);

    }

    private function setFieldContratoItemServicoIndicador($contratoitem_servico_indicador_id): void
    {
        $this->crud->addField([   // Hidden
            'name' => 'contratoitem_servico_indicador_id',
            'type' => 'hidden',
            'default' => $contratoitem_servico_indicador_id,
        ]);
    }

    private function setFieldSlider(): void
    {
        $this->crud->addField([   // Range
            'name' => 'slider',
            'label' => 'Faixas de ajuste no pagamento',
            'type' => 'slider'
        ]);
    }

    private function setFieldFrom(): void
    {
        $this->crud->addField([   // Number
            'name' => 'from',
            'label' => 'A partir de',
            'type' => 'money',
            // optionals
            'attributes' => [
                'id' => 'from',
            ], // allow decimals
            'suffix' => "> =",
//                'tab' => 'Dados do serviço',
        ]);
    }

    private function setFieldTo(): void
    {
        $this->crud->addField([   // Number
            'name' => 'to',
            'label' => 'Até',
            'type' => 'money',
            // optionals
            'attributes' => [
                'id' => 'to',
            ], // allow decimals
            'prefix' => "<",
//                'tab' => 'Dados do serviço',
        ]);
    }

    private function setFieldValorGlosa(): void
    {
        $this->crud->addField([   // Number
            'name' => 'valor_glosa',
            'label' => 'Valor da Glosa (%)',
            'type' => 'money',
            // optionals
//                'prefix' => "<",
//                'tab' => 'Dados do serviço',
        ]);
    }

    private function setFieldEscopo(): void
    {
        $this->crud->addField([
            'name' => 'escopo',
            'label' => 'Escopo da Glosa',
            'type' => 'radio',
            'options' => [
                0 => 'Serviço',
                1 => 'Fatura',
                2 => 'Contrato'
            ],
            'default' => 0,
            'inline' => true,
//                'tab' => 'Dados do serviço',
        ]);
    }
}
