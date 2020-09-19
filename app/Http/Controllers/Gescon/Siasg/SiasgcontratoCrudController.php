<?php

namespace App\Http\Controllers\Gescon\Siasg;

use App\Jobs\AtualizaSiasgCompraJob;
use App\Jobs\AtualizaSiasgContratoJob;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\ContratoSiasgIntegracao;
use App\Models\ContratoSiasgIntegracaoNovo;
use App\Models\Siasgcompra;
use App\Models\Siasgcontrato;
use App\Models\Unidade;
use App\XML\ApiSiasg;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SiasgcontratoRequest as StoreRequest;
use App\Http\Requests\SiasgcontratoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class SiasgcontratoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SiasgcontratoCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Siasgcontrato');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/siasg/contratos');
        $this->crud->setEntityNameStrings('contrato', 'Importação SIASG - Contratos');

        $this->crud->addClause('leftjoin', 'siasgcompras', 'siasgcompras.id', '=', 'siasgcontratos.compra_id');
        $this->crud->addClause('leftjoin', 'codigoitens', 'codigoitens.id', '=', 'siasgcontratos.tipo_id');
        $this->crud->addClause('leftjoin', 'unidades', 'unidades.id', '=', 'siasgcontratos.unidade_id');
        $this->crud->addClause('select', 'siasgcontratos.*');
        $this->crud->orderBy('siasgcontratos.updated_at','desc');
//        $this->crud->addClause('where', 'siasgcontratos.unidade_id', '=', session()->get('user_ug_id'));
//        $this->crud->addClause('orwhere', 'siasgcontratos.unidadesubrrogacao_id', '=', session()->get('user_ug_id'));


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

