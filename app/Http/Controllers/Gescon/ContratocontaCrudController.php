<?php

namespace App\Http\Controllers\Gescon;

// inserido
use App\Models\Codigoitem;
use App\Models\Contratoconta;
use App\Models\Contrato;
use Backpack\CRUD\app\Http\Controllers\CrudController;


// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratocontaRequest as StoreRequest;
use App\Http\Requests\ContratocontaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// inserido
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


/**
 * Class ContratocontaCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 */
class ContratocontaCrudController extends CrudController
{
    public function setup()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');

        $contrato = Contrato::where('id','=',$contrato_id)
            ->where('unidade_id','=',session()->get('user_ug_id'))->first();
        if(!$contrato){
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratoconta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/' . $contrato_id . '/contratocontas');
        $this->crud->setEntityNameStrings('contratoconta', 'contratocontas');
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->enableExportButtons();

        $this->crud->addButtonFromView('line', 'morecontratoconta', 'morecontratoconta', 'end');



        $this->crud->allowAccess('show');


        (backpack_user()->can('contratoconta_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contratoconta_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contratoconta_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $campos = $this->Campos($contrato);
        $this->crud->addFields($campos);



        // add asterisk for fields that are required in ContratocontaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

    }

    public function verificarSeContratoJaPossuiConta($request){
        // $contratoId = $request->request->get('contrato_id');
        $contratoId = \Route::current()->parameter('contrato_id');
        if( Contratoconta::where('contrato_id', $contratoId)->count() > 0 ){return true;}
        return false;
    }

    public function store(StoreRequest $request)
    {
        // será permitida apenas uma conta por contrato. Vamos verificar
        if(self::verificarSeContratoJaPossuiConta($request)){
            \Alert::error('Já existe uma conta para este contrato!')->flash();
            return redirect()->back();
        }

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


    public function Campos($contrato)
    {
        $campos = [
            [   // Hidden
                'name' => 'contrato_id',
                'label' => 'Contrato', // Table column heading
                'type' => 'hidden',
                'default' => $contrato->id,
            ],
            [
                'name' => 'banco',
                'label' => 'Banco', // Table column heading
                'type' => 'text',
            ],
            [
                'name' => 'agencia',
                'label' => 'Agência', // Table column heading
                'type' => 'text',
            ],
            [
                'name' => 'conta_corrente',
                'label' => 'Conta Corrente', // Table column heading
                'type' => 'text',
            ],
            [
                'name' => 'fat_empresa',
                'label' => 'Fat Empresa', // Table column heading
                'type' => 'text',
            ],
        ];

        return $campos;
    }


    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'banco',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'agencia',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'conta_corrente',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'fat_empresa',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
        ];
        return $colunas;
    }


}
