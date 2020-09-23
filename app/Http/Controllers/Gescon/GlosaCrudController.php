<?php

namespace App\Http\Controllers\Gescon;

use App\Http\Traits\Formatador;
use App\Models\Codigoitem;
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

        $escopo_glosas = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Escopo da Glosa');
        })
            ->pluck('descricao', 'id')
            ->toArray();
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

        $this->columns($escopo_glosas);
        $this->fields($contratoitem_servico_indicador_id, $contratoitem_servico_indicador->tipo_afericao, $escopo_glosas);

        // add asterisk for fields that are required in GlosaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $this->setRequestFaixa($request);

        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $this->setRequestFaixa($request);

        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('contratoitem_servico_indicador_id');

        return $content;
    }

    private function setRequestFaixa($request): void
    {
        if (isset($request->slider)) {
            $faixa = explode(';', $request->slider);
            $request->request->set('from', $faixa[0]);
            $request->request->set('to', $faixa[1]);
            return;
        }

        $request->request->set('from', $this->retornaFormatoAmericano($request->from));
        $request->request->set('to', $this->retornaFormatoAmericano($request->to));

    }

    private function fields(string $contratoitem_servico_indicador_id, bool $tipo_afericao, array $escopo_glosas): void
    {
        $this->setFieldContratoItemServicoIndicador($contratoitem_servico_indicador_id);
        $this->setFieldFaixa($tipo_afericao);
        $this->setFieldValorGlosa();
        $this->setFieldEscopo($escopo_glosas);
    }

    private function columns(array $escopo_glosas): void
    {
        $this->setColumnAPartirDe();
        $this->setColumnAte();
        $this->setColumnValorGlosa();
        $this->setColumnEscopo($escopo_glosas);

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

    private function setColumnEscopo(array $escopo_glosas): void
    {
        $this->crud->addColumn([
            'name' => 'escopo_id',
            'label' => 'Escopo',
            'type' => 'select_from_array',
            'options' => $escopo_glosas,
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);

    }

    private function setFieldFaixa(bool $tipo_afericao): void
    {
        if ($tipo_afericao) {
            $this->setFieldFrom();
            $this->setFieldTo();
            return;
        }
        $this->setFieldSlider();
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
            'attributes' => [
                'id' => 'from',
            ], // allow decimals
            'prefix' => "> =",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);
    }

    private function setFieldTo(): void
    {
        $this->crud->addField([   // Number
            'name' => 'to',
            'label' => 'Até',
            'type' => 'money',
            'attributes' => [
                'id' => 'to',
            ], // allow decimals
            'prefix' => "<",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);
    }

    private function setFieldValorGlosa(): void
    {
        $this->crud->addField([   // Number
            'name' => 'valor_glosa',
            'label' => 'Valor da Glosa (%)',
            'type' => 'money',
        ]);
    }

    private function setFieldEscopo(array $escopo_glosas): void
    {
        $this->crud->addField([
            'name' => 'escopo_id',
            'label' => 'Escopo da Glosa',
            'type' => 'radio',
            'options' => $escopo_glosas,
            'inline' => true,
        ]);
    }
}
