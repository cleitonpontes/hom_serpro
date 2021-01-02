<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Fornecedor;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RescisaoRequest as StoreRequest;
use App\Http\Requests\RescisaoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;

/**
 * Class RescisaoCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 */
class RescisaoCrudController extends CrudController
{
    public function setup()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');

        $contrato = Contrato::where('id', '=', $contrato_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'))->first();
        if (!$contrato) {
            abort('403', config('app.erro_permissao'));
        }

        $tps = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->Where('descricao', '=', 'Termo de Rescisão')
            ->pluck('id')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratohistorico');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/' . $contrato_id . '/rescisao');
        $this->crud->setEntityNameStrings('Termo de Rescisão', 'Termos de Rescisão');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratohistorico.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratohistorico.unidade_id');
        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));
        $this->crud->addClause('select', 'contratohistorico.*');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        foreach ($tps as $t) {
            $this->crud->addClause('where', 'tipo_id', '=', $t);
        }
        $this->crud->orderBy('data_assinatura', 'asc');

        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->addButtonFromView('line', 'morecontratohistorico', 'morecontratohistorico', 'end');
        $this->crud->enableExportButtons();
//        $this->crud->disableResponsiveTable();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('contratorescisao_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contratorescisao_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contratorescisao_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $fornecedores = Fornecedor::select(DB::raw("CONCAT(cpf_cnpj_idgener,' - ',nome) AS nome"), 'id')
            ->orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();

        $unidade = [session()->get('user_ug_id') => session()->get('user_ug')];

        $tipo = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '=', 'Termo de Rescisão')
            ->first();


        $campos = $this->Campos($fornecedores, $tipo, $contrato_id, $unidade);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in ApostilamentoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'numero',
                'label' => 'Núm. Rescisão',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'observacao',
                'label' => 'Observação',
                'type' => 'text',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'processo',
                'label' => 'Processo',
                'type' => 'text',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'data_assinatura',
                'label' => 'Data da Assinatura',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'data_publicacao',
                'label' => 'Data de Publicação',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'vigencia_fim',
                'label' => 'Vigência Fim',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
        ];

        return $colunas;

    }

    public function Campos($fornecedores, $tipo, $contrato_id, $unidade)
    {
        $contrato = Contrato::find($contrato_id);

        $campos = [

            [   // Hidden
                'name' => 'receita_despesa',
                'type' => 'hidden',
                'default' => $contrato->receita_despesa,
            ],
            [   // Hidden
                'name' => 'tipo_id',
                'type' => 'hidden',
                'default' => $contrato->tipo_id,
            ],
            [   // Hidden
                'name' => 'contrato_id',
                'type' => 'hidden',
                'default' => $contrato->id,
            ],
            [   // Hidden
                'name' => 'fornecedor_id',
                'type' => 'hidden',
                'default' => $contrato->fornecedor_id,
            ],
            [   // Hidden
                'name' => 'unidade_id',
                'type' => 'hidden',
                'default' => $contrato->unidade_id,
            ],
            [   // Hidden
                'name' => 'categoria_id',
                'type' => 'hidden',
                'default' => $contrato->categoria_id,
            ],
            [   // Date
                'name' => 'vigencia_inicio',
                'type' => 'hidden',
                'default' => $contrato->vigencia_inicio,
            ],
            [   // Hidden
                'name' => 'tipo_id',
                'type' => 'hidden',
                'default' => $tipo->id,
            ],
            [   // Hidden
                'name' => 'numero',
                'type' => 'hidden',
                'default' => $contrato->numero,

            ],
            [
                'name' => 'observacao',
                'label' => 'Observação',
                'type' => 'textarea',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ],
            ],
            $campos[] = [
                'name' => 'processo',
                'label' => 'Número Processo',
                'type' => 'numprocesso',
            ],
            [   // Date
                'name' => 'data_assinatura',
                'label' => 'Data Assinatura Rescisão',
                'type' => 'date',
            ],
            [
            'name' => 'data_publicacao',
            'label' => 'Data Publicação',
            'type' => 'date',
            ],
            [
                // Date
                'name' => 'vigencia_fim',
                'label' => 'Data Vig. Fim',
                'type' => 'date',
            ]

        ];

        return $campos;
    }

    public function store(StoreRequest $request)
    {
        $request->request->set('situacao',0);
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $request->request->set('situacao',0);
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'retroativo_mesref_de',
            'retroativo_anoref_de',
            'retroativo_mesref_ate',
            'retroativo_anoref_ate',
            'retroativo_valor',
            'fornecedor_id',
            'tipo_id',
            'categoria_id',
            'unidade_id',
            'fundamento_legal',
            'modalidade_id',
            'licitacao_numero',
            'data_assinatura',
            'data_publicacao',
            'valor_inicial',
            'valor_global',
            'valor_parcela',
            'valor_acumulado',
            'situacao_siasg',
            'contrato_id',
            'receita_despesa',
            'processo',
            'objeto',
            'novo_valor_global',
            'novo_valor_parcela',
            'novo_num_parcelas',
            'data_inicio_novo_valor',
        ]);


        return $content;
    }

}
