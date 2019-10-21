<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use App\Models\Contrato;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratoterceirizadoRequest as StoreRequest;
use App\Http\Requests\ContratoterceirizadoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ContratoterceirizadoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratoterceirizadoCrudController extends CrudController
{
    public function setup()
    {

        $contrato_id = \Route::current()->parameter('contrato_id');

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
        $this->crud->setModel('App\Models\Contratoterceirizado');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/meus-contratos/' . $contrato_id . '/terceirizados');
        $this->crud->setEntityNameStrings('Terceirizado do Contrato', 'Terceirizados - Contrato');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addButtonFromView('top', 'voltar', 'voltarmeucontrato', 'end');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        $conresp = $contrato->whereHas('responsaveis', function ($query) {
            $query->whereHas('user', function ($query) {
                $query->where('id', '=', backpack_user()->id);
            })->where('situacao', '=', true);
        })->where('id', '=', $contrato_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'))->first();

        if ($conresp) {
            $this->crud->AllowAccess('create');
            $this->crud->AllowAccess('delete');
            $this->crud->AllowAccess('update');
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->addColumns([
            [
                'name' => 'getContrato',
                'label' => 'Número Contrato', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [
                'name'  => 'cpf',
                'label' => 'CPF',
                'type'  => 'text',
            ],
            [
                'name'  => 'nome',
                'label' => 'Nome',
                'type'  => 'text',
            ],
            [
                'name' => 'getFuncao',
                'label' => 'Função', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getFuncao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [
                'name'  => 'descricao_complementar',
                'label' => 'Descrição Complementar',
                'type'  => 'text',
            ],
            [
                'name'  => 'jornada',
                'label' => 'Jornada',
                'type'  => 'number',
            ],
            [
                'name'  => 'unidade',
                'label' => 'Unidade',
                'type'  => 'text',
            ],
            [
                'name' => 'formatVlrSalario',
                'label' => 'Salário', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrSalario', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],
            [
                'name' => 'formatVlrCusto',
                'label' => 'Custo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrCusto', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],
            [
                'name' => 'getEscolaridade',
                'label' => 'Escolaridade', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getEscolaridade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [   // Date
                'name' => 'data_inicio',
                'label' => 'Data Início',
                'type' => 'date',
            ],
            [   // Date
                'name' => 'data_fim',
                'label' => 'Data Fim',
                'type' => 'date',
            ],
            [
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],
        ]);

        $con = $contrato->where('id', '=', $contrato_id)
            ->pluck('numero', 'id')
            ->toArray();

        $funcoes = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Mão de Obra');
        })->orderBy('descricao')->pluck('descricao', 'id')->toArray();

        $escolaridades = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Escolaridade');
        })->orderBy('descricao')->pluck('descricao', 'id')->toArray();

        $this->crud->addFields([
            [
                'name' => 'cpf',
                'label' => 'CPF',
                'type' => 'cpf',
                'tab' => 'Dados Pessoais',
            ],
            [
                'name' => 'nome',
                'label' => 'Nome Completo',
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ],
                'tab' => 'Dados Pessoais',
            ],
            [ // select_from_array
                'name' => 'escolaridade_id',
                'label' => "Escolaridade",
                'type' => 'select2_from_array',
                'options' => $escolaridades,
                'allows_null' => true,
                'tab' => 'Dados Pessoais',
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'contrato_id',
                'label' => "Número Contrato",
                'type' => 'select_from_array',
                'options' => $con,
                'allows_null' => false,
                'tab' => 'Dados Funcionais',
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'funcao_id',
                'label' => "Função",
                'type' => 'select2_from_array',
                'options' => $funcoes,
                'allows_null' => true,
                'tab' => 'Dados Funcionais',
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                'name' => 'descricao_complementar',
                'label' => 'Descrição Complementar',
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ],
                'tab' => 'Dados Funcionais',
            ],
            [
                'name' => 'jornada',
                'label' => 'Jornada',
                'type' => 'jornada',
                'tab' => 'Dados Funcionais',
            ],
            [
                'name' => 'unidade',
                'label' => 'Unidade',
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ],
                'tab' => 'Dados Funcionais',
            ],
            [
                'name' => 'salario',
                'label' => 'Salário',
                'type' => 'money',
                'attributes' => [
                    'id' => 'salario',
                ],
                'prefix' => "R$",
                'tab' => 'Dados Funcionais',
            ],
            [
                'name' => 'custo',
                'label' => 'Custo',
                'type' => 'money',
                'attributes' => [
                    'id' => 'custo',
                ],
                'prefix' => "R$",
                'tab' => 'Dados Funcionais',
            ],
            [
                'name' => 'data_inicio',
                'label' => 'Data Início',
                'type' => 'date',
                'tab' => 'Dados Funcionais',
            ],
            [
                'name' => 'data_fim',
                'label' => 'Data Desligamento',
                'type' => 'date',
                'tab' => 'Dados Funcionais',
            ],
            [ // select_from_array
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select_from_array',
                'options' => [1 => 'Ativo', 0 => 'Inativo'],
                'allows_null' => false,
                'tab' => 'Dados Funcionais',
//                'attributes' => [
//                    'disabled' => 'disabled',
//                ],
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
        ]);
        // add asterisk for fields that are required in ContratoterceirizadoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        $salario = str_replace(',', '.', str_replace('.','',$request->input('salario')));
        $request->request->set('salario', number_format(floatval($salario),2,'.',''));

        $custo = str_replace(',', '.', str_replace('.','',$request->input('custo')));
        $request->request->set('custo', number_format(floatval($custo),2,'.',''));

        if($request->input('data_fim')){
            $request->request->set('situacao', false);
        }

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $salario = str_replace(',', '.', str_replace('.','',$request->input('salario')));
        $request->request->set('salario', number_format(floatval($salario),2,'.',''));

        $custo = str_replace(',', '.', str_replace('.','',$request->input('custo')));
        $request->request->set('custo', number_format(floatval($custo),2,'.',''));

        if($request->input('data_fim')){
            $request->request->set('situacao', false);
        }
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('contrato_id');
        $this->crud->removeColumn('funcao_id');
        $this->crud->removeColumn('escolaridade_id');
        $this->crud->removeColumn('custo');
        $this->crud->removeColumn('salario');

        return $content;
    }
}
