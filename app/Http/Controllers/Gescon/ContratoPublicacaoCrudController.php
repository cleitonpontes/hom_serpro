<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\SfOrcEmpenhoDados;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratoPublicacaoRequest as StoreRequest;
use App\Http\Requests\ContratoPublicacaoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;
use Route;
use App\Models\ContratoPublicacoes;
use App\Http\Traits\BuscaCodigoItens;

/**
 * Class ContratoPublicacaoCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 */
class ContratoPublicacaoCrudController extends CrudController
{
    use BuscaCodigoItens;

    public function setup()
    {
        $contrato_id = Route::current()->parameter('contrato_id');

        $contrato = Contrato::where('id', '=', $contrato_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'));

        if (!$contrato->first()) {
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(ContratoPublicacoes::class);
//        $this->crud->setRoute(config('backpack.base.route_prefix') . '/contratopublicacao');
        $this->crud->setRoute(config('backpack.base.route_prefix')
            . "/gescon/contrato/$contrato_id/publicacao");
        $this->crud->setEntityNameStrings('Publicação', 'Publicações');
        $this->crud->addClause(
            'join',
            'contratohistorico',
            'contratohistorico.id',
            '=',
            'contratopublicacoes.contratohistorico_id'
        );
        $this->crud->addClause(
            'join',
            'contratos',
            'contratos.id',
            '=',
            'contratohistorico.contrato_id'
        );
        $this->crud->addClause('where', 'contratos.id', '=', $contrato_id);


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');
        $this->crud->allowAccess('update');
        $this->crud->allowAccess('create');

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();

        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->addButtonFromView('line', 'atualizarsituacaopublicacao', 'atualizarsituacaopublicacao');
        $this->crud->enableExportButtons();

        $this->adicionaColunas();
        $this->adicionaCampos();

        // add asterisk for fields that are required in ContratoPublicacaoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    /**
     * Configura os campos dos formulários de Inserir e Atualizar
     *
     */
    protected function adicionaCampos()
    {
        $this->adicionaCampoDataPublicacao();
        $this->adicionaCampoTipoPublicacao();
//        $this->adicionaCampoStatus();
//        $this->adicionaCampoStatusPublicacao();
        $this->adicionaCampoTextoDOU();
        $this->adicionaCampoTipoPagamento();
        $this->adicionaCampoMotivoIsencao();
        $this->adicionaCampoEmpenho();
    }

    /**
     * Configura o campo Data de Publicação
     *
     */
    private function adicionaCampoDataPublicacao(): void
    {
        $this->crud->addField([
            'name' => 'data_publicacao',
            'label' => 'Data Publicacao',
            'type' => 'date',
            /*'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]*/
        ]);
    }

    /**
     * Configura o campo Tipo de Publicacao
     *
     */
    private function adicionaCampoTipoPublicacao(): void
    {
        $this->crud->addField([
            'name' => 'tipo_publicacao',
            'label' => 'Tipo Publicacao',
            'type' => 'text',
            'attributes' => [
                'readonly' => 'readonly',
                'style' => 'pointer-events: none;touch-action: none;'
            ],
            /*'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]*/
        ]);
    }

    /**
     * Configura o campo Status
     *
     */
    private function adicionaCampoStatus(): void
    {
        $this->crud->addField([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => [
                'Pendente' => 'Pendente',
                'L' => 'Lido',
                'E' => 'Erro',
            ],
        ]);
    }

    /**
     * Configura o campo Status Publicacao
     */
    private function adicionaCampoStatusPublicacao(): void
    {
        $this->crud->addField([
            'name' => '',
            'label' => '',
            'type' => '',
        ]);
        dd($this->retornaCodigosItens('teste'));
        $this->crud->addField([ // select_from_array
            'name' => 'situacao',
            'label' => "Situação",
            'type' => 'select_from_array',
            'options' => [
                'P' => 'Pendente',
                'L' => 'Lido',
                'E' => 'Erro',
            ],
            'default' => 'P',
            'allows_null' => false,
            'attributes' => [
                'readonly' => 'readonly',
                'style' => 'pointer-events: none;touch-action: none;'
            ],
//                'default' => 'one',
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);
    }

    /**
     * Configura o campo Texto DOU
     *
     */
    private function adicionaCampoTextoDOU(): void
    {
        $this->crud->addField([
            'name' => 'texto_dou',
            'label' => 'Texto DOU',
            'type' => 'textarea',
        ]);
    }

    /**
     * Configura o campo Tipo de Pagamento
     */
    private function adicionaCampoTipoPagamento(): void
    {
        $this->crud->addField([
            'name' => 'tipo_pagamento_id',
            'label' => "Tipo Pagamento",
            'type' => 'select2_from_array',
            'options' => $this->retornaArrayCodigosItens('Forma Pagamento'),
            'allows_null' => false,
        ]);
    }

    /**
     * Configura o campo Motivo Isenção
     */
    private function adicionaCampoMotivoIsencao(): void
    {
        $this->crud->addField([
            'name' => 'motivo_isencao_id',
            'label' => "Motivo Isenção",
            'type' => 'select2_from_array',
            'options' => $this->retornaArrayCodigosItens('Motivo Isenção'),
            'allows_null' => false,
        ]);
    }

    /**
     * Configura o campo Empenho
     */
    private function adicionaCampoEmpenho(): void
    {
        $this->crud->addField([ // select_from_array
            'name' => 'numero',
            'label' => "Número Empenho",
            'type' => 'empenho',
//                'allows_null' => false,
//                'default' => 'one',
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);
    }



    /**
     * Configura a grid de visualização
     *
     */
    protected function adicionaColunas()
    {
        $this->adicionaColunaDataPublicacao();
//        $this->adicionaColunaStatus();
        $this->adicionaColunaStatusPublicacao();
        $this->adicionaColunaTipoPublicacao();
    }

    /**
     * Cofigura a coluna
     */
    private function adicionaColunaDataPublicacao(): void
    {
        $this->crud->addColumn([
            'name' => 'data_publicacao',
            'label' => 'Data Publicação',
            'type' => 'date',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    /**
     * Cofigura a coluna
     */
    private function adicionaColunaStatus(): void
    {
        $this->crud->addColumn([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    /**
     * Cofigura a coluna
     */
    private function adicionaColunaStatusPublicacao(): void
    {
        $this->crud->addColumn([
            'name' => 'status_publicacao',
            'label' => 'Situação Publicação',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    /**
     * Cofigura a coluna
     */
    private function adicionaColunaTipoPublicacao(): void
    {
        $this->crud->addColumn([
            'name' => 'tipo_publicacao',
            'label' => 'Tipo Publicacao',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
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
        dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function executarAtualizacaoSituacaoPublicacao($id)
    {
        DB::beginTransaction();
        try {
            $situacao = Codigoitem::wherehas('codigo', function ($q) {
                $q->where('descricao', '=', 'Situações Minuta Empenho');
            })
                ->where('descricao', 'EM PROCESSAMENTO')
                ->first();
            $minuta->situacao_id = $situacao->id;
            $minuta->save();

            $modSfOrcEmpenhoDados = SfOrcEmpenhoDados::where('minutaempenho_id', $id)->first();

            $modSfOrcEmpenhoDados->situacao = 'EM PROCESSAMENTO';
            $modSfOrcEmpenhoDados->save();

            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
        }

        Alert::success('Situação da minuta alterada com sucesso!')->flash();
        return redirect('/empenho/minuta');
    }
}
