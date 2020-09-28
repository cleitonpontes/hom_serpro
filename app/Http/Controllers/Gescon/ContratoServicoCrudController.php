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

        $itens = $contrato->itens()->get()->pluck('descricao_complementar', 'id')->toArray();


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

        //LEMBRAR DE FAZER OS ACESSOS
        $this->crud->allowAccess('show');

        $this->crud->addButtonFromView('line', 'moreindicadores', 'moreindicadores', 'end');

        $this->crud->addClause('leftJoin'
            , 'contratoitem_servico', 'contratoitem_servico.servico_id', '=', 'servicos.id'
        );
        $this->crud->addClause('leftJoin'
            , 'contratoitens', 'contratoitens.id', '=', 'contratoitem_servico.contratoitem_id'
        );
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

        $this->crud->addColumns($this->columns());
        $this->crud->addFields($this->fields($contrato_id, $itens));

        // add asterisk for fields that are required in ServicoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
//        dd($request->all());
        // your additional operations before save here

//        $itens = $request->contratoitem_id;
        $request->request->set('valor', $this->retornaFormatoAmericano($request->valor));
//
//        try {
//            // Begin a transaction
//            DB::beginTransaction();
//
//            $redirect_location = parent::storeCrud($request);
//
//            foreach ($itens as $item) {
//                ContratoitemServico::create([
//                    'contratoitem_id' => $item,
//                    'servico_id' => $this->crud->entry->id,
//                ]);
//            }
//            // Commit the transaction
//            DB::commit();
//        } catch (Exception $e) {
//            // An error occured; cancel the transaction...
//            DB::rollback();
//
//            // and throw the error again.
//            throw $e;
//        }

        $redirect_location = parent::storeCrud($request);

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        //TODO FAZER O UPDATE DOS ITENS DO CONTRATO
        $itens = $request->contratoitem_id;
        $request->request->set('valor', $this->retornaFormatoAmericano($request->valor));

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    private function fields(string $contrato_id
        , array $itens): array
    {
        return [
            [   // Hidden
                'name' => 'contrato_id',
                'type' => 'hidden',
                'default' => $contrato_id,
            ],
//            [
//                'name' => 'contratoitem_id',
//                'label' => 'Item do Contrato',
//                'type' => 'select2_from_array',
//                'options' => $itens,
//                'allows_null' => false,
//                'placeholder' => 'Selecione',
//                'allows_multiple' => true,
//            ],
            [
                'label' => 'Item do Contrato',
                'type' => 'select2_multiple',
                'name' => 'contratoItens',
                'entity' => 'Contratoitem',
                'attribute' => 'descricao_complementar',
                'model' => "App\Models\Contratoitem",
                'pivot' => true,
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