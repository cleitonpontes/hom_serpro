<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Indicador;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratoItemServicoIndicadorRequest as StoreRequest;
use App\Http\Requests\ContratoItemServicoIndicadorRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Route;

/**
 * Class ContratoItemServicoIndicadorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratoItemServicoIndicadorCrudController extends CrudController
{
    public function setup()
    {
        $contratoitem_servico_id = Route::current()->parameter('contratoitem_servico_id');
        $indicadores = Indicador::all()->pluck('nome', 'id')->toArray();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ContratoItemServicoIndicador');
//        $this->crud->setRoute(config('backpack.base.route_prefix') . '/contratoitemservicoindicador');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/meus-servicos/' . $contratoitem_servico_id . '/indicadores');
        $this->crud->setEntityNameStrings('indicador', 'indicadores');
        $this->crud->removeButton('create');
        $this->crud->addButtonFromView('top', 'vincular', 'vincularIndicador');

        //todo arrumar o botao voltar para o local correto
        $this->crud->addButtonFromView('top', 'voltar', 'voltarmeucontrato', 'end');
        $this->crud->enableExportButtons();

        //todo LEMBRAR DE FAZER OS ACESSOS
        $this->crud->allowAccess('show');

        $this->crud->addButtonFromView('line', 'moreglosas', 'moreglosas', 'end');


        // Apenas ocorrencias deste contratoitem_servico_id
        $this->crud->addClause('where', 'contratoitem_servico_indicador.contratoitem_servico_id', '=', $contratoitem_servico_id);



        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

//        $this->crud->setFromDb();

        $this->crud->addColumns($this->colunas());
        $this->crud->addFields($this->campos($contratoitem_servico_id, $indicadores));

        // add asterisk for fields that are required in ContratoItemServicoIndicadorRequest
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

    private function campos(string $contratoitem_servico_id
        , array $indicadores): array
    {
        return [
            [   // Hidden
                'name' => 'contratoitem_servico_id',
                'type' => 'hidden',
                'default' => $contratoitem_servico_id,
            ],
            [ // select_from_array
                'name' => 'indicador_id',
                'label' => 'Indicador',
                'type' => 'select2_from_array',
                'options' => $indicadores,
                'allows_null' => false,
                'placeholder' => 'Selecione',
//                'allows_multiple' => true,
//                'tab' => 'Dados do serviço',
            ],
            [
                'name' => 'tipo_afericao',
                'label' => 'Aferição',
                'type' => 'radio',
                'options' => [0 => 'Percentual', 1 => 'Número de Ocorrências'],
                'default' => 0,
                'inline' => true,
//                'tab' => 'Dados do serviço',
            ],
            [   // Number
                'name' => 'meta',
                'label' => 'Meta',
                'type' => 'money',
                // optionals
                'attributes' => [
                    'id' => 'meta',
                ], // allow decimals
            ],
            [ // select_from_array
                'name' => 'periodicidade',
                'label' => 'periodicidade',
                'type' => 'select2_from_array',
                'options' => ['Anual','Mensal','Semanal','Diária','Única'],
                'allows_null' => false,
                'placeholder' => 'Selecione',
//                'allows_multiple' => true,
//                'tab' => 'Dados do serviço',
            ],
//            [
//                'name' => 'detalhe',
//                'label' => 'Detalhe',
//                'type' => 'textarea',
//                'attributes' => [
//                    'onfocusout' => "maiuscula(this)"
//                ],
////                'tab' => 'Dados do serviço',
//            ],
//            [   // Number
//                'name' => 'valor',
//                'label' => 'Valor',
//                'type' => 'money',
//                // optionals
//                'attributes' => [
//                    'id' => 'valor',
//                ], // allow decimals
//                'prefix' => "R$",
////                'tab' => 'Dados do serviço',
//            ],
//            [
//                'name' => 'situacao',
//                'label' => "Situação",
//                'type' => 'select2_from_array',
//                'options' => [1 => 'Ativo', 0 => 'Inativo'],
//                'allows_null' => false,
////                'tab' => 'Dados do serviço',
//            ],
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
//            [
//                'name' => 'nome',
//                'label' => 'Nome',
//                'type' => 'text',
//                'orderable' => true,
//                'visibleInTable' => true,
//                'visibleInModal' => true,
//                'visibleInExport' => true,
//                'visibleInShow' => true,
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('servicos.nome', 'ilike', "%" . $searchTerm . "%");
//                },
//            ],
            [
                'name' => 'getIndicador',
                'label' => 'Indicador', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getIndicador', // the method in your Model
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('tipolistafatura.nome', 'like', "%" . strtoupper($searchTerm) . "%");
////                    $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
//                },
            ],
            [
                'name' => 'tipo_afericao',
                'label' => 'Tipo de Afericao',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => [0 => 'Percentual', 1 => 'Número de Ocorrências'],
            ],
//            [
//                'name' => 'descricao_complementar',
//                'label' => 'Item do Contrato',
//                'type' => 'text',
//                'orderable' => true,
//                'visibleInTable' => true,
//                'visibleInModal' => true,
//                'visibleInExport' => true,
//                'visibleInShow' => true,
////                'searchLogic' => function (Builder $query, $column, $searchTerm) {
////                    $query->orWhere('servicos.nome', 'ilike', "%" . $searchTerm . "%");
////                },
//            ],
//            [
//                'name' => 'detalhe',
//                'label' => 'Detalhe',
//                'type' => 'text',
//                'orderable' => true,
//                'visibleInTable' => true,
//                'visibleInModal' => true,
//                'visibleInExport' => true,
//                'visibleInShow' => true,
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('servicos.detalhe', 'ilike', "%" . $searchTerm . "%");
//                }
//            ],
//            [
//                'name' => 'situacao',
//                'label' => 'Situação',
//                'type' => 'boolean',
//                'orderable' => true,
//                'visibleInTable' => true,
//                'visibleInModal' => true,
//                'visibleInExport' => true,
//                'visibleInShow' => true,
//                'options' => [0 => 'Inativo', 1 => 'Ativo']
//            ],
        ];
    }
}
