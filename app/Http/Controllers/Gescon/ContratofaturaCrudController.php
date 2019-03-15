<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use App\Models\Empenho;
use App\Models\Tipolistafatura;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratofaturaRequest as StoreRequest;
use App\Http\Requests\ContratofaturaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ContratofaturaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratofaturaCrudController extends CrudController
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
        $this->crud->setModel('App\Models\Contratofatura');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/meus-contratos/' . $contrato_id . '/faturas');
        $this->crud->setEntityNameStrings('Fatura do Contrato', 'Faturas - Contrato');
        $this->crud->addClause('join', 'tipolistafatura', 'tipolistafatura.id', '=', 'contratofaturas.tipolistafatura_id');
        $this->crud->addClause('join', 'justificativafatura', 'justificativafatura.id', '=', 'contratofaturas.justificativafatura_id');
        $this->crud->addClause('select', 'contratofaturas.*');

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
            $this->crud->AllowAccess('update');
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $con = $contrato->where('id', '=', $contrato_id)
            ->pluck('numero', 'id')
            ->toArray();

        $tipolistafatura = Tipolistafatura::where('situacao',true)
            ->orderBy('nome', 'ASC')
            ->pluck('nome','id')
            ->toArray();



        $campos = $this->Campos($con, $tipolistafatura, $contrato_id);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in ContratofaturaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getContrato',
                'label' => 'Contrato', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('contrato_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                    });
//                },
            ],
            [
                'name' => 'getTipoListaFatura',
                'label' => 'Tipo Lista Fatura', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoListaFatura', // the method in your Model
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('tipolistafatura.nome', 'like', "%" . strtoupper($searchTerm) . "%");
//                    $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'getJustificativaFatura',
                'label' => 'Justificativa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getJustificativaFatura', // the method in your Model
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('justificativafatura.nome', 'like', "%$searchTerm%");
                },
            ],
            [
                'name' => 'getSfpadrao',
                'label' => 'Doc. Origem Siafi', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSfpadrao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('empenhos.numero', 'like', "%" . strtoupper($searchTerm) . "%");
//                },
            ],
            [
                'name' => 'numero',
                'label' => 'Número', // Table column heading
                'type' => 'text',
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
                'name' => 'emissao',
                'label' => 'Dt. Emissão', // Table column heading
                'type' => 'date',
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
                'name' => 'vencimento',
                'label' => 'Dt. Vencimento', // Table column heading
                'type' => 'date',
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
                'name' => 'prazo',
                'label' => 'Dt. Prazo Pagto.', // Table column heading
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'formatValor',
                'label' => 'Valor', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatValor', // the method in your Model
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
                'name' => 'formatJuros',
                'label' => 'Juros', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatJuros', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'formatMulta',
                'label' => 'Multa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatMulta', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'formatGlosa',
                'label' => 'Glosa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatGlosa', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'formatValorLiquido',
                'label' => 'Valor Líquido a pagar', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatValorLiquido', // the method in your Model
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
                'name' => 'processo',
                'label' => 'Processo', // Table column heading
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'protocolo',
                'label' => 'Dt. Protocolo', // Table column heading
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'ateste',
                'label' => 'Dt. Ateste', // Table column heading
                'type' => 'date',
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
            [ // n-n relationship (with pivot table)
                'name'      => 'empenhos',
                'label'     => 'Empenhos',
                'type'      => 'select_multiple',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'entity'    => 'empenhos',
                'attribute' => 'numero',
                'model'     => Empenho::class,
                'pivot'     => true,
            ],
            [
                'name' => 'repactuacao',
                'label' => 'Repactuação',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => [0 => 'Não', 1 => 'Sim']
            ],
            [
                'name' => 'infcomplementar',
                'label' => 'Informações Complementares', // Table column heading
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'mesref',
                'label' => 'Mês Referência', // Table column heading
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'anoref',
                'label' => 'Ano Referência', // Table column heading
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'select_from_array',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => config('app.situacao_fatura')
            ],


        ];

        return $colunas;

    }

    public function Campos($contrato,$tipolistafatura, $contrato_id)
    {

        $con = Contrato::find($contrato_id);

        $campos = [
            [ // select_from_array
                'name' => 'contrato_id',
                'label' => "Contrato",
                'type' => 'select_from_array',
                'options' => $contrato,
                'allows_null' => false,
                'attributes' => [
                    'readonly'=>'readonly',
//                    'disabled'=>'disabled',
                ], // chan
                'tab' => 'Dados Fatura',
            ],
            [ // select_from_array
                'name' => 'tipolistafatura_id',
                'label' => "Tipo Lista Fatura",
                'type' => 'select2_from_array',
                'options' => $tipolistafatura,
                'allows_null' => true,
                'tab' => 'Dados Fatura',
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'numero',
                'label' => "Número",
                'type' => 'text',
                'attributes' => [
                    'maxlength'=>'17',
                    'onkeyup' => "maiuscula(this)",
//                    'disabled'=>'disabled',
                ],
                'tab' => 'Dados Fatura',
            ],
            [ // select_from_array
                'name' => 'emissao',
                'label' => "Dt. Emissão",
                'type' => 'date',
                'tab' => 'Dados Fatura',
            ],
            [ // select_from_array
                'name' => 'vencimento',
                'label' => "Dt. Vencimento",
                'type' => 'date',
                'tab' => 'Dados Fatura',
            ],

            [   // Number
                'name' => 'valor',
                'label' => 'Valor',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'valor',
                ], // allow decimals
                'prefix' => "R$",
                'tab' => 'Dados Fatura',
            ],
            [   // Number
                'name' => 'juros',
                'label' => 'Juros',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'juros',
                ], // allow decimals
                'prefix' => "R$",
                'tab' => 'Dados Fatura',
            ],
            [   // Number
                'name' => 'multa',
                'label' => 'Multa',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'multa',
                ], // allow decimals
                'prefix' => "R$",
                'tab' => 'Dados Fatura',
            ],
            [   // Number
                'name' => 'glosa',
                'label' => 'Glosa',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'glosa',
                ], // allow decimals
                'prefix' => "R$",
                'tab' => 'Dados Fatura',
            ],
            [ // select_from_array
                'name' => 'processo',
                'label' => "Processo",
                'type' => 'numprocesso',
                'tab' => 'Outras Informações',
            ],
            [ // select_from_array
                'name' => 'protocolo',
                'label' => "Dt. Protocolo",
                'type' => 'date',
                'tab' => 'Outras Informações',
            ],
            [ // select_from_array
                'name' => 'ateste',
                'label' => "Dt. Ateste",
                'type' => 'date',
                'tab' => 'Outras Informações',
            ],
            [ // select_from_array
                'name' => 'repactuacao',
                'label' => "Repactuação?",
                'type' => 'radio',
                'options' => [0 => 'Não', 1 => 'Sim'],
                'default'    => 0,
                'inline'      => true,
                'tab' => 'Outras Informações',
            ],
            [ // select_from_array
                'name' => 'infcomplementar',
                'label' => "Informações Complementares",
                'type' => 'text',
                'tab' => 'Outras Informações',
            ],
            [ // select_from_array
                'name' => 'mesref',
                'label' => "Mês Referência",
                'type' => 'select2_from_array',
                'options' => config('app.meses_referencia_fatura'),
                'allows_null' => false,
                'tab' => 'Outras Informações',
            ],
            [ // select_from_array
                'name' => 'anoref',
                'label' => "Ano Referência",
                'type' => 'select2_from_array',
                'options' => config('app.anos_referencia_fatura'),
                'default'    => date('Y'),
                'allows_null' => false,
                'tab' => 'Outras Informações',
            ],
            [       // Select2Multiple = n-n relationship (with pivot table)
                'label' => "Empenhos",
                'type' => 'select2_multiple',
                'name' => 'empenhos', // the method that defines the relationship in your Model
                'entity' => 'empenhos', // the method that defines the relationship in your Model
                'attribute' => 'numero', // foreign key attribute that is shown to user
                'model' => "App\Models\Empenho", // foreign key model
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                'options' => (function ($query) use ($con) {
                    return $query->orderBy('numero', 'ASC')
                        ->where('unidade_id',session()->get('user_ug_id'))
                        ->where('fornecedor_id',$con->fornecedor_id)
                        ->get();
                }),
                'tab' => 'Outras Informações',
                // 'select_all' => true, // show Select All and Clear buttons?
            ],
            [ // select_from_array
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select_from_array',
                'options' => config('app.situacao_fatura'),
                'default'    => 'PEN',
                'attributes' => [
                    'readonly'=>'readonly',
                    'disabled'=>'disabled',
                ],
                'allows_null' => false,
                'tab' => 'Outras Informações',
            ],

        ];

        return $campos;
    }

    public function store(StoreRequest $request)
    {
        $situacao = $request->input('situacao');
        $contrato_id = $request->input('contrato_id');
        // your additional operations before save here
        if($situacao == 'PEN'){
            $redirect_location = parent::storeCrud($request);
            return $redirect_location;
        }else{
            \Alert::warning('Para incluir a Situação deve ser Pendente!')->flash();
            return redirect('/gescon/meus-contratos/'.$contrato_id.'/faturas');
        }

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry

    }

    public function update(UpdateRequest $request)
    {
        $contrato_id = $request->input('contrato_id');
        $situacao = $request->input('situacao');

        if($situacao == 'PEN'){
            $redirect_location = parent::updateCrud($request);
            return $redirect_location;
        }else{
            \Alert::error('Essa Fatura não pode ser alterada!')->flash();
            return redirect('/gescon/meus-contratos/'.$contrato_id.'/faturas');
        }


    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('contrato_id');
        $this->crud->removeColumn('tipolistafatura_id');
        $this->crud->removeColumn('justificativafatura_id');
        $this->crud->removeColumn('sfpadrao_id');
        $this->crud->removeColumn('valor');
        $this->crud->removeColumn('juros');
        $this->crud->removeColumn('multa');
        $this->crud->removeColumn('glosa');
        $this->crud->removeColumn('valorliquido');

        return $content;
    }
}
