<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contratostatusprocesso;
use App\Models\Contrato;
use App\Models\Codigo;
use App\Models\Codigoitem;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratostatusprocessoRequest as StoreRequest;
use App\Http\Requests\ContratostatusprocessoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
/**
 * Class ContratostatusprocessoCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 */
class ContratostatusprocessoCrudController extends CrudController
{
    public function setup()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        $contrato = Contrato::where('id','=',$contrato_id)
            ->where('unidade_id','=',session()->get('user_ug_id'))->first();
        if(!$contrato) {
            abort('403', config('app.erro_permissao'));
        }
        $numeroProcesso = $contrato->processo;
        $con = $contrato->where('id', '=', $contrato_id)
            ->pluck('numero', 'id')
            ->toArray();
        $arrayCodigos = Codigo::where('codigos.descricao', 'Status Processo')
                    ->join('codigoitens', 'codigoitens.codigo_id', '=', 'codigos.id')
                    ->orderby('codigoitens.descres')
                    ->pluck('codigoitens.descres', 'codigoitens.id');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratostatusprocesso');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/'.$contrato_id.'/status');
        $this->crud->setEntityNameStrings('Status do contrato', 'Status do contrato');
        $this->crud->addClause('select', 'contratostatusprocessos.*');
        $this->crud->addClause('join', 'codigoitens', 'codigoitens.id', '=', 'contratostatusprocessos.situacao');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('statusprocesso_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('statusprocesso_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('statusprocesso_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $campos = $this->Campos($con, $numeroProcesso, $arrayCodigos);
        // $campos = $this->Campos($con, $arrayProcessos, $arrayCodigos);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in ContratoprepostoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->adicionaFiltros();
    }
    // colunas para listagem
    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getContrato',
                'label' => 'Número do instrumento', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'processo',
                'label' => 'Número do Processo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'data_inicio',
                'label' => 'Data início',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'data_fim',
                'label' => 'Data fim',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'textarea',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratostatusprocessos.status', 'ilike', "%" . $searchTerm . "%");
                },

            ],
            [
                'name' => 'unidade',
                'label' => 'Unidade',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getNomeSituacao',
                'label' => 'Situação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getNomeSituacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('codigoitens.descres', 'ilike', "%" . utf8_encode(utf8_decode(strtoupper($searchTerm))) . "%");
                },
            ],
            [
                'name' => 'getQuantidadeDias',
                'label' => 'Qtd. dias', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getQuantidadeDias', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
        ];
        return $colunas;
    }
    // campos para o formulário
    public function campos($con, $numeroProcesso, $arrayCodigos)
    // public function campos($con, $arrayProcessos, $arrayCodigos)
    {
        $campos = [
            [ // select_from_array
                'name' => 'contrato_id',
                'label' => "Número do instrumento",
                'type' => 'select_from_array',
                'options' => $con,
                'allows_null' => false,
                'attributes' => [
                    // 'readonly'=>'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ],
            ],
            [
                'name' => 'processo',
                'label' => 'Número Processo',
                'type' => 'numprocesso',
                'default' => $numeroProcesso
            ],
            [
                'name' => 'data_inicio',
                'label' => 'Data Início',
                'type' => 'date',
                'format' => 'd/m/Y',
                // 'tab' => 'Outras Informações',
            ],
            [
                'name' => 'data_fim',
                'label' => 'Data Fim',
                'type' => 'date',
                'format' => 'd/m/Y',
                // 'tab' => 'Outras Informações',
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'textarea',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ]
            ],
            [
                'name' => 'unidade',
                'label' => 'Unidade',
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ]
            ],
            [
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'select_from_array',
                'options' => $arrayCodigos,
                'allows_null' => true,
            ],
        ];
        return $campos;
    }

    /**
     * Adiciona todos os filtros desejados para esta funcionalidade
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaFiltros()
    {
        // $this->adicionaFiltroNumeroProcesso();
        $this->adicionaFiltroDataInicio();
        $this->adicionaFiltroDataFim();
        // $this->adicionaFiltroStatus();
        // $this->adicionaFiltroUnidade();
        $this->adicionaFiltroSituacao();
    }
    private function retornaSituacoes()
    {
        $objCodigoId = Codigo::where('descricao', 'Status Processo')->select('id')->first();
        $codigoId = $objCodigoId->id;
        $dados = Codigoitem::select('id', 'descres');
        $dados->where('codigo_id', $codigoId);
        $dados->orderBy('descres');
        return $dados->pluck('descres', 'id')->toArray();
    }
    private function adicionaFiltroSituacao()
    {
        $campo = [
            'name' => 'situacao',
            'type' => 'select2',
            'label' => 'Situação'
        ];
        $situacoes = $this->retornaSituacoes();
        $this->crud->addFilter(
            $campo,
            $situacoes,
            function ($value) {
                $this->crud->addClause('where', 'contratostatusprocessos.situacao', $value);
            }
        );
    }
    private function adicionaFiltroDataFim()
    {
        $campo = [
            'name' => 'data_fim',
            'type' => 'date_range',
            'label' => 'Data fim'
        ];
        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratostatusprocessos.data_fim', '>=', $dates->from . ' 00:00:00');
                $this->crud->addClause('where', 'contratostatusprocessos.data_fim', '<=', $dates->to . ' 23:59:59');
            }
        );
    }
    private function adicionaFiltroDataInicio()
    {
        $campo = [
            'name' => 'data_inicio',
            'type' => 'date_range',
            'label' => 'Data início'
        ];
        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratostatusprocessos.data_inicio', '>=', $dates->from . ' 00:00:00');
                $this->crud->addClause('where', 'contratostatusprocessos.data_inicio', '<=', $dates->to . ' 23:59:59');
            }
        );
    }
    private function adicionaFiltroUnidade()
    {
        $campo = [
            'name' => 'unidade',
            'type' => 'select2_multiple',
            'label' => 'Unidade'
        ];
        // $contratos = $this->retornaContratos();
        $unidades = $this->retornaUnidades();
        $this->crud->addFilter(
            $campo,
            $unidades,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'contratostatusprocessos.unidade', json_decode($value));
            }
        );
    }

    public function adicionaFiltroNumeroProcesso()
    {
        $campo = [
            'name' => 'processo',
            'type' => 'select2_multiple',
            'label' => 'Número Processo'
        ];
        // $contratos = $this->retornaContratos();
        $processos = $this->retornaProcessos();
        $this->crud->addFilter(
            $campo,
            $processos,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'contratostatusprocessos.processo', json_decode($value));
            }
        );
    }
    private function adicionaFiltroStatus()
    {
        $campo = [
            'name' => 'status',
            'type' => 'text',
            'label' => 'Status'
        ];
        // $contratos = $this->retornaContratos();
        $status = $this->retornaStatus();
        $this->crud->addFilter(
            $campo,
            $status,
            function ($value) {
                $this->crud->addClause('whereIn', 'contratostatusprocessos.status', json_decode($value));

                // $query->orWhere('codigoitens.descres', 'ilike', "%" . utf8_encode(utf8_decode(strtoupper($searchTerm))) . "%");


            }
        );
    }
    private function retornaStatus()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        return $dados = Contratostatusprocesso::where('contrato_id', '=', $contrato_id)
        ->pluck('status', 'status')
        ->toArray();
    }
    private function retornaContratos()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        return $dados = Contrato::where('id', '=', $contrato_id)
        ->orderBy('id')
        ->pluck('numero', 'id')
        ->toArray();
    }
    private function retornaProcessos()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        return $dados = Contratostatusprocesso::where('contrato_id', '=', $contrato_id)
        ->pluck('processo', 'processo')
        ->toArray();
    }
    private function retornaUnidades()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        return $dados = Contratostatusprocesso::where('contrato_id', '=', $contrato_id)
        ->pluck('unidade', 'unidade')
        ->toArray();
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
    public function show($id)
    {
        $content = parent::show($id);
        // $this->crud->removeColumn('contrato_id');
        return $content;
    }
}
