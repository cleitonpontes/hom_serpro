<?php

namespace App\Http\Controllers\Admin;

use Alert;
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
class ContratoPublicacaoAdminCrudController extends CrudController
{
    use BuscaCodigoItens;

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
//        $this->crud->setModel(ContratoPublicacoes::class);
        $this->crud->setModel('App\Models\ContratoPublicacoes');
        $this->crud->setRoute(config('backpack.base.route_prefix')
            . "/admin/publicacoes");
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
        $this->crud->orderBy('updated_at', 'desc');
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
        $this->crud->denyAccess('create');

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();

        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        //TODO SUPRIMIR O BTN DE ATUALIZAR SITUAÇÃO ATÉ SEGUNDA ORDEM
//        $this->crud->addButtonFromView('line', 'atualizarsituacaopublicacao', 'atualizarsituacaopublicacao');
        $this->crud->enableExportButtons();

        $this->adicionaColunas();
        $this->adicionaCampos();
        $this->aplicaFiltros();

        // add asterisk for fields that are required in ContratoPublicacaoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }



    protected function aplicaFiltros()
    {
        $this->aplicaFiltroTipo();
    }

    protected function aplicaFiltroTipo()
    {
        $this->crud->addFilter(
            [
                'name' => 'status',
                'type' => 'text',
                'label' => 'Status'
            ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'contratopublicacoes.status', 'LIKE', "%$value%");
            }
        );
    }

    /**
     * Configura os campos dos formulários de Inserir e Atualizar
     *
     */
    protected function adicionaCampos()
    {
        $this->adicionaCampoDataPublicacao();
        $this->adicionaCampoContratoHistorico();
        $this->adicionaCampoStatus();
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
    private function adicionaCampoContratoHistorico(): void
    {
        $this->crud->addField(
            [
                // 1-n relationship
                'label' => "Instrumento", // Table column heading
                'type' => "select2_from_ajax",
                'name' => 'contratohistorico_id', // the column that contains the ID of that connected entity
                'entity' => 'contratohistorico', // the method that defines the relationship in your Model
                'attribute' => "numero", // foreign key attribute that is shown to user
//                'attribute2' => "nomeresumido", // foreign key attribute that is shown to user
                'process_results_template' => 'gescon.process_results_unidade',
                'model' => "App\Models\Contratohistorico", // foreign key model
                'data_source' => url("api/contratohistorico"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Selecione o Instrumento", // placeholder for the select
                'minimum_input_length' => 2, // minimum characters to type before querying results
            ]
        );
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
        $this->adicionaColunaCodUnidade();
        $this->adicionaColunaCodSiorg();
        $this->adicionaColunaDataPublicacao();
        $this->adicionaColunaStatus();
        $this->adicionaColunaStatusPublicacao();
        $this->adicionaColunaTipoPublicacao();
        $this->adicionaColunaCpf();
        $this->adicionaColunaLog();
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
    private function adicionaColunaCodUnidade(): void
    {
        $this->crud->addColumn([
            'name' => 'getCodUnidade',
            'label' => 'Unidade', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getCodUnidade', // the method in your Model
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
        ]);
    }
    /**
     * Cofigura a coluna
     */
    private function adicionaColunaCodSiorg(): void
    {
        $this->crud->addColumn([
            'name' => 'getCodSiorg',
            'label' => 'Cod. Siorg', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getCodSiorg', // the method in your Model
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


    /**
     * Cofigura a coluna
     */
    private function adicionaColunaLog(): void
    {
        $this->crud->addColumn([
            'name' => 'log',
            'label' => 'Log',
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
        $situacao_id = $this->retornaIdCodigoItem('Situacao Publicacao', 'A PUBLICAR');

        $request->request->set('status_publicacao_id', $situacao_id);

        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // verifica sé está em uma situação que permite a alteração
        $contrato_id = Contratohistorico::find($request->input('contratohistorico_id'))->contrato_id;
        $publicacao = ContratoPublicacoes::find($request->id);
        if (!in_array($publicacao->status_publicacao_id, $this->sitacoesPermitidasAlteracao())) {
            Alert::warning('Não é possível alterar uma publicação com essa situação.')->flash();
            return redirect()->route('crud.publicacao.index', ['contrato_id' => $contrato_id]);
        }

        $situacao_id = $this->retornaIdCodigoItem('Situacao Publicacao', 'A PUBLICAR');

        $request->request->set('status_publicacao_id', $situacao_id);
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    private function sitacoesPermitidasAlteracao()
    {
        $arrSituacoesPermitidas[] = $this->retornaIdCodigoItem('Situacao Publicacao', 'INFORMADO');
        $arrSituacoesPermitidas[] = $this->retornaIdCodigoItem('Situacao Publicacao', 'A PUBLICAR');
        $arrSituacoesPermitidas[] = $this->retornaIdCodigoItem('Situacao Publicacao', 'DEVOLVIDO PELA IMPRENSA');

        return $arrSituacoesPermitidas;
    }

    public function executarAtualizacaoSituacaoPublicacao($contrato_id, $id)
    {
        $publicacao = ContratoPublicacoes::find($id);

        $situacao_devolvido = $this->retornaIdCodigoItem('Situacao Publicacao', 'DEVOLVIDO PELA IMPRENSA');

        if ($publicacao->status_publicacao_id == $situacao_devolvido) {
            DB::beginTransaction();
            try {
                $situacao = $this->retornaIdCodigoItem('Situacao Publicacao', 'A PUBLICAR');
                $publicacao->status_publicacao_id = $situacao->id;
                $publicacao->save();

                DB::commit();
                Alert::success('Situação da publicacao alterada com sucesso!')->flash();
                return redirect($this->crud->route);
            } catch (Exception $exc) {
                DB::rollback();
                Alert::error('Erro! Tente novamente mais tarde!')->flash();
                return redirect($this->crud->route);
            }
        }

        Alert::warning('Operação não permitida!')->flash();
        return redirect($this->crud->route);
    }
}
