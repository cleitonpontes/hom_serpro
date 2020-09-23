<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use App\Models\Indicador;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Traits\Formatador;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratoItemServicoIndicadorRequest as StoreRequest;
use App\Http\Requests\ContratoItemServicoIndicadorRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Route;

/**
 * Class ContratoItemServicoIndicadorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratoItemServicoIndicadorCrudController extends CrudController
{
    use Formatador;

    public function setup()
    {
        $contratoitem_servico_id = Route::current()->parameter('cis_i_id');
        $indicadores = Indicador::all()->pluck('nome', 'id')->toArray();

        $periodicidade = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Periodicidade da Glosa');
        })
            ->pluck('descricao', 'id');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ContratoItemServicoIndicador');
//        $this->crud->setRoute(config('backpack.base.route_prefix') . '/contratoitemservicoindicador');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/meus-servicos/' . $contratoitem_servico_id . '/indicadores');
        $this->crud->setEntityNameStrings('indicador', 'indicadores');
        $this->crud->removeButton('create');
        $this->crud->addButtonFromView('top', 'vincular', 'vincularIndicador');

        //todo arrumar o botao voltar para o local correto
        $this->crud->addButtonFromView('top', 'voltar', 'voltarmeucontrato', 'end');
        $this->crud->enableExportButtons();

        //todo LEMBRAR DE FAZER OS ACESSOS
        $this->crud->allowAccess('show');

        $this->crud->addButtonFromView('line', 'moreglosas', 'moreglosas', 'end');


        // Apenas ocorrencias deste contratoitem_servico_id
        $this->crud->addClause('where', 'contratoitem_servico_indicador.contratoitem_servico_id', '=', $contratoitem_servico_id);


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */


        $this->columns($periodicidade);
        $this->fields($contratoitem_servico_id, $indicadores, $periodicidade);

        // add asterisk for fields that are required in ContratoItemServicoIndicadorRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $request->request->set('vlrmeta', $this->retornaFormatoAmericano($request->vlrmeta));

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

    private function fields(string $contratoitem_servico_id
        , array $indicadores, $periodicidade): void
    {
        $this->setFieldContratoItemServico($contratoitem_servico_id);
        $this->setFieldIndicador($indicadores);
        $this->setFieldTipoAfericao();
        $this->setFieldMeta();
        $this->setFieldPeriodicidade($periodicidade);
    }

    private function columns($periodicidade): void
    {
        $this->setColumnIndicador();
        $this->setColumnTipoAfericao();
        $this->setColumnMeta();
        $this->setColumnPeriodicidade($periodicidade);
    }

    private function setColumnIndicador(): void
    {
        $this->crud->addColumn([
            'name' => 'getIndicador',
            'label' => 'Indicador', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getIndicador', // the method in your Model
            'orderable' => true,
            'limit' => 1000,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);

    }

    private function setColumnTipoAfericao(): void
    {
        $this->crud->addColumn([
            'name' => 'tipo_afericao',
            'label' => 'Tipo de Afericao',
            'type' => 'boolean',
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
            // optionally override the Yes/No texts
            'options' => [0 => 'Percentual', 1 => 'Número de Ocorrências'],
        ]);
    }

    private function setColumnMeta()
    {
        $this->crud->addColumn([
            'name' => 'vlrmeta',
            'label' => 'Meta',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
            'options' => [0 => 'Percentual', 1 => 'Número de Ocorrências'],
        ]);
    }

    private function setColumnPeriodicidade($periodicidade)
    {
        $this->crud->addColumn([
            'name' => 'periodicidade_id',
            'label' => "Periodicidade",
            'type' => 'select_from_array',
            'options' => $periodicidade,
            'allows_null' => false,
        ]);
    }

    private function setFieldContratoItemServico($contratoitem_servico_id)
    {
        $this->crud->addField([   // Hidden
            'name' => 'contratoitem_servico_id',
            'type' => 'hidden',
            'default' => $contratoitem_servico_id,
        ]);
    }

    private function setFieldIndicador($indicadores)
    {
        $this->crud->addField([ // select_from_array
            'name' => 'indicador_id',
            'label' => 'Indicador',
            'type' => 'select2_from_array',
            'options' => $indicadores,
            'allows_null' => false,
            'placeholder' => 'Selecione',
        ]);
    }

    private function setFieldTipoAfericao()
    {
        $this->crud->addField([
            'name' => 'tipo_afericao',
            'label' => 'Aferição',
            'type' => 'radio',
            'options' => [0 => 'Percentual', 1 => 'Número de Ocorrências'],
            'default' => 0,
            'inline' => true,
        ]);
    }

    private function setFieldMeta()
    {
        $this->crud->addField([   // Number
            'name' => 'vlrmeta',
            'label' => 'Meta',
            'type' => 'money',
            'attributes' => [
                'id' => 'vlrmeta',
            ], // allow decimals
        ]);
    }

    private function setFieldPeriodicidade($periodicidade)
    {
        $this->crud->addField([
            'name' => 'periodicidade_id',
            'label' => 'periodicidade',
            'type' => 'select2_from_array',
            'options' => $periodicidade,
            'allows_null' => false,
            'placeholder' => 'Selecione',
        ]);
    }

}
