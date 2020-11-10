<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\AdminController;
use App\Models\AmparoLegal;
use Backpack\CRUD\app\Http\Controllers\CrudController;

use App\Http\Requests\AmparoLegalRequest as StoreRequest;
use App\Http\Requests\AmparoLegalRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
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
//                $this->crud->denyAccess('create');
//                $this->crud->denyAccess('update');
//                $this->crud->denyAccess('delete');
//                $this->crud->allowAccess('show');


//                (backpack_user()->can('indicador_inserir'))
//                    ? $this->crud->allowAccess('create') : null;
//                (backpack_user()->can('indicador_editar'))
//                    ? $this->crud->allowAccess('update') : null;
//                (backpack_user()->can('indicador_deletar'))
//                    ? $this->crud->allowAccess('delete') : null;

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
            } else {
                abort('403', config('app.erro_permissao'));
            }
    }

    public function store(StoreRequest $request)
    {
//        $amparo = Indicador::where('nome', $request->nome)->onlyTrashed()->first();
//
//        //CASO EXISTA INDICADOR DELETADO
//        if ($amparo) {
//            $amparo->update(['finalidade' => $request->finalidade, 'situacao' => $request->situacao]);
//            $amparo->restore();
//            Alert::success(trans('backpack::crud.insert_success'))->flash();
            $redirectUrl = Request::has('http_referrer') ? Request::get('http_referrer') : $this->crud->route;
//
            return Redirect::to($redirectUrl);
//
//            //$indicador->forceDelete();

//        }

//        // your additional operations before save here
//        $redirect_location = parent::storeCrud($request);
//        // your additional operations after save here
//        // use $this->data['entry'] or $this->crud->entry
//        return $redirect_location;
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
                    'onfocusout' => "maiuscula(this)",
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
