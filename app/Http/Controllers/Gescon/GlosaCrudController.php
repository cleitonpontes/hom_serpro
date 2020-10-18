<?php

namespace App\Http\Controllers\Gescon;

use App\Http\Traits\Formatador;
use App\Models\Codigoitem;
use App\Models\ContratoItemServicoIndicador;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GlosaRequest as StoreRequest;
use App\Http\Requests\GlosaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;
use Route;

/**
 * Class GlosaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GlosaCrudController extends CrudController
{
    use Formatador;

    public function setup()
    {
        $contratoitem_servico_indicador_id = Route::current()->parameter('cisi_id');
        $contratoitem_servico_indicador = ContratoItemServicoIndicador::find($contratoitem_servico_indicador_id);

        $escopo_glosas = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Escopo da Glosa');
        })
            ->pluck('descricao', 'id')
            ->toArray();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Glosa');
        $this->crud->setRoute(
            config('backpack.base.route_prefix')
            . '/gescon/meus-servicos/'
            . Route::current()->parameter('contrato_id') . '/'
            . Route::current()->parameter('contratoitem_servico_id') . '/'
            . $contratoitem_servico_indicador_id . '/glosas'
        );
        $this->crud->setEntityNameStrings('glosa', 'glosas');

        $this->crud->addButtonFromView('top', 'voltar', 'voltarmeusindicadores', 'end');

        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('glosa_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('glosa_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('glosa_deletar')) ? $this->crud->allowAccess('delete') : null;

        $this->crud->addClause('join', 'contratoitem_servico_indicador', 'contratoitem_servico_indicador.id', '=', 'glosas.contratoitem_servico_indicador_id');
        $this->crud->addClause('join', 'indicadores', 'indicadores.id', '=', 'contratoitem_servico_indicador.indicador_id');

        // Apenas ocorrencias deste contratoitem_servico_indicador_id
        $this->crud->addClause('where', 'glosas.contratoitem_servico_indicador_id', '=', $contratoitem_servico_indicador_id);

        $this->crud->addClause('select', [
            DB::raw('indicadores.nome as indicador_nome'),
            'contratoitem_servico_indicador.vlrmeta',
            // Tabela principal deve ser sempre a última da listagem!
            'glosas.*'
        ]);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->colunas($escopo_glosas);
        $this->campos(
            $contratoitem_servico_indicador
            , $escopo_glosas
        );

        // add asterisk for fields that are required in GlosaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $this->setRequestFaixa($request);
        $request->request->set('valor_glosa', $this->retornaFormatoAmericano($request->valor_glosa));

        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $this->setRequestFaixa($request);
        $request->request->set('valor_glosa', $this->retornaFormatoAmericano($request->valor_glosa));

        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('contratoitem_servico_indicador_id');

        return $content;
    }

    /**
     * Configura o tipo de faixa (slider ou "from to" ) para salvar corretamente
     *
     * @param $request
     */
    private function setRequestFaixa($request): void
    {
        if (isset($request->slider)) {
            $faixa = explode(';', $request->slider);
            $request->request->set('from', $faixa[0]);
            $request->request->set('to', $faixa[1]);
            return;
        }

        $request->request->set('from', $this->retornaFormatoAmericano($request->from));
        $request->request->set('to', $this->retornaFormatoAmericano($request->to));

    }

    /**
     * Configura os campos dos formulários de Inserir e Atualizar
     *
     * @param ContratoItemServicoIndicador $contratoItemServicoIndicador
     * @param array $escopo_glosas
     */
    private function campos(
        ContratoItemServicoIndicador $contratoItemServicoIndicador
        , array $escopo_glosas): void
    {
        $this->adicionaCampometa($contratoItemServicoIndicador->vlrmeta);
        $this->adicionaCampoTipoAfericao($contratoItemServicoIndicador->tipo_afericao);
        $this->adicionaCampoContratoItemServicoIndicador($contratoItemServicoIndicador->id);
        $this->adicionaCampoFaixa(
            $contratoItemServicoIndicador->tipo_afericao
            , $contratoItemServicoIndicador->vlrmeta
        );
        $this->adicionaCampoValorGlosa();
        $this->adicionaCampoEscopo($escopo_glosas);
    }


    /**
     * Configura a grid de visualização
     *
     * @param array $escopo_glosas
     */
    private function colunas(array $escopo_glosas): void
    {
        $this->adicionaColunaIndicador();
        $this->adicionaColunaAPartirDe();
        $this->adicionaColunaAte();
        $this->adicionaColunaValorGlosa();
        $this->adicionaColunaEscopo($escopo_glosas);

    }


    /**
     * Configura a coluna indicador
     */
    private function adicionaColunaIndicador(): void
    {
        $this->crud->addColumn([
            'name' => 'indicador_nome',
            'label' => 'Indicador',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);

    }


    /**
     * Cofigura a coluna "A Partir de"
     */
    private function adicionaColunaAPartirDe(): void
    {
        $this->crud->addColumn([
            'name' => 'from_formatado',
            'label' => 'A Partir de',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);

    }

    /**
     * Configura a coluna "Até"
     */
    private function adicionaColunaAte(): void
    {
        $this->crud->addColumn([
            'name' => 'to_formatado',
            'label' => 'Até',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }


    /**
     * Configura a coluna 'Valor da Glosa (%)',
     */
    private function adicionaColunaValorGlosa(): void
    {
        $this->crud->addColumn([
            'name' => 'valor_glosa_formatado',
            'label' => 'Valor da Glosa (%)',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    /**
     * Configura a coluna 'Escopo'
     *
     * @param array $escopo_glosas
     */
    private function adicionaColunaEscopo(array $escopo_glosas): void
    {
        $this->crud->addColumn([
            'name' => 'escopo_id',
            'label' => 'Escopo',
            'type' => 'select_from_array',
            'options' => $escopo_glosas,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);

    }

    /**
     * Configura a Faixa dependendo do tipo de aferição
     *
     * @param bool $tipo_afericao
     * @param float $vlrmeta
     */
    private function adicionaCampoFaixa(bool $tipo_afericao, float $vlrmeta): void
    {
        if ($tipo_afericao) {
            $this->adicionaCampoFrom();
            $this->adicionaCampoTo();
            return;
        }
        $this->adicionaCampoSlider($vlrmeta);
    }

    /**
     * Configura o campo escondido "tipo_afericao"
     *
     * @param $tipo_afericao
     */
    private function adicionaCampoTipoAfericao($tipo_afericao): void
    {
        $this->crud->addField([
            'name' => 'tipo_afericao',
            'type' => 'hidden',
            'default' => (int)$tipo_afericao
        ]);
    }

    /**
     * Configura o campo escondido "vlrmeta"
     *
     * @param float $vlrmeta
     */
    private function adicionaCampoMeta(float $vlrmeta): void
    {
        $this->crud->addField([
            'name' => 'vlrmeta',
            'type' => 'hidden',
            'default' => $vlrmeta
        ]);
    }

    /**
     * Configura o campo escondido "contratoitem_servico_indicador_id"
     *
     * @param $contratoitem_servico_indicador_id
     */
    private function adicionaCampoContratoItemServicoIndicador($contratoitem_servico_indicador_id): void
    {
        $this->crud->addField([
            'name' => 'contratoitem_servico_indicador_id',
            'type' => 'hidden',
            'default' => $contratoitem_servico_indicador_id,
        ]);
    }

    /**
     * Configura o campo 'Faixa de ajuste no pagamento'
     *
     * @param float|int $vlrmeta
     */
    private function adicionaCampoSlider(float $vlrmeta = 100): void
    {
        $this->crud->addField([   // Range
            'name' => 'slider',
            'label' => 'Faixa de ajuste no pagamento',
            'type' => 'slider',
            'min' => '0',
            'max' => $vlrmeta - 0.1,
            'step' => '0.1',
            'grid' => true,
        ]);
    }

    /**
     * Configura o campo 'A partir de'
     */
    private function adicionaCampoFrom(): void
    {
        $this->crud->addField([
            'name' => 'from',
            'label' => 'A partir de',
            'type' => 'money',
            'attributes' => [
                'id' => 'from',
            ], // allow decimals
            'prefix' => "> =",
            'allowZero' => "true",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);
    }

    /**
     * Configura o campo "Até"
     */
    private function adicionaCampoTo(): void
    {
        $this->crud->addField([   // Number
            'name' => 'to',
            'label' => 'Até',
            'type' => 'money',
            'attributes' => [
                'id' => 'to',
            ], // allow decimals
            'prefix' => "<",
            'allowZero' => "true",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);
    }

    /**
     * Configura o campo 'Valor da Glosa (%)'
     */
    private function adicionaCampoValorGlosa(): void
    {
        $this->crud->addField([   // Number
            'name' => 'valor_glosa',
            'label' => 'Valor da Glosa (%)',
            'type' => 'money',
            'attributes' => [
                'id' => 'valor_glosa',
            ], // allow decimals
            'allowZero' => "true",
        ]);
    }

    /**
     * Configura o campo 'Escopo da Glosa'
     *
     * @param array $escopo_glosas
     */
    private function adicionaCampoEscopo(array $escopo_glosas): void
    {
        $this->crud->addField([
            'name' => 'escopo_id',
            'label' => 'Escopo da Glosa',
            'type' => 'radio',
            'options' => $escopo_glosas,
            'inline' => true,
        ]);
    }
}
