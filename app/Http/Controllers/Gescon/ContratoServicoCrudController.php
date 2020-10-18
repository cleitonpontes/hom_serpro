<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use App\Http\Traits\Formatador;
use Backpack\CRUD\app\Http\Controllers\CrudController;

use App\Http\Requests\ServicoRequest as StoreRequest;
use App\Http\Requests\ServicoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Route;

/**
 * Class ContratoServicoCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 */
class ContratoServicoCrudController extends CrudController
{
    use Formatador;

    public function setup()
    {
        $contrato_id = Route::current()->parameter('contrato_id');
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
        $this->crud->setModel('App\Models\Servico');
        $this->crud->setRoute(
            config('backpack.base.route_prefix')
            . '/gescon/meus-contratos/' . $contrato_id
            . '/servicos'
        );
        $this->crud->setEntityNameStrings('serviço', 'serviços');
        $this->crud->addButtonFromView('top', 'voltar', 'voltarmeucontrato', 'end');

        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        $this->crud->addClause('leftJoin'
            , 'contratoitem_servico', 'contratoitem_servico.servico_id', '=', 'servicos.id'
        );
        $this->crud->addClause('leftJoin'
            , 'contratoitens', 'contratoitens.id', '=', 'contratoitem_servico.contratoitem_id'
        );
        $this->crud->addClause('leftJoin'
            , 'catmatseritens', 'catmatseritens.id', '=', 'contratoitens.catmatseritem_id'
        );
        // Apenas ocorrencias deste contrato_id
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);

        $this->crud->addClause('select', [
            DB::raw('contratoitem_servico.id as contratoitem_servico_id'),
            'contratoitens.contrato_id',
            'contratoitens.descricao_complementar',
            'catmatseritens.descricao',
            // Tabela principal deve ser sempre a última da listagem!
            'servicos.*'
        ]);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        (backpack_user()->can('contrato_servico_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contrato_servico_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contrato_servico_deletar')) ? $this->crud->allowAccess('delete') : null;

        $this->crud->addButtonFromView('line', 'moreindicadores', 'moreindicadores', 'end');

        $this->colunas();
        $this->campos($contrato_id);

        // add asterisk for fields that are required in ServicoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here

        $request->request->set('valor', $this->retornaFormatoAmericano($request->valor));

        $redirect_location = parent::storeCrud($request);

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $request->request->set('valor', $this->retornaFormatoAmericano($request->valor));

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    /**
     * Configura os campos dos formulários de Inserir e Atualizar
     *
     * @param string $contrato_id
     */
    private function campos(string $contrato_id): void
    {
        $this->adicionaCampoContrato($contrato_id);
        $this->adicionaCampoItem($contrato_id);
        $this->adicionaCampoNome();
        $this->adicionaCampoDetalhe();
        $this->adicionaCampoValor();
        $this->adicionaCampoSituacao();
    }

    /**
     * Configura a grid de visualização
     */
    private function colunas(): void
    {
        $this->adicionaColunaNome();
        $this->adicionaColunaItem();
        $this->adicionaColunaDetalhe();
        $this->adicionaColunaValor();
        $this->adicionaColunaSituacao();
    }

    /**
     * Configura o campo Contrato
     * @param string $contrato_id
     */
    private function adicionaCampoContrato(string $contrato_id): void
    {
        $this->crud->addField([   // Hidden
            'name' => 'contrato_id',
            'type' => 'hidden',
            'default' => $contrato_id,
        ]);
    }

    /**
     * Configura o campo Item
     * @param string $contrato_id
     */
    private function adicionaCampoItem(string $contrato_id): void
    {
        $this->crud->addField([
            'label' => 'Item do contrato',
            'type' => 'select2_multiple',
            'name' => 'contratoItens',
            'entity' => 'Contratoitem',
            'attribute' => 'descricao_item',
            'attribute2' => 'descricao_complementar',
            'attribute_separator' => ' - DESCRIÇÃO COMPLEMENTAR:  ',
            'model' => "App\Models\Contratoitem",
            'pivot' => true,
            'options' => (function ($query) use ($contrato_id) {
                return $query->where('contrato_id', $contrato_id)->get();
            }),
        ]);
    }

    /**
     * Configura o campo Nome
     */
    private function adicionaCampoNome(): void
    {
        $this->crud->addField([
            'name' => 'nome',
            'label' => 'Nome',
            'type' => 'text',
            'attributes' => [
                'onfocusout' => "maiuscula(this)",
                'maxlength' => "255",
            ],
        ]);
    }

    /**
     * Configura o campo Detalhe
     */
    private function adicionaCampoDetalhe(): void
    {
        $this->crud->addField([
            'name' => 'detalhe',
            'label' => 'Detalhe',
            'type' => 'textarea',
            'attributes' => [
                'onfocusout' => "maiuscula(this)"
            ],
        ]);
    }

    /**
     * Configura o campo Valor
     */
    private function adicionaCampoValor(): void
    {
        $this->crud->addField([
            'name' => 'valor',
            'label' => 'Valor',
            'type' => 'money',
            'attributes' => [
                'id' => 'valor',
            ], // allow decimals
            'prefix' => "R$",
        ]);
    }

    /**
     * Configura o campo Situacao
     */
    private function adicionaCampoSituacao(): void
    {
        $this->crud->addField([
            'name' => 'situacao',
            'label' => "Situação",
            'type' => 'select2_from_array',
            'options' => [1 => 'Ativo', 0 => 'Inativo'],
            'allows_null' => false,
        ]);
    }

    /**
     * Configura a coluna Nome
     */
    private function adicionaColunaNome(): void
    {
        $this->crud->addColumn([
            'name' => 'nome',
            'label' => 'Nome',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('servicos.nome', 'ilike', "%" . $searchTerm . "%");
            },
        ]);
    }

    /**
     * Configura a coluna Item
     */
    private function adicionaColunaItem(): void
    {
        $this->crud->addColumn([
            'name' => 'descricao',
            'label' => 'Item do contrato',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('catmatseritens.descricao', 'ilike', "%" . $searchTerm . "%");
            },
        ]);
    }

    /**
     * Configura a coluna Detalhe
     */
    private function adicionaColunaDetalhe(): void
    {
        $this->crud->addColumn([
                'name' => 'detalhe',
                'label' => 'Detalhe',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('servicos.detalhe', 'ilike', "%" . $searchTerm . "%");
                }
            ]);
    }

    /**
     * Configura a coluna Valor
     */
    private function adicionaColunaValor(): void
    {
        $this->crud->addColumn([
                'name' => 'valor_formatado',
                'label' => 'Valor',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ]);
    }

    /**
     * Configura a coluna Situacao
     */
    private function adicionaColunaSituacao(): void
    {
        $this->crud->addColumn([
            'name' => 'situacao',
            'label' => 'Situação',
            'type' => 'boolean',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'options' => [0 => 'Inativo', 1 => 'Ativo']
        ]);
    }



    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->addColumn([
            'name' => 'descricao',
            'label' => 'Itens do contrato',
            'type' => 'select_multiple',

            'entity' => 'contratoItens', // the method that defines the relationship in your Model
            'attribute' => 'descricao_item', // foreign key attribute that is shown to user
            'attribute2' => 'descricao_item', // foreign key attribute that is shown to user
            'model' => "App\Models\Contratoitem", // foreign key model

        ]);


        return $content;
    }
}
