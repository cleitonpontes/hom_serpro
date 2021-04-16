<?php
namespace App\Http\Controllers\Gescon;
use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LancamentoRequest as StoreRequest;
use App\Http\Requests\LancamentoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use App\Models\Movimentacaocontratoconta;
use Illuminate\Database\Eloquent\Builder;
/**
 * Class LancamentoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LancamentoCrudController extends CrudController
{
    public function setup()
    {
        $movimentacaocontratoconta_id = \Route::current()->parameter('movimentacaocontratoconta_id');
        $objMovimentacaoContratoConta = Movimentacaocontratoconta::where('id', '=', $movimentacaocontratoconta_id)->first();
        $contratoconta_id = $objMovimentacaoContratoConta->contratoconta_id;
        \Route::current()->setParameter('contratoconta_id', $contratoconta_id);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Lancamento');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/contratoconta/movimentacaocontratoconta/' . $movimentacaocontratoconta_id . '/lancamento');
        $this->crud->setEntityNameStrings('lancamento', 'lancamentos');

        // adicionar cláusula para trabalharmos apenas com lançamentos da movimentação
        // $this->crud->addClause('select', 'lancamentos.*', 'contratoterceirizados.salario', 'codigoitens.descricao', 'cod_encargo.descricao', 'encargos.percentual');
        $this->crud->addClause('select', 'lancamentos.*', 'contratoterceirizados.salario', 'codigoitens.descricao');
        $this->crud->addClause('join', 'movimentacaocontratocontas', 'movimentacaocontratocontas.id',  '=',  'lancamentos.movimentacao_id');
        $this->crud->addClause('join', 'codigoitens', 'codigoitens.id',  '=',  'movimentacaocontratocontas.tipo_id');
        $this->crud->addClause('join', 'contratoterceirizados', 'contratoterceirizados.id',  '=',  'lancamentos.contratoterceirizado_id');
        // $this->crud->addClause('join', 'encargos', 'encargos.id',  '=',  'lancamentos.encargo_id');
        // $this->crud->addClause('join', 'codigoitens as cod_encargo', 'cod_encargo.id',  '=',  'encargos.tipo_id');
        $this->crud->addClause('where', 'lancamentos.movimentacao_id', '=', $movimentacaocontratoconta_id);
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->addButtonFromView('top', 'voltarparamovimentacoes', 'voltarparamovimentacoes', 'end');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);
        // add asterisk for fields that are required in LancamentoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getNomePessoaContratoTerceirizado',
                'label' => 'Empregado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getNomePessoaContratoTerceirizado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratoterceirizados.nome', 'ilike', "%$searchTerm%");
                },
            ],
            [
                'name' => 'salario',
                'label' => 'Salário',
                'type' => 'text',
                // 'orderable' => true,
                // 'visibleInTable' => true, // no point, since it's a large text
                // 'visibleInModal' => true, // would make the modal too big
                // 'visibleInExport' => true, // not important enough
                // 'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratoterceirizados.salario', 'ilike', "%$searchTerm%");
                },
            ],
            [
                'name' => 'getTipoEncargoOuGrupoA',
                'label' => 'Verba', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoEncargoOuGrupoA', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('cod_encargo.descricao', 'ilike', "%$searchTerm%");
                // },
            ],
            [
                'name' => 'getPercentualEncargoOuGrupoA',
                'label' => 'Percentual', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getPercentualEncargoOuGrupoA', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'prefix' => "% ",

                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('encargos.percentual', 'ilike', "%$searchTerm%");
                // },
            ],
            [
                'name' => 'getTipoMovimentacao',
                'label' => 'Tipo da movimentação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoMovimentacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatValor',
                'label' => 'Valor lançamento', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatValor', // the method in your Model
                'prefix' => "R$ ",
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('lancamentos.valor', 'ilike', "%$searchTerm%");
                // },
            ],
        ];
        return $colunas;
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
}
