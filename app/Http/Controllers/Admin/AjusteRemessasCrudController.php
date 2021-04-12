<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\BuscaCodigoItens;
use App\Models\MinutaEmpenho;
use App\Models\MinutaEmpenhoRemessa;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\AjusteRemessasRequest as UpdateRequest;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class AjusteRemessasCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AjusteRemessasCrudController extends CrudController
{
    use BuscaCodigoItens;

    public function setup()
    {
        if(!backpack_user()->hasRole('Administrador')){
            abort('403', config('app.erro_permissao'));
        }

        $this->remessa_id = $this->crud->getCurrentEntryId();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\MinutaEmpenhoRemessa');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/ajusteremessas');
        $this->crud->setEntityNameStrings('Ajuste de remessas', 'Ajuste de remessas');

        $this->crud->allowAccess('update');
        $this->crud->denyAccess('show');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('create');
        $this->crud->addButtonFromView('line', 'atualizaritemcompracontrato', 'atualizaritemcompracontrato', 'end');

        (backpack_user()->can('minuta_ajuste_editar')) ? $this->crud->allowAccess('update') : null;


        $this->crud->addClause('select',
            [
                'minutaempenhos_remessa.*',
                DB::raw("CONCAT(unidades.codigo,' - ',unidades.nomeresumido) AS unidade"),
                DB::raw("CONCAT(uc.codigo,' - ',uc.nomeresumido) AS unidade_compra"),
                DB::raw("CONCAT(modalidade.descres,' - ', modalidade.descricao) AS compra_modalidade"),
                'codigoitens.descricao AS tipoEmpenhoPor',
                'compras.numero_ano',
                'minutaempenhos.mensagem_siafi AS empenho',
                DB::raw('minutaempenhos_remessa.id as "minutaempenhos_remessa_id"')
            ]
        );
        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));
        $this->crud->addClause('where', 'minutaempenhos_remessa.remessa', '<>', 0);
        $this->crud->addClause('join', 'minutaempenhos', 'minutaempenhos_remessa.minutaempenho_id', '=', 'minutaempenhos.id');
        $this->crud->addClause('join', 'unidades', 'minutaempenhos.unidade_id', '=', 'unidades.id');
        $this->crud->addClause('join', 'compras', 'minutaempenhos.compra_id', '=', 'compras.id');
        $this->crud->addClause('join', 'unidades as uc', 'compras.unidade_origem_id', '=', 'uc.id');
        $this->crud->addClause('join', 'codigoitens', 'minutaempenhos.tipo_empenhopor_id', '=', 'codigoitens.id');
        $this->crud->addClause('join', 'codigoitens as modalidade', 'compras.modalidade_id', '=', 'modalidade.id');
        $this->crud->orderBy('minutaempenhos.updated_at', 'desc');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();
        $this->crud->enableExportButtons();
        $this->aplicaFiltros();

        $this->adicionaCampos($this->remessa_id);
        $this->adicionaColunas();


        // add asterisk for fields that are required in AjusteRemessasRequest
