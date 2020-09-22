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

        $indicadores = Indicador::all()->pluck('nome', 'id')->toArray();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Servico');
//        $this->crud->setRoute(config('backpack.base.route_prefix') . '/servico');
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
//            'contratos.*',
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

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();

        $this->crud->addColumns($this->colunas());
        $this->crud->addFields($this->campos($contrato_id, $itens, $indicadores));

        // add asterisk for fields that are required in ServicoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here

        $itens = $request->contratoitem_id;
        $request->request->set('valor', $this->retornaFormatoAmericano($request->valor));

        try {
            // Begin a transaction
            DB::beginTransaction();

            $redirect_location = parent::storeCrud($request);

            foreach ($itens as $item) {
                ContratoitemServico::create([
                    'contratoitem_id' => $item,
                    'servico_id' => $this->crud->entry->id,
                ]);
            }
            // Commit the transaction
            DB::commit();
        } catch (Exception $e) {
            // An error occured; cancel the transaction...
            DB::rollback();

            // and throw the error again.
            throw $e;
        }

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

    private function campos(string $contrato_id
        , array $itens, array $indicadores): array
    {
        return [
            [   // Hidden
                'name' => 'contrato_id',
                'type' => 'hidden',
                'default' => $contrato_id,
            ],
            [ // select_from_array
                'name' => 'contratoitem_id',
                'label' => 'Item do Contrato',
                'type' => 'select2_from_array',
                'options' => $itens,
                'allows_null' => false,
                'placeholder' => 'Selecione',
                'allows_multiple' => true,
//                'tab' => 'Dados do serviço',
            ],
            [
                'name' => 'nome',
                'label' => 'Nome',
                'type' => 'text',
                'attributes' => [
                    'onfocusout' => "maiuscula(this)",
                    'maxlength' => "255",
                ],
//                'tab' => 'Dados do serviço',
            ],
            [
                'name' => 'detalhe',
                'label' => 'Detalhe',
                'type' => 'textarea',
                'attributes' => [
                    'onfocusout' => "maiuscula(this)"
                ],
//                'tab' => 'Dados do serviço',
            ],
            [   // Number
                'name' => 'valor',
                'label' => 'Valor',
                'type' => 'money',
                // optionals
                'attributes' => [
                    'id' => 'valor',
                ], // allow decimals
                'prefix' => "R$",
//                'tab' => 'Dados do serviço',
            ],
            [
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select2_from_array',
                'options' => [1 => 'Ativo', 0 => 'Inativo'],
                'allows_null' => false,
//                'tab' => 'Dados do serviço',
            ],
//            [
//                'name' => 'indicador',
//                'label' => "Indicador",
//                'type' => 'select2_from_array',
//                'options' => [1 => 'Ativo', 0 => 'Inativo'],
//                'allows_null' => false,
//                'tab' => 'Indicador Associado',
//            ],
//            [
//                'name' => 'indicadores',
//                'label' => 'Indicadores',
//                'type' => 'table2',
//                'indicadores' => $indicadores,
//                'periodicidade' => [1 => 'Anual', 2 => 'Mensal', 3 => 'Semanal'],
//                'entity_singular' => 'indicador', // used on the "Add X" button
//                'columns' => [
//                    'name' => 'Indicador',
//                    'desc' => 'Tipo Aferição',
//                    'meta' => 'Meta',
//                    'price' => 'Periodicidade'
//                ],
//                'max' => 50,
//                'min' => 0,
//                'tab' => 'Indicador Associado',
//            ],


        ];
    }

    private function colunas(): array
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
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('servicos.nome', 'ilike', "%" . $searchTerm . "%");
//                },
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
