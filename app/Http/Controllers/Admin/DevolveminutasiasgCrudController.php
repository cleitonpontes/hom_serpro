<?php
namespace App\Http\Controllers\Admin;
use App\Models\Devolveminutasiasg;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// inserido
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DevolveminutasiasgRequest as StoreRequest;
use App\Http\Requests\DevolveminutasiasgRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class DevolveminutasiasgCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class DevolveminutasiasgCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\DevolveMinutaSiasg');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'admin/devolveminutasiasg');
        $this->crud->setEntityNameStrings('devolveminutasiasg', 'Devolve NE Siasg');

        $this->crud->allowAccess('update');
        $this->crud->allowAccess('show');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('create');

        (backpack_user()->can('devolveminuta_editar')) ? $this->crud->allowAccess('update') : null;

        // cláusulas para possibilitar buscas
        $this->crud->addClause('select', 'devolve_minuta_siasg.*', 'devolve_minuta_siasg.created_at as criado_em', 'minutaempenhos.descricao as descricao_minuta_empenho');
        $this->crud->addClause('join', 'minutaempenhos', 'devolve_minuta_siasg.minutaempenho_id', '=', 'minutaempenhos.id');

        // buscar a descrição da minuta
        if( \Route::current()->parameter('devolveminutasiasg') ){
            $idDevolveMinutaSiasg =  \Route::current()->parameter('devolveminutasiasg');
            $descricao_minuta_empenho = Devolveminutasiasg::where('devolve_minuta_siasg.id','=',$idDevolveMinutaSiasg)
                ->join('minutaempenhos', 'devolve_minuta_siasg.minutaempenho_id', '=', 'minutaempenhos.id')
                ->select('minutaempenhos.descricao')
                ->first()->descricao;
        } else { $descricao_minuta_empenho = null; }

        $this->crud->addColumns($this->colunas());
        $campos = $this->Campos($descricao_minuta_empenho);
        $this->crud->addFields($campos);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        // add asterisk for fields that are required in DevolveminutasiasgRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // filtros na listagem
        $this->adicionarFiltros();
    }
    public function adicionarFiltros()
    {
        $this->adicionarFiltroSituacao();
    }
    public function adicionarFiltroSituacao()
    {
        $arraySituacao = array(
            'Erro' => 'Erro',
            'Pendente' => 'Pendente',
            'Sucesso' => 'Sucesso',

        );
        $campo = [
            'name' => 'situacao',
            'type' => 'select2_multiple',
            'label' => 'Situação'
        ];
        $this->crud->addFilter(
            $campo,
            $arraySituacao,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'devolve_minuta_siasg.situacao', json_decode($value));
            }
        );
    }

    private function colunas(): array
    {
        return [
            [
                'name' => 'getMinutaEmpenho',
                'label' => 'Minuta empenho', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getMinutaEmpenho', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                // 'visibleInModal' => true, // would make the modal too big
                // 'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
                // },

            ],
            [
                'name' => 'situacao',
                'label' => 'Situação', // Table column heading
                // 'type' => 'model_function',
                // 'function_name' => 'formatPercentual', // the method in your Model
                'orderable' => true,
                // 'visibleInTable' => true, // no point, since it's a large text
                // 'visibleInModal' => true, // would make the modal too big
                // 'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('devolve_minuta_siasg.situacao', 'ilike', "%$searchTerm%");
                // },
            ],
            [
                'name' => 'criado_em',
                'label' => 'Criado em', // Table column heading
                // 'type' => 'model_function',
                // 'function_name' => 'formatPercentual', // the method in your Model
                'orderable' => true,
                // 'visibleInTable' => true, // no point, since it's a large text
                // 'visibleInModal' => true, // would make the modal too big
                // 'visibleInExport' => true, // not important enough
                'visibleInShow' => false, // sure, why not
            ],
            [
                'name' => 'alteracao',
                'label' => 'Alteração?', // Table column heading
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => false, // sure, why not
                // optionally override the Yes/No texts
                'options' => [false => 'Não', true => 'Sim']

            ],
            [
                'name' => 'getMinutaEmpenhoRemessa',
                'label' => 'Minuta Empenho Remessa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getMinutaEmpenhoRemessa', // the method in your Model
                'orderable' => true,
                // 'visibleInTable' => true, // no point, since it's a large text
                // 'visibleInModal' => true, // would make the modal too big
                // 'visibleInExport' => true, // not important enough
                'visibleInShow' => false, // sure, why not
            ],
            [
                'name' => 'minutaempenho_id',
                'label' => 'Minuta Empenho', // Table column heading
                'type' => 'text',
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInShow' => false, // sure, why not
            ],
            [
                'name' => 'minutaempenhos_remessa_id',
                'label' => 'Minuta Empenho', // Table column heading
                'type' => 'text',
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInShow' => false, // sure, why not
            ],
        ];
    }

    public function Campos($descricao_minuta_empenho)
    {
        $arraySituacao = array(
            'Erro' => 'Erro',
            'Pendente' => 'Pendente',
            'Sucesso' => 'Sucesso',
        );
        $campos = [
            [   //
                'name' => 'descricao_minuta_empenho',
                'label' => 'Desc minuta empenho',
                'type' => 'text',
                // optionals
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ], // allow decimals
                'default' => $descricao_minuta_empenho,
            ],
            [ // select_from_array
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select2_from_array',
                'options' => $arraySituacao,
                // 'options' => config([
                //     'Erro' => 'Erro',
                //     'Pendente' => 'Pendente',
                //     'Sucesso' => 'Sucesso',
                // ]),
                'allows_null' => false,
            ],
        ];
        return $campos;
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
