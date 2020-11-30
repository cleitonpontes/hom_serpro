<?php

namespace App\Http\Controllers\Admin;

use App\Models\Codigoitem;

use Alert;
use App\Http\Controllers\AdminController;
use App\Models\AmparoLegal;
use Backpack\CRUD\app\Http\Controllers\CrudController;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

use App\Http\Requests\AmparoLegalRequest as StoreRequest;
use App\Http\Requests\AmparoLegalRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\View\View;
use Exception;
use Redirect;
use Request;

/**
 * Class AmparoLegalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AmparoLegalCrudController extends CrudController
{
    /**
     * @throws Exception
     */
    public function setup(): void
    {
        if (backpack_user()->hasRole('Administrador')) {

            /*
            |--------------------------------------------------------------------------
            | CrudPanel Basic Information
            |--------------------------------------------------------------------------
            */
            $this->crud->setModel('App\Models\AmparoLegal');
            $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/amparolegal');
            $this->crud->setEntityNameStrings('Amparo Legal', 'Amparos Legais');
            $this->crud->enableExportButtons();

            // modalidades para o select
            $arrayModalidades = Codigoitem::whereHas('codigo', function ($query) {
                $query->where('descricao', '=', 'Modalidade Licitação');
            })
                ->orderBy('descricao')
                ->pluck('descricao', 'id')
                ->toArray();

            $this->crud->addColumns($this->colunas());
            $this->crud->addFields($this->campos($arrayModalidades));

            /*
            |--------------------------------------------------------------------------
            | CrudPanel Configuration
            |--------------------------------------------------------------------------
            */

            // add asterisk for fields that are required in IndicadorRequest
            $this->crud->setRequiredFields(StoreRequest::class, 'create');
            $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        } else {
            abort('403', config('app.erro_permissao'));
        }
    }

    public function getTipoRestricao(){
        return 'ok';
    }

    private function campos($arrayModalidades): array
    {
        return [
            [
                // n-n relationship
                'label' => "Demais UGs/UASGs", // Table column heading
                'type' => "select2_from_ajax_multiple",
                'name' => 'unidades', // the column that contains the ID of that connected entity
                'entity' => 'unidades', // the method that defines the relationship in your Model
                'attribute' => "codigo", // foreign key attribute that is shown to user
                'attribute2' => "nomeresumido", // foreign key attribute that is shown to user
                'process_results_template' => 'gescon.process_results_multiple_unidade',
                'model' => "App\Models\RestricaoAmparoLegal", // foreign key model
                'data_source' => url("api/unidade"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Selecione a(s) Unidade(s)", // placeholder for the select
                'minimum_input_length' => 2, // minimum characters to type before querying results
                // 'tab' => 'Outros',
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            ],

            [
                // n-n relationship
                'label' => "Restrições", // Table column heading
                'type' => "select2_from_ajax_multiple",
                'name' => 'restricoes', // the column that contains the ID of that connected entity
                'entity' => 'codigoitens', // the method that defines the relationship in your Model
                'attribute' => "descricao", // foreign key attribute that is shown to user
                'attribute2' => "descres", // foreign key attribute that is shown to user
                'process_results_template' => 'gescon.process_results_multiple_tipo_restricao',
                'model' => "App\Models\Codigoitem", // foreign key model
                'data_source' => url("api/codigoitemAmparoLegal"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Selecione a(s) Restrição(ões)", // placeholder for the select
                'minimum_input_length' => 2, // minimum characters to type before querying results
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            ],


            [
                'name' => 'codigo',
                'label' => 'Código',
                'type' => 'number',
            ],
            [ // select_from_array
                'name' => 'modalidade_id',
                'label' => "Modalidade",
                'type' => 'select2_from_array',
                'options' => $arrayModalidades,
                'allows_null' => false,
            ],
            [
                'name' => 'ato_normativo',
                'label' => 'Ato Normativo',
                'type' => 'text',
            ],
            [
                'name' => 'artigo',
                'label' => 'Artigo',
                'type' => 'text',
            ],
            [
                'name' => 'paragrafo',
                'label' => 'Parágrafo',
                'type' => 'text',
            ],
            [
                'name' => 'inciso',
                'label' => 'Inciso',
                'type' => 'text',
            ],
            [
                'name' => 'alinea',
                'label' => 'Alínea',
                'type' => 'text',
            ],

        ];
    }



    public function store(StoreRequest $request)
    {


        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;




        // $redirectUrl = Request::has('http_referrer') ? Request::get('http_referrer') : $this->crud->route;
        // return Redirect::to($redirectUrl);
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    /**
     * retorna as colunas para visualização (show)
     *
     * @return array
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function colunas(): array
    {
        return [
           [
                'name' => 'modalidade_id',
                'label' => 'Modalidade',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('indicadores.nome', 'ilike', "%" . $searchTerm . "%");
                // },
            ],
           [
                'name' => 'ato_normativo',
                'label' => 'Ato Normativo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('indicadores.nome', 'ilike', "%" . $searchTerm . "%");
                // },
            ],
           [
                'name' => 'artigo',
                'label' => 'Artigo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('indicadores.nome', 'ilike', "%" . $searchTerm . "%");
                // },
            ],
           [
                'name' => 'paragrafo',
                'label' => 'Parágrafo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('indicadores.nome', 'ilike', "%" . $searchTerm . "%");
                // },
            ],
           [
                'name' => 'inciso',
                'label' => 'Inciso',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('indicadores.nome', 'ilike', "%" . $searchTerm . "%");
                // },
            ],
           [
                'name' => 'alinea',
                'label' => 'Alínea',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('indicadores.nome', 'ilike', "%" . $searchTerm . "%");
                // },
            ],
        ];
    }


    public function show($id): View
    {
        $content = parent::show($id);

        $this->crud->addColumn([
            'name' => 'nome',
            'label' => 'Indicador',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'limit' => 255
        ]);
        $this->crud->addColumn([
            'name' => 'finalidade',
            'label' => 'Finalidade',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'limit' => 10000
        ]);

        return $content;
    }



}
