<?php

namespace App\Http\Controllers\Admin;

use App\Models\Codigoitem;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PadroespublicacaoRequest as StoreRequest;
use App\Http\Requests\PadroespublicacaoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use function foo\func;

/**
 * Class PadroespublicacaoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PadroespublicacaoCrudController extends CrudController
{
    public function setup()
    {

        if (!backpack_user()->hasRole('Administrador')) {
            abort('403', config('app.erro_permissao'));
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Padroespublicacao');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/padroespublicacao');
        $this->crud->setEntityNameStrings('Padrão publicação', 'Padrões Publicações');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');


        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $campos = $this->Campos();
        $this->crud->addFields($campos);


        // add asterisk for fields that are required in PadroespublicacaoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function colunas()
    {
        $colunas = [
            [
                'name' => 'getTipoContrato',
                'label' => 'Tipo Instrumento', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $q, $column, $searchTerm) {
//                    $q->where('orgaos.codigo', 'like', "%".strtoupper($searchTerm)."%");
//                    $q->orWhere('orgaos.nome', 'like', "%".strtoupper($searchTerm)."%");
//                },
            ],
            [
                'name' => 'getTipoMudanca',
                'label' => 'Tipo Mudança', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoMudanca', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('codigoitens.descricao', 'like', "%" . $searchTerm . "%");
//                },
            ],
            [
                'name' => 'getIdentificadorNorma',
                'label' => 'Identificador Norma', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getIdentificadorNorma', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('codigoitens.descricao', 'like', "%" . $searchTerm . "%");
//                },
            ],
            [
                'name' => 'texto_padrao',
                'label' => 'Texto Padrão',
                'type' => 'text',
                'orderable' => true,
//                'limit' => 150,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ]
        ];

        return $colunas;
    }

    public function Campos()
    {
        $tipo_contrato = $this->buscaCodigoItemPorCodigo('Tipo de Contrato', ['Empenho','Outros']);
        $tipo_mudanca = $this->buscaCodigoItemPorCodigo('Tipo Publicacao');
        $identificados_norma = $this->buscaCodigoItemPorCodigo('Tipo Norma Publicação');

        $campos = [
            [ // select_from_array
                'name' => 'tipo_contrato_id',
                'label' => "Tipo Instrumento",
                'type' => 'select_from_array',
                'options' => $tipo_contrato,
                'allows_null' => true,
            ],
            [ // select_from_array
                'name' => 'tipo_mudanca_id',
                'label' => "Tipo Mudança",
                'type' => 'select2_from_array',
                'options' => $tipo_mudanca,
                'allows_null' => true,
            ],
            [ // select_from_array
                'name' => 'identificador_norma_id',
                'label' => "Identificador norma",
                'type' => 'select2_from_array',
                'options' => $identificados_norma,
                'allows_null' => true,
            ],
            [
                'name' => 'texto_padrao',
                'label' => 'Texto padrão',
                'type' => 'textarea',
                'attributes' => [
                   // 'onblur' => "maiuscula(this)"
                ]
            ]

        ];

        return $campos;
    }

    public function buscaCodigoItemPorCodigo(string $descricao_codigo, array $excessao=null)
    {
        $retorno = Codigoitem::select()
        ->whereHas('codigo', function ($c) use($descricao_codigo){
            $c->where('descricao',$descricao_codigo);
        })
            ->orderBy('descricao');

        $pkCount = (is_array($excessao) ? count($excessao) : 0);
        if($pkCount>0){
            $retorno->whereNotIn('descricao',$excessao);
        }

        return $retorno->pluck('descricao', 'id')->toArray();
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

        $this->crud->removeColumn('tipo_contrato_id');
        $this->crud->removeColumn('tipo_mudanca_id');
        $this->crud->removeColumn('identificador_norma_id');

        return $content;
    }
}
