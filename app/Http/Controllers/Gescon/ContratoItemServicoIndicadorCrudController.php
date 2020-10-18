<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use App\Models\Indicador;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Traits\Formatador;

use App\Http\Requests\ContratoItemServicoIndicadorRequest as StoreRequest;
use App\Http\Requests\ContratoItemServicoIndicadorRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;
use Route;

/**
 * Class ContratoItemServicoIndicadorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratoItemServicoIndicadorCrudController extends CrudController
{
    use Formatador;

    public function setup()
    {
        $contratoitem_servico_id = Route::current()->parameter('contratoitem_servico_id');
        $contrato_id = Route::current()->parameter('contratoitem_servico_id');
        $indicadores = Indicador::all()->pluck('nome', 'id')->toArray();

        $periodicidade = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Periodicidade da Glosa');
        })
            ->pluck('descricao', 'id');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ContratoItemServicoIndicador');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/meus-servicos/' . $contrato_id . '/' . $contratoitem_servico_id . '/indicadores');
        $this->crud->setEntityNameStrings('indicador', 'indicadores');
        $this->crud->removeButton('create');
        $this->crud->addButtonFromView('top', 'vincular', 'vincularIndicador');

        $this->crud->addButtonFromView('top', 'voltar', 'voltarservico', 'end');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('contrato_servico_indicador_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contrato_servico_indicador_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contrato_servico_indicador_deletar')) ? $this->crud->allowAccess('delete') : null;

        $this->crud->addButtonFromView('line', 'moreglosas', 'moreglosas', 'end');

        $this->crud->addClause('join', 'contratoitem_servico', 'contratoitem_servico.id', '=', 'contratoitem_servico_indicador.contratoitem_servico_id');
        $this->crud->addClause('join', 'servicos', 'servicos.id', '=', 'contratoitem_servico.servico_id');
        $this->crud->addClause('join', 'contratoitens', 'contratoitens.id', '=', 'contratoitem_servico.contratoitem_id');
        // Apenas ocorrencias deste contratoitem_servico_id
        $this->crud->addClause('where', 'contratoitem_servico_indicador.contratoitem_servico_id', '=', $contratoitem_servico_id);

        $this->crud->addClause('select', [
            DB::raw('contratoitens.contrato_id'),
            DB::raw('servicos.nome as servico_nome'),
            // Tabela principal deve ser sempre a última da listagem!
            'contratoitem_servico_indicador.*'
        ]);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->colunas($periodicidade);
        $this->campos($contratoitem_servico_id, $indicadores, $periodicidade);

        // add asterisk for fields that are required in ContratoItemServicoIndicadorRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $request->request->set('vlrmeta', $this->retornaFormatoAmericano($request->vlrmeta));

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

    /**
     * Configura os campos dos formulários de Inserir e Atualizar
     *
     * @param string $contratoitem_servico_id
     * @param array $indicadores
     * @param $periodicidade
     */
    private function campos(string $contratoitem_servico_id
        , array $indicadores, $periodicidade): void
    {
        $this->adicionaCampoContratoItemServico($contratoitem_servico_id);
        $this->adicionaCampoIndicador($indicadores);
        $this->adicionaCampoTipoAfericao();
        $this->adicionaCampoMeta();
        $this->adicionaCampoPeriodicidade($periodicidade);
    }

    /**
     * Configura a grid de visualização
     *
     * @param $periodicidade
     */
    private function colunas($periodicidade): void
    {
        $this->adicionaColunaServico();
        $this->adicionaColunaIndicador();
        $this->adicionaColunaTipoAfericao();
        $this->adicionaColunaMeta();
        $this->adicionaColunaPeriodicidade($periodicidade);
    }

    /**
     * Configura a coluna Servico
     */
    private function adicionaColunaServico(): void
    {
        $this->crud->addColumn([
            'name' => 'servico_nome',
            'label' => 'Servico',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    /**
     * Configura a coluna Indicador
     */
    private function adicionaColunaIndicador(): void
    {
        $this->crud->addColumn([
            'name' => 'getIndicador',
            'label' => 'Indicador',
            'type' => 'model_function',
            'function_name' => 'getIndicador',
            'orderable' => true,
            'limit' => 1000,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);

    }

    /**
     * Configura a coluna Tipo Afericao
     */
    private function adicionaColunaTipoAfericao(): void
    {
        $this->crud->addColumn([
            'name' => 'tipo_afericao',
            'label' => 'Tipo de Afericao',
            'type' => 'boolean',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'options' => [0 => 'Percentual', 1 => 'Número de Ocorrências'],
        ]);
    }

    /**
     * Configura a coluna Meta
     */
    private function adicionaColunaMeta(): void
    {
        $this->crud->addColumn([
            'name' => 'vlrmeta_formatado',
            'label' => 'Meta',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    /**
     * Configura a coluna Peridiocidade
     *
     * @param $periodicidade
     */
    private function adicionaColunaPeriodicidade($periodicidade): void
    {
        $this->crud->addColumn([
            'name' => 'periodicidade_id',
            'label' => "Periodicidade",
            'type' => 'select_from_array',
            'options' => $periodicidade,
            'allows_null' => false,
        ]);
    }

    /**
     * Configura o campo escondido 'contratoitem_servico_id'
     *
     * @param $contratoitem_servico_id
     */
    private function adicionaCampoContratoItemServico($contratoitem_servico_id): void
    {
        $this->crud->addField([   // Hidden
            'name' => 'contratoitem_servico_id',
            'type' => 'hidden',
            'default' => $contratoitem_servico_id,
        ]);
    }

    /**
     * Configura o campo Indicador
     *
     * @param $indicadores
     */
    private function adicionaCampoIndicador($indicadores): void
    {
        $this->crud->addField([ // select_from_array
            'name' => 'indicador_id',
            'label' => 'Indicador',
            'type' => 'select2_from_array',
            'options' => $indicadores,
            'allows_null' => false,
            'placeholder' => 'Selecione',
        ]);
    }

    /**
     * Configura o campo Afericao
     */
    private function adicionaCampoTipoAfericao(): void
    {
        $this->crud->addField([
            'name' => 'tipo_afericao',
            'label' => 'Aferição',
            'type' => 'radio',
            'options' => [0 => 'Percentual', 1 => 'Número de Ocorrências'],
            'default' => 0,
            'inline' => true,
            'wrapperAttributes' => [
                'title' => "Utilizar Número de Ocorrências quando a quantidade de eventos for baixa (Exemplo: < 100 ocorrências)"
            ],
        ]);
    }

    /**
     * Configura o campo Meta
     */
    private function adicionaCampoMeta(): void
    {
        $this->crud->addField([   // Number
            'name' => 'vlrmeta',
            'label' => 'Meta',
            'type' => 'money',
            'attributes' => [
                'id' => 'vlrmeta',
                "step" => "any"
            ],
        ]);
    }

    /**
     * Configura o campo Periodicidade
     *
     * @param $periodicidade
     */
    private function adicionaCampoPeriodicidade($periodicidade): void
    {
        $this->crud->addField([
            'name' => 'periodicidade_id',
            'label' => 'Periodicidade',
            'type' => 'select2_from_array',
            'options' => $periodicidade,
            'allows_null' => false,
            'placeholder' => 'Selecione',
        ]);
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'contratoitem_servico_id',
            'indicador_id',
        ]);

        return $content;
    }

}