//        $this->crud->setRequiredFields(StoreRequest::class, 'create');
//        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->adicionaBoxItens($id);
        $this->adicionaBoxSaldo($id);

        $this->crud->removeColumn('tipo_empenhopor_id');
        $this->crud->removeColumn('situacao_id');
        $this->crud->removeColumn('unidade_id');
        $this->crud->removeColumn('compra_id');

        $this->crud->removeColumn('fornecedor_compra_id');
        $this->crud->removeColumn('fornecedor_empenho_id');
        $this->crud->removeColumn('saldo_contabil_id');

        $this->crud->removeColumn('tipo_empenho_id');
        $this->crud->removeColumn('amparo_legal_id');

        $this->crud->removeColumn('numero_empenho_sequencial');
        $this->crud->removeColumn('passivo_anterior');
        $this->crud->removeColumn('conta_contabil_passivo_anterior');
        $this->crud->removeColumn('tipo_minuta_empenho');

        return $content;
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
        try {
            DB::beginTransaction();
            $minutaEmpenhoRemessa = MinutaEmpenhoRemessa::find($request->id);
            $minutaEmpenhoRemessa->mensagem_siafi = $request->mensagem_siafi;
            $minutaEmpenhoRemessa->situacao_id = $request->nova_situacao;
            $minutaEmpenhoRemessa->save();

            $redirect_location = parent::updateCrud($request);
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
//        return Redirect::to($rota);

            DB::commit();
            return $redirect_location;
        } catch (Exception $exc) {
            DB::rollback();
        }
    }

    protected function adicionaCampos($minuta_id)
    {
        $this->adicionaCampoMensagemSIAF();
        $this->adicionaCampoSituacao($minuta_id);
    }

    protected function aplicaFiltros()
    {
        $this->aplicaFiltroSituacao();
    }

    protected function adicionaCampoMensagemSIAF()
    {
        $this->crud->addField([
            'name' => 'mensagem_siafi',
            'label' => 'Mensagem SIAF',
            'type' => 'text',
            'allows_null' => false,
        ]);
    }

    protected function adicionaCampoSituacao($id)
    {
        $minutaRemessa = MinutaEmpenhoRemessa::find($id);
        $this->crud->addField([
            'name' => 'nova_situacao',
            'label' => "Situação do Empenho",
            'type' => 'select2_from_array',
            'options' => $this->retornaArrayCodigosItens('Situações Minuta Empenho'),
            'allows_null' => false,
            'default' => $minutaRemessa ? $minutaRemessa->situacao_id : null,
        ]);
    }

    protected function adicionaColunas(): void
    {
        $this->adicionaColunaUnidade();
        $this->adicionaColunaUnidadeCompra();
        $this->adicionaColunaModalidade();
        $this->adicionaColunaTipoEmpenhoPor();
        $this->adicionaColunaNumeroAnoCompra();
        $this->adicionaColunaEmpenho();
        $this->adicionaColunaMensagemSiafi();
        $this->adicionaColunaRemessa();
        $this->adicionaColunaSituacao();
        $this->adicionaColunaUpdatedAt();
    }

    public function adicionaColunaSituacao(): void
    {
        $this->crud->addColumn([
            'name' => 'getSituacao',
            'label' => 'Situação',
            'type' => 'model_function',
            'function_name' => 'getSituacao',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    public function adicionaColunaMensagemSiafi(): void
    {
        $this->crud->addColumn([
            'name' => 'mensagem_siafi',
            'label' => 'Mensagem SIAFI',
            'type' => 'text',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('minutaempenhos.mensagem_siafi', 'like', "%$searchTerm%");
            },
        ]);
    }

    public function adicionaColunaEmpenho(): void
    {
        $this->crud->addColumn([
            'name' => 'empenho',
            'label' => 'Empenho',
            'type' => 'text',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('minutaempenhos.mensagem_siafi', 'like', "%$searchTerm%");
            },
        ]);
    }

    public function adicionaColunaRemessa(): void
    {
        $this->crud->addColumn([
            'name' => 'remessa',
            'label' => 'Remessa',
            'type' => 'text',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('minutaempenhos.mensagem_siafi', 'like', "%$searchTerm%");
            },
        ]);
    }

    public function adicionaColunaUnidade(): void
    {
        $this->crud->addColumn([
            'name' => 'unidade',
            'label' => 'Unidade Gestora',
            'type' => 'text',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('minutaempenhos.mensagem_siafi', 'like', "%$searchTerm%");
            },
        ]);
    }

    public function adicionaColunaUnidadeCompra(): void
    {
        $this->crud->addColumn([
            'name' => 'unidade_compra',
            'label' => 'UASG Compra',
            'type' => 'text',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('minutaempenhos.mensagem_siafi', 'like', "%$searchTerm%");
            },
        ]);
    }

    public function adicionaColunaUpdatedAt(): void
    {
        $this->crud->addColumn([
            'name' => 'updated_at',
            'label' => 'Atualizado em',
            'type' => 'datetime',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    public function adicionaColunaTipoEmpenhoPor()
    {

        $this->crud->addColumn([
            'name' => 'tipoEmpenhoPor',
            'label' => 'Tipo de Minuta', // Table column heading
            'type' => 'text',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('minutaempenhos.mensagem_siafi', 'like', "%$searchTerm%");
            },
        ]);
    }

    public function adicionaColunaModalidade()
    {
        $this->crud->addColumn([
            'name' => 'compra_modalidade',
            'label' => 'Modalidade', // Table column heading
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaColunaTipoCompra()
    {
        $this->crud->addColumn([
            'name' => 'tipo_compra',
            'label' => 'Tipo da Compra', // Table column heading
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaColunaNumeroAnoCompra()
    {
        $this->crud->addColumn([
            'name' => 'numero_ano',
            'label' => 'Numero/Ano', // Table column heading
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    protected function aplicaFiltroSituacao()
    {
        $this->crud->addFilter([
            'name' => 'getSituacao',
            'label' => 'Situação',
            'type' => 'select2_multiple',

        ], [
            217 => 'EMPENHO EMITIDO',
            215 => 'EM PROCESSAMENTO',
            214 => 'EM ANDAMENTO',
            218 => 'EMPENHO CANCELADO',
            216 => 'ERRO',
        ], function ($value) {
            $this->crud->addClause(
                'whereIn',
                'minutaempenhos_remessa.situacao_id',
                json_decode($value)
            );
        });
    }
}
