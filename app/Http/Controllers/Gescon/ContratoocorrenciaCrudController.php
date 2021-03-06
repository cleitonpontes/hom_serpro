<?php

namespace App\Http\Controllers\Gescon;

use App\Jobs\OcorrenciaMailJob;
use App\Mail\EmailOcorrencia;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Contratoocorrencia;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratoocorrenciaRequest as StoreRequest;
use App\Http\Requests\ContratoocorrenciaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\Mail;

/**
 * Class ContratoocorrenciaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratoocorrenciaCrudController extends CrudController
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
        $this->crud->setModel('App\Models\Contratoocorrencia');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/meus-contratos/' . $contrato_id . '/ocorrencias');
        $this->crud->setEntityNameStrings('OcorrĂȘncia do Contrato', 'OcorrĂȘncias - Contrato');
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
//            $this->crud->AllowAccess('update');
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->orderBy('numero', 'asc');

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);



        $con = $contrato->where('id', '=', $contrato_id)
            ->pluck('numero', 'id')
            ->toArray();

        $situacao = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'SituaĂ§ĂŁo OcorrĂȘncia');
        })
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();

        $novasit = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'SituaĂ§ĂŁo OcorrĂȘncia');
        })
            ->where('descricao', '<>', 'Pendente')
            ->where('descricao', '<>', 'Conclusiva')
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();

        $numocorrencia = Contratoocorrencia::where('contrato_id', '=', $contrato_id)
            ->whereIn('situacao',  [128,130])
            ->orderBy('numero')
            ->pluck('numero', 'id')
            ->toArray();

        $this->crud->addFields([
            [ // select_from_array
                'name' => 'contrato_id',
                'label' => "NĂșmero do instrumento",
                'type' => 'select_from_array',
                'options' => $con,
                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'situacao',
                'label' => "SituaĂ§ĂŁo",
                'type' => 'select_from_array',
                'options' => $situacao,
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'numeroocorrencia',
                'label' => "OcorrĂȘncia ConcluĂ­da",
                'type' => 'select_from_array',
                'options' => $numocorrencia,
                'placeholder' => 'Selecione',
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'novasituacao',
                'label' => "Nova SituaĂ§ĂŁo",
                'type' => 'select_from_array',
                'options' => $novasit,
                'placeholder' => 'Selecione',
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                'name' => 'data',
                'label' => 'Data',
                'type' => 'date',
            ],
            [
                'name' => 'ocorrencia',
                'label' => 'OcorrĂȘncia',
                'type' => 'textarea',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ]
            ],
            [
                'name' => 'notificapreposto', // the name of the db column
                'label' => 'Notifica Preposto?', // the input label
                'type' => 'radio',
                'options' => [
                    0 => 'NĂŁo',
                    1 => 'Sim'
                ],
                // optional
                'inline' => true, // show the radios all on the same line?
            ],
            [   // Email
                'name' => 'emailpreposto',
                'label' => 'E-mail Preposto',
                'type' => 'email'
            ],
            [
                'name' => 'arquivos',
                'label' => 'Arquivos',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'local'
            ],

        ]);


        // add asterisk for fields that are required in ContratoocorrenciaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
//        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    /**
     * @param UpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $request->request->set('ocorrencia', trim(str_replace('  ', '', str_replace('\r', '',
            str_replace('\n', '', strip_tags($request->input('ocorrencia')))))));

        $situacao = $request->input('situacao');

        if ($situacao != 132) {
            $request->request->set('novasituacao', null);
            $request->request->set('numeroocorrencia', null);
        }

        $numero = Contratoocorrencia::where('contrato_id', '=', $request->input('contrato_id'))
            ->orderBy('numero', 'desc')
            ->first();

        if ($numero) {
            $request->request->set('numero', $numero->numero + 1);
        } else {
            $request->request->set('numero', 1);
        }

        $request->request->set('user_id', backpack_user()->id);

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);

        if ($situacao == 132) {
            $ocorrencia = Contratoocorrencia::find($request->input('numeroocorrencia'));
            $ocorrencia->situacao = $request->input('novasituacao');
            $ocorrencia->save();
        }

        if ($request->input('notificapreposto')) {
            $dadosOcorrencia = $this->getDadosOcorrencia($id = $this->crud->entry->id);

            OcorrenciaMailJob::dispatch($dadosOcorrencia);
        }


        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function getDadosOcorrencia($id)
    {

        $ocorrencia = Contratoocorrencia::find($id);
        $contrato = Contrato::find($ocorrencia->contrato_id);
        $orgao = $contrato->getOrgao();
        $unidade = $contrato->getUnidade();
        $fornecedor = $contrato->getFornecedor();
        $usuario = $ocorrencia->getUserCpfInibido();
        $situacao = $ocorrencia->getSituacao();
        $arquivos = $ocorrencia->getArquivos();

        $dadosOcorrencia = [
            'orgao' => $orgao,
            'unidade' => $unidade,
            'fornecedor' => $fornecedor,
            'contrato_numero' => $contrato->numero,
            'user' => $usuario,
            'emailpreposto' => $ocorrencia->emailpreposto,
            'data' => $ocorrencia->data,
            'textoocorrencia' => $ocorrencia->ocorrencia,
            'situacao' => $situacao,
            'arquivos' => $arquivos
        ];

        return $dadosOcorrencia;
    }

    public function update(UpdateRequest $request)
    {
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
        $this->crud->removeColumn('user_id');
        $this->crud->removeColumn('numeroocorrencia');
        $this->crud->removeColumn('novasituacao');
        $this->crud->removeColumn('situacao');

        return $content;
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'numero',
                'label' => 'NĂșmero',
                'type' => 'text',
            ],
            [
                'name' => 'getContrato',
                'label' => 'NĂșm. do instrumento', // Table column heading
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
                'name' => 'getUser',
                'label' => 'UsuĂĄrio', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUser', // the method in your Model
                'orderable' => true,
                'limit' => 255,
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
                'name' => 'data',
                'label' => 'Data',
                'type' => 'date',
            ],
            [
                'name' => 'ocorrencia',
                'label' => 'OcorrĂȘncia',
                'type' => 'textarea',
                'limit' => 9999,
            ],
            [
                'name' => 'notificapreposto',
                'label' => 'Notifica Preposto',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => [0 => 'NĂŁo', 1 => 'Sim']
            ],
            [
                'name' => 'emailpreposto',
                'label' => 'E-mail Preposto',
                'type' => 'text',
            ],
            [
                'name' => 'getNumeroOcorrencia',
                'label' => 'OcorrĂȘncia Alterada', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getNumeroOcorrencia', // the method in your Model
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
                'name' => 'getNovaSituacao',
                'label' => 'Nova SituaĂ§ĂŁo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getNovaSituacao', // the method in your Model
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
                'name' => 'getSituacao',
                'label' => 'SituaĂ§ĂŁo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSituacao', // the method in your Model
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
                'name' => 'arquivos',
                'label' => 'Arquivos',
                'type' => 'upload_multiple',
                'disk' => 'local',
                'upload' => true,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
            ]
        ];

        return $colunas;
    }



}