//        $this->crud->addButtonFromView('top', 'siasg', 'siasg', 'end');
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('contrato_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contrato_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contrato_deletar')) ? $this->crud->allowAccess('delete') : null;

        $this->crud->enableExportButtons();

        $colunas = $this->colunas();
        $this->crud->addColumns($colunas);

        $campos = $this->campos();
        $this->crud->addFields($campos);


        // add asterisk for fields that are required in SiasgcontratoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function colunas()
    {
        return [
            [
                'name' => 'getCompra',
                'label' => 'Compra', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getCompra', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('siasgcompras.numero', 'ilike', "%" . $searchTerm . "%");
                    $query->orWhere('siasgcompras.ano', 'ilike', "%" . $searchTerm . "%");
                    $query->orWhere('unidades.codigo', 'ilike', "%" . $searchTerm . "%");
                    $query->orWhere('unidades.nomeresumido', 'ilike', "%" . $searchTerm . "%");
                },
            ],
            [
                'name' => 'getUnidade',
                'label' => 'Unidade', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('unidades.codigo', 'ilike', "%" . $searchTerm . "%");
                    $query->orWhere('unidades.nomeresumido', 'ilike', "%" . $searchTerm . "%");
                },
            ],
            [
                'name' => 'getTipo',
                'label' => 'Tipo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipo', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('codigoitens.descres', 'ilike', "%" . $searchTerm . "%");
                    $query->orWhere('codigoitens.descricao', 'ilike', "%" . $searchTerm . "%");
                },
            ],
            [
                'name' => 'numero',
                'label' => 'Número',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('siasgcontratos.numero', 'ilike', "%" . $searchTerm . "%");
                },
            ],
            [
                'name' => 'ano',
                'label' => 'Ano',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('siasgcontratos.ano', 'ilike', "%" . $searchTerm . "%");
                },
            ],
            [
                'name' => 'codigo_interno',
                'label' => 'Cód. Interno (Ñ SISG)',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getUnidadeSubrrogada',
                'label' => 'Unid. Subrrogação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidadeSubrrogada', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('unidades.codigo', 'ilike', "%" . $searchTerm . "%");
                    $query->orWhere('unidades.nomeresumido', 'ilike', "%" . $searchTerm . "%");
                },
            ],
            [
                'name' => 'mensagem',
                'label' => 'Mensagem retorno',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('siasgcontratos.mensagem', 'ilike', "%" . $searchTerm . "%");
                },
            ],
            [
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('siasgcontratos.situacao', 'ilike', "%" . $searchTerm . "%");
                },
            ],
            [
                'name' => 'getContratoVinculado',
                'label' => 'Contrato vinculado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContratoVinculado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'sisg',
                'label' => 'Rotina SISG?',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'options' => [0 => 'Não', 1 => 'Sim']
            ],
            [
                'name' => 'created_at',
                'label' => 'Criação',
                'type' => 'datetime',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'updated_at',
                'label' => 'Atualização',
                'type' => 'datetime',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
        ];
    }

    public function campos()
    {
        $tipos = $this->buscaTipos();
        $contratos = $this->buscaContratos();

        return [
            [
                // 1-n relationship
                'label' => "Compra", // Table column heading
                'type' => "select2_from_ajax_compra",
                'name' => 'compra_id', // the column that contains the ID of that connected entity
                'entity' => 'compra', // the method that defines the relationship in your Model
                'attribute' => "unidadecompra", // foreign key attribute that is shown to user
                'attribute2' => "numerocompra", // foreign key attribute that is shown to user
                'process_results_template' => 'gescon.process_results_comprasiasg',
                'model' => "App\Models\Siasgcompra", // foreign key model
                'data_source' => url("api/comprasiasg"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Selecione a Compra", // placeholder for the select
                'minimum_input_length' => 2, // minimum characters to type before querying results
            ],
            [
                // 1-n relationship
                'label' => "Unidade do Contrato", // Table column heading
                'type' => "select2_from_ajax",
                'name' => 'unidade_id', // the column that contains the ID of that connected entity
                'entity' => 'unidade', // the method that defines the relationship in your Model
                'attribute' => "codigo", // foreign key attribute that is shown to user
                'attribute2' => "nomeresumido", // foreign key attribute that is shown to user
                'process_results_template' => 'gescon.process_results_unidade',
                'model' => "App\Models\Unidade", // foreign key model
                'data_source' => url("api/unidade"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Selecione a Unidade", // placeholder for the select
                'minimum_input_length' => 2, // minimum characters to type before querying results
            ],
            [
                // select_from_array
                'name' => 'tipo_id',
                'label' => "Tipo",
                'type' => 'select2_from_array',
                'options' => $tipos,
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                'name' => 'numero',
                'label' => 'Número',
                'type' => 'numerocompra',
            ],
            [
                'name' => 'ano',
                'label' => 'Ano Contrato',
                'type' => 'anoquatrodigitos',
            ],
            [ // select_from_array
                'name' => 'sisg',
                'label' => "Rotina SISG?",
                'type' => 'select_from_array',
                'options' => [1 => 'Sim', 0 => 'Não'],
                'allows_null' => false,
            ],
            [
                'name' => 'codigo_interno',
                'label' => 'Código Interno Não SISG',
                'type' => 'text',
                'attributes' => [
                    'maxlength' => "10",
                    'onfocusout' => "maiuscula(this)"
                ],
                'default' => '0000000000'
            ],
            [ // select_from_array
                'name' => 'contrato_id',
                'label' => "Contrato vinculado",
                'type' => 'select2_from_array',
                'options' => $contratos,
                'allows_null' => true,
            ],
            [   // Hidden
                'name' => 'situacao',
                'type' => 'hidden',
                'default' => 'Pendente',
            ],
        ];

    }

    private function buscaTipos()
    {
        $tipos = Codigoitem::select(DB::raw("CONCAT(descres,' - ',descricao) AS nome"), 'id')
            ->whereHas('codigo', function ($query) {
                $query->where('descricao', '=', 'Tipo de Contrato');
            })
            ->whereIn('descres', config('api-siasg.tipo_contrato'))
            ->orderBy('descres')
            ->pluck('nome', 'id')
            ->toArray();

        return $tipos;
    }

    private function buscaContratos()
    {
        $contratos = Contrato::select(DB::raw("CONCAT(contratos.numero,' | ',fornecedores.cpf_cnpj_idgener,' - ',fornecedores.nome) AS numero"), 'contratos.id')
            ->join('fornecedores','contratos.fornecedor_id','=','fornecedores.id')
            ->where('unidade_id', session()->get('user_ug_id'))
            ->where('situacao',true)
            ->orderBy('numero')
            ->pluck('numero', 'contratos.id')
            ->toArray();

        return $contratos;
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
        $request->request->set('situacao', 'Pendente');

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function verificarContratosPendentes()
    {
        $model = new Siasgcontrato;
        $apiSiasg = new ApiSiasg();

        $siasgcontratos = $model->buscaContratosPendentes();

        foreach ($siasgcontratos as $siasgcontrato) {
            $dado = [];
            if ($siasgcontrato->sisg == true) {
                $dado = [
                    'contrato' => $siasgcontrato->unidade->codigosiasg . $siasgcontrato->tipo->descres . $siasgcontrato->numero . $siasgcontrato->ano
                ];
                $retorno = $apiSiasg->executaConsulta('DadosContrato', $dado);
            } else {
                $dado = [
                    'contratoNSisg' => $siasgcontrato->unidade->codigosiasg . str_pad($siasgcontrato->codigo_interno, 10 , " ") . $siasgcontrato->tipo->descres . $siasgcontrato->numero . $siasgcontrato->ano
                ];
                $retorno = $apiSiasg->executaConsulta('ContratoNaoSisg', $dado);
            }

            $siasgcontrato_atualizado = $siasgcontrato->atualizaJsonMensagemSituacao($siasgcontrato->id, $retorno);

            if($siasgcontrato_atualizado->mensagem == 'Sucesso' and $siasgcontrato_atualizado->situacao == 'Importado'){
                $contratoSiagIntegracao = new ContratoSiasgIntegracaoNovo();
                $contrato = $contratoSiagIntegracao->executaAtualizacaoContratos($siasgcontrato_atualizado);

                if(isset($contrato->id)){
                    $siasgcontrato_atualizado->contrato_id = $contrato->id;
                    $siasgcontrato_atualizado->save();
                }
            }

        }

        \Alert::success('Contratos importados com sucesso!')->flash();

        return redirect('/gescon/siasg/contratos');
    }


    public function executaJobAtualizacaoSiasgContratos()
    {
        $model = new Siasgcontrato;
        $siasgcontratos = $model->buscaContratosPendentes();

        foreach ($siasgcontratos as $siasgcontrato){
            if(isset($siasgcontrato->id)){
                AtualizaSiasgContratoJob::dispatch($siasgcontrato)->onQueue('siasgcontrato');
            }
        }
    }

    public function importaManualmenteContratoSemCompra()
    {
        $file = fopen(env('CARGA_CONTRATOS_SISG'), "r");
        $tipoconsulta = 'CONTRATOSISG';

        while (!feof($file)) {
            $line = fgets($file);

            $contrato = (substr($line,0,23));
            $unidade = substr($contrato,0,6);
            $tipo_id = substr($contrato,6,2);
            $numero = substr($contrato,8,5);
            $ano = substr($contrato,13,4);
            $subrogacao_id = substr($contrato,17,6);

            $unidade_id = $this->buscaIdUnidadesPorNumeroUasg($unidade);

            $dados = [
                'unidade_id' => $unidade_id,
                'tipo_id'    =>(int)$tipo_id,
                'numero'     =>$numero,
                'ano'        =>$ano,
                'unidadesubrrogacao_id'=>($subrogacao_id == '000000')? null : $this->buscaIdUnidadesPorNumeroUasg($subrogacao_id),
                "codigo_interno" => '',
                'situacao'   => 'Pendente',
                'sisg'       => 1
            ];

            ($unidade_id != 0) ? $this->insereContratoSiasg($dados) : '';


        }
        fclose($file);
        die('Importação dos contratos sem compra realizada com sucesso!!');
    }


    public function importaManualmenteContratoNaoSisg()
    {
        $file = fopen(env('CARGA_CONTRATOS_NSISG'), "r");
        $tipoconsulta = 'CONTRATONAOSISG';

        while (!feof($file)) {
            $line = fgets($file);

            $contrato = (substr($line,0,27));
            $unidade = substr($contrato,0,6);
            $codigoInterno = substr($contrato,6,10);
            $tipo_id = substr($contrato,16,2);
            $numero = substr($contrato,18,5);
            $ano = substr($contrato,23,4);
            $unidade_id = $this->buscaIdUnidadesPorNumeroUasg($unidade);
            $dados = [
                'unidade_id' => $unidade_id,
                'tipo_id'    =>(int)$tipo_id,
                'numero'     =>$numero,
                'ano'        =>$ano,
                'codigo_interno'=>$codigoInterno,
                'unidadesubrrogacao_id' => null,
                'situacao'   => 'Pendente',
                'sisg'       => 0
            ];

            ($unidade_id != 0) ? $this->insereContratoSiasg($dados) : '';

        }
        fclose($file);
        die('Importação dos contratos NÃO SISG realizada com sucesso!!');
    }

    private function buscaIdUnidadesPorNumeroUasg(string $numero)
    {
        if(strlen($numero) < 6){
            $numero = '0'.$numero;
        }
        $unidades = Unidade::where('codigo',$numero)->first();

        (!is_null($unidades)) ? $id = $unidades->id : $id = null;

        return $id;
    }

    public function insereContratoSiasg(array $dados)
    {

        $siasgContrato = new Siasgcontrato();
        $siasgContrato->unidade_id = $dados['unidade_id'];
        $siasgContrato->tipo_id = $dados['tipo_id'];
        $siasgContrato->numero = $dados['numero'];
        $siasgContrato->ano = $dados['ano'];
        $siasgContrato->codigo_interno = $dados['codigo_interno'];
        $siasgContrato->situacao = $dados['situacao'];
        $siasgContrato->unidadesubrrogacao_id = $dados['unidadesubrrogacao_id'];
        $siasgContrato->sisg = $dados['sisg'];
        $siasgContrato->save();

    }

}
