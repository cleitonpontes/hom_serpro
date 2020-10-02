<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use App\Models\ContratoitemServico;
use App\Http\Traits\Formatador;
use App\Models\Indicador;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ServicoRequest as StoreRequest;
use App\Http\Requests\ServicoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Route;

/**
 * Class ServicoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratoServicoCrudController extends CrudController
{
    use Formatador;

    public function setup()
    {

        $contrato_id = Route::current()->parameter('contrato_id');
        $contrato = Contrato::where('id', '=', $contrato_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'))->first();

        if (!$contrato) {
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Servico');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/meus-contratos/' . $contrato_id . '/servicos');
        $this->crud->setEntityNameStrings('serviço', 'serviços');
        $this->crud->addButtonFromView('top', 'voltar', 'voltarmeucontrato', 'end');

        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        $this->crud->addClause('leftJoin'
            , 'contratoitem_servico', 'contratoitem_servico.servico_id', '=', 'servicos.id'
        );
        $this->crud->addClause('leftJoin'
            , 'contratoitens', 'contratoitens.id', '=', 'contratoitem_servico.contratoitem_id'
        );
        // Apenas ocorrencias deste contrato_id
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);

        $this->crud->addClause('select', [
            DB::raw('contratoitem_servico.id as contratoitem_servico_id'),
            'contratoitens.descricao_complementar',
            // Tabela principal deve ser sempre a última da listagem!
            'servicos.*'
        ]);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        (backpack_user()->can('contrato_servico_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contrato_servico_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contrato_servico_deletar')) ? $this->crud->allowAccess('delete') : null;

        $this->crud->addButtonFromView('line', 'moreindicadores', 'moreindicadores', 'end');

        $this->crud->addColumns($this->columns());
        $this->crud->addFields($this->fields($contrato_id));

        // add asterisk for fields that are required in ServicoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here

        $request->request->set('valor', $this->retornaFormatoAmericano($request->valor));

        $redirect_location = parent::storeCrud($request);

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $request->request->set('valor', $this->retornaFormatoAmericano($request->valor));

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    private function fields(string $contrato_id): array
    {
        return [
            [   // Hidden
                'name' => 'contrato_id',
                'type' => 'hidden',
                'default' => $contrato_id,
            ],
            [
                'label' => 'Item do Contrato',
                'type' => 'select2_multiple',
                'name' => 'contratoItens',
                'entity' => 'Contratoitem',
                'attribute' => 'descricao_item',
                'model' => "App\Models\Contratoitem",
                'pivot' => true,
                'options' => (function ($query) use ($contrato_id) {
                    return $query->where('contrato_id',$contrato_id)->get();
                }),
            ],
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
                'name' => 'detalhe',
                'label' => 'Detalhe',
                'type' => 'textarea',
                'attributes' => [
                    'onfocusout' => "maiuscula(this)"
                ],
            ],
            [
                'name' => 'valor',
                'label' => 'Valor',
                'type' => 'money',
                'attributes' => [
                    'id' => 'valor',
                ], // allow decimals
                'prefix' => "R$",
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

    private function columns(): array
    {
        return [
            [
                'name' => 'nome',
                'label' => 'Nome',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('servicos.nome', 'ilike', "%" . $searchTerm . "%");
                },
            ],
            [
                'name' => 'descricao_complementar',
                'label' => 'Item do Contrato',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('servicos.nome', 'ilike', "%" . $searchTerm . "%");
                },
            ],
            [
                'name' => 'detalhe',
                'label' => 'Detalhe',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('servicos.detalhe', 'ilike', "%" . $searchTerm . "%");
                }
            ],
            [
                'name' => 'valor',
                'label' => 'Valor',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
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
}
