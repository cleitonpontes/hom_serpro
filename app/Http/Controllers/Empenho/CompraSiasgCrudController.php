<?php

namespace App\Http\Controllers\Empenho;

use App\Http\Controllers\Empenho\Minuta\Etapa1EmpenhoController;
use App\Http\Controllers\Empenho\Minuta\Tela1EmpenhoController;
use App\Http\Traits\Formatador;
use App\Models\Codigoitem;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CompraSiasgRequest as StoreRequest;
use App\Http\Requests\CompraSiasgRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;
use Route;

/**
 * Class GlosaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CompraSiasgCrudController extends CrudController
{
    use Formatador;

    public function setup()
    {
        $modalidades = Codigoitem::where('codigo_id',13)
            ->where('visivel',true)
            ->pluck('descricao','descres')
            ->toArray();

        $uasgCompra = Unidade::select('unidades.codigo',
            DB::raw("CONCAT(unidades.codigo,'-',unidades.nomeresumido) AS unidadecompra")
        )
            ->pluck('unidadecompra','codigo')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Compra');
        $this->crud->setRoute(
            config('backpack.base.route_prefix')
            . '/empenho/buscacompra'
        );
        $this->crud->setEntityNameStrings('Buscar Compra', 'Buscar Compras');

        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('show');

        (backpack_user()->can('glosa_inserir')) ? $this->crud->allowAccess('create') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        //$this->columns();
        $this->fields($modalidades,$uasgCompra);

        // add asterisk for fields that are required in GlosaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        dd($request->all());
        // your additional operations before save here
        $novoEmpenho = new Etapa1EmpenhoController();
        $novoEmpenho->gravar($request);

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

//        $this->crud->removeColumn('contratoitem_servico_indicador_id');

        return $content;
    }

    private function fields(array $modalidades , array $uasg_compra): void
    {
        $this->setFieldModalidade($modalidades);
        $this->setFieldNumeroAno();
        $this->setFieldUnidadeCompra();

    }

    private function setFieldUnidadeCompra(): void
    {
        $this->crud->addField([
            'label' => "Unidade Compra",
            'type' => "select2_from_ajax",
            'name' => 'unidade_origem_id',
            'entity' => 'unidadecompra',
            'attribute' => "codigo",
            'attribute2' => "nomeresumido",
            'process_results_template' => 'gescon.process_results_unidade',
            'model' => "App\Models\Unidade",
            'data_source' => url("api/unidade"),
            'placeholder' => "Selecione a Unidade",
            'minimum_input_length' => 2
        ]);
    }

    private function setFieldNumeroAno(): void
    {
        $this->crud->addField([
            'name' => 'numero_ano',
            'label' => 'Numero / Ano',
            'type' => 'numcontrato'
        ]);
    }

    private function setFieldModalidade($modalidade): void
    {
        $this->crud->addField([
            'name' => 'modalidade_id',
            'label' => "Modalidade Licitação",
            'type' => 'select2_from_array',
            'options' => $modalidade,
            'allows_null' => true
        ]);
    }


}

