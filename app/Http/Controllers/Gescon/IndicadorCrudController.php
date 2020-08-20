<?php

namespace App\Http\Controllers\Gescon;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use App\Http\Requests\IndicadorRequest as StoreRequest;
use App\Http\Requests\IndicadorRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

/**
 * Class IndicadorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class IndicadorCrudController extends CrudController
{
    public function setup(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Indicador');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/indicador');
        $this->crud->setEntityNameStrings('indicador', 'indicadores');

        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');


        (backpack_user()->can('indicador_inserir'))
            ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('indicador_editar'))
            ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('indicador_deletar'))
            ? $this->crud->allowAccess('delete') : null;

        $this->crud->addColumns($this->colunas());

        $this->crud->addFields($this->campos());

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // add asterisk for fields that are required in IndicadorRequest
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
                'name' => 'nome',
                'label' => 'Indicador',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('indicadores.nome', 'ilike', "%" . $searchTerm . "%");
                },
            ],
            [
                'name' => 'finalidade',
                'label' => 'Finalidade',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('indicadores.finalidade', 'ilike', "%" . $searchTerm . "%");
                }
            ],
            [
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],
        ];
    }

    private function campos(): array
    {
        return [
            [
                'name' => 'nome',
                'label' => 'Nome',
                'type' => 'text',
                'attributes' => [
                    'maxlength' => "255",
                ],
            ],
            [
                'name' => 'finalidade',
                'label' => 'Finalidade',
                'type' => 'textarea',
                'attributes' => [
                    'onfocusout' => "maiuscula(this)"
                ],
            ],
            [
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select2_from_array',
                'options' => [1 => 'Ativo', 0 => 'Inativo'],
                'allows_null' => false,
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
