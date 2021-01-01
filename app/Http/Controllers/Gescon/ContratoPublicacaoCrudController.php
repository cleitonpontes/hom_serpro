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
use App\Models\Contratohistorico;

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
//        $this->crud->setModel(ContratoPublicacoes::class);
        $this->crud->setModel('App\Models\ContratoPublicacoes');
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
        $this->crud->addClause('select', 'contratopublicacoes.*');


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
        $this->adicionaCampos($contrato_id);

        // add asterisk for fields that are required in ContratoPublicacaoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    /**
     * Configura os campos dos formulários de Inserir e Atualizar
     *
     */
    protected function adicionaCampos($contrato_id)
    {
        $this->adicionaCampoDataPublicacao();
        $this->adicionaCampoContratoHistorico($contrato_id);
//        $this->adicionaCampoStatus();
//        $this->adicionaCampoStatusPublicacao();
        $this->adicionaCampoTextoDOU();
        $this->adicionaCampoCpf();
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
     * Configura o campo CPF
     *
     */
    private function adicionaCampoCPF(): void
    {
        $this->crud->addField([
            'name' => 'cpf',
            'label' => 'CPF',
            'type' => 'cpf_sem_mascara',
        ]);
    }

    /**
     * Configura o campo Data de Publicação
     *
     */
    private function adicionaCampoContratoHistorico($contrato_id): void
    {
        $this->crud->addField([  // Select2
            'label' => "Instrumento",
            'type' => 'select2',
            'name' => 'contratohistorico_id', // the db column for the foreign key
            'entity' => 'contratohistorico', // the method that defines the relationship in your Model
            'attribute' => 'combo_publicacao', // foreign key attribute that is shown to user
            'model' => Contratohistorico::class, // foreign key model

            // optional
            'options'   => (function ($query) use ($contrato_id) {
                return $query->where('contrato_id', $contrato_id)->get();
            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
        ]);
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
//        dd($this->retornaCodigosItens('teste'));
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
            'attributes' => [
                'id' => 'tipo_pagamento_id',
            ],
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
            'type' => 'empenho_texto_livre',
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
        $this->adicionaColunaCpf();
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

    /**
     * Cofigura a coluna
     */
    private function adicionaColunaCpf(): void
    {
        $this->crud->addColumn([
            'name' => 'cpf',
            'label' => 'CPF',
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
        $situacao_id = $this->retornaIdCodigoItem('Situacao Publicacao','A PUBLICAR');

        $request->request->set('status_publicacao_id', $situacao_id);

        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $situacao_id = $this->retornaIdCodigoItem('Situacao Publicacao','A PUBLICAR');

        $request->request->set('status_publicacao_id', $situacao_id);
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
