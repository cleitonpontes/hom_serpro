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
        // array de encargos (fat empresa) - só pode ser 1, 2 ou 3% - array será usado em campos()
        $arrayEncargosFatEmpresa = [1 => '1%', 2 => '2%', 3 => '3%'];
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratoconta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/' . $contrato_id . '/contratocontas');
        $this->crud->setEntityNameStrings('conta-deposito vinculada', 'Conta-Depósito Vinculada');
        $this->crud->addButtonFromView('top', 'Sobre', 'sobrecontratoconta', 'begin');
        $this->crud->addButtonFromView('top', 'voltar', 'voltarmeucontrato', 'end');
        $this->crud->addButtonFromView('line', 'morecontratoconta', 'morecontratoconta', 'end');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->allowAccess('show');
        // permissões
        (backpack_user()->can('contratoconta_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contratoconta_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contratoconta_deletar')) ? $this->crud->allowAccess('delete') : null;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        // listagem
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);
        // formulário
        $campos = $this->Campos($contrato, $arrayEncargosFatEmpresa);
        $this->crud->addFields($campos);
        // add asterisk for fields that are required in ContratocontaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
    public function verificarSeContratoJaPossuiConta($request){
        $contratoId = \Route::current()->parameter('contrato_id');
        if( Contratoconta::where('contrato_id', $contratoId)->count() > 0 ){return true;}
        return false;
    }
    public function store(StoreRequest $request)
    {
        // será permitida apenas uma conta por contrato. Vamos verificar
        if(self::verificarSeContratoJaPossuiConta($request)){
            \Alert::error('Já existe uma Conta-Depósito Vinculada a este contrato!')->flash();
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
    public function Campos($contrato, $arrayEncargosFatEmpresa)
    {
        $campos = [
            [   // Hidden
                'name' => 'contrato_id',
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
                'label' => "Encargos (%)",
                'type' => 'radio',
                'options' => $arrayEncargosFatEmpresa,
                'allows_null' => false,
                'allows_multiple' => false, // OPTIONAL; needs you to cast this to array in your model;
                // 'options' => [1 => 'Sim', 0 => 'Não'],
                'inline' => true,
                'default' => 1
            ],
            [   // Number
                'name' => 'percentual_grupo_a_13_ferias',
                'label' => 'Percentual Grupo A',
                'type' => 'number',
                // optionals
                'attributes' => [
                    'id' => 'percentual_grupo_a_13_ferias',
                    // 'readonly' => 'readonly',
                    'step' => '0.0001',
                ], // allow decimals
                'prefix' => "% ",
                // 'default' => number_format($contrato->valor_global, 2, ',', '.'),
                // 'tab' => 'Vigência / Valores',
                // 'suffix' => ".00",
            ],
            [   // Number
                'name' => 'percentual_submodulo22',
                'label' => 'Percentual do Submódulo 2.2',
                'type' => 'number',
                // optionals
                'attributes' => [
                    'id' => 'percentual_submodulo22',
                    // 'readonly' => 'readonly',
                    'step' => '0.0001',
                ], // allow decimals
                'prefix' => "% ",
                // 'default' => number_format($contrato->valor_global, 2, ',', '.'),
                // 'tab' => 'Vigência / Valores',
                // 'suffix' => ".00",
            ],
        ];
        return $campos;
    }
    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'banco',
                'label' => 'Banco',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'agencia',
                'label' => 'Agência',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'conta_corrente',
                'label' => 'Conta Corrente',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'percentual_grupo_a_13_ferias',
                'label' => 'Percentual Grupo A',
                'type' => 'number',
                'decimals' => 2,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'prefix' => "% ",
            ],
            [
                'name' => 'percentual_submodulo22',
                'label' => 'Percentual Submódulo 2.2',
                'type' => 'number',
                'decimals' => 2,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'prefix' => "% ",
            ],
            [
                'name' => 'fat_empresa',
                'label' => 'Encargo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'prefix' => "% ",
            ],
            [
                'name' => 'getSaldoContratoContaParaColunas',
                'label' => 'Saldo da Conta', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSaldoContratoContaParaColunas', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'prefix' => "R$ ",
            ],
            [
                'name' => 'getStatusDaConta',
                'label' => 'Status da Conta', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getStatusDaConta', // the method in your Model
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
