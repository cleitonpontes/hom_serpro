<?php

namespace App\Http\Controllers\Admin;

use App\Models\Codigoitem;


use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\FeriadoRequest as StoreRequest;
use App\Http\Requests\FeriadoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class FeriadoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class FeriadoCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Feriado');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/feriado');
        $this->crud->setEntityNameStrings('feriado', 'feriados');

        // $this->crud->addClause('select', 'feriados.*', 'codigoitens.descricao as descricao_codigoitem');
        $this->crud->addClause('select', 'feriados.data');
        $this->crud->addClause('select', 'feriados.descricao as descricao_feriado');

        $this->crud->addClause('select', [
            'codigoitens.descricao as descricao_codigoitem',
            // Tabela principal deve ser sempre a última da listagem!
            'feriados.id',
            'feriados.data',
            'feriados.descricao as descricao_feriado',
            'feriados.tipo_id'
        ]);

        $this->crud->addClause('join', 'codigoitens',
            'codigoitens.id', '=', 'feriados.tipo_id'
        );
        $this->crud->addClause('orderBy', 'data');

        // tipos de feriados para o select
        $arrayTiposFeriados = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo Feriados');
        })
            // ->Where('descricao', '=', 'Termo de Rescisão')
            ->pluck('descricao', 'id')
            ->toArray();

        // colunas da listagem
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);
        // campos do formulário
        $campos = $this->Campos($arrayTiposFeriados);
        $this->crud->addFields($campos);

        backpack_user()->hasRole('Administrador') ? $this->crud->allowAccess('show') : $this->crud->denyAccess('show');
        backpack_user()->hasRole('Administrador') ? $this->crud->allowAccess('show') : $this->crud->denyAccess('create');
        backpack_user()->hasRole('Administrador') ? $this->crud->allowAccess('show') : $this->crud->denyAccess('update');
        backpack_user()->hasRole('Administrador') ? $this->crud->allowAccess('show') : $this->crud->denyAccess('delete');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // add asterisk for fields that are required in FeriadoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        backpack_user()->hasRole('Administrador') ? $this->crud->enableExportButtons() : null;

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

    public function Colunas()
    {
        $colunas = [
            [
                'name'  => 'data',
                'label' => 'Data',
                'type'  => 'date',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('feriados.data', 'ilike', "%$searchTerm%");
                },
            ],
            [
                'name'  => 'descricao_feriado',
                'label' => 'Descrição',
                'type'  => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => false,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('feriados.descricao', 'ilike', "%$searchTerm%");
                },
                'orderLogic' => function ($query, $column, $columnDirection) {
                    return $query->orderBy('feriados.descricao', $columnDirection);
                }
            ],
            [
                'name'  => 'tipo_id',
                'label' => 'Tipo Feriado',
                'type'  => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
                },
                'orderLogic' => function ($query, $column, $columnDirection) {
                    return $query->orderBy('codigoitens.descricao', $columnDirection);
                }
            ],
        ];
        return $colunas;
    }

    public function Campos($arrayTiposFeriados)
    {
        $campos = [
            [
                'name' => 'data',
                'label' => "Data",
                'type' => 'date',
                'format' => 'd/m/Y',
            ],
            [
                'name' => 'descricao',
                'label' => "Descrição",
                'type' => 'text',
                'orderable' => true,
            ],
            [ // select_from_array
                'name' => 'tipo_id',
                'label' => "Tipo do Feriado",
                'type' => 'select2_from_array',
                'options' => $arrayTiposFeriados,
                'allows_null' => false,
            ],
        ];
        return $campos;
    }


    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            // 'tipo_id',
            // 'data',
            'descricao_feriado',
            'descricao_codigoitem'
        ]);


        return $content;
    }
}
