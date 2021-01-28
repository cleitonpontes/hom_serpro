<?php

namespace App\Http\Controllers\Empenho;

use Alert;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\ContaCorrentePassivoAnterior;
use App\Models\ContratoItemMinutaEmpenho;
use App\Models\MinutaEmpenho;
use App\Models\MinutaEmpenhoRemessa;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContaCorrentePassivoAnteriorRequest as StoreRequest;
use App\Http\Requests\ContaCorrentePassivoAnteriorRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;
use Exception;
use Redirect;
use Route;

/**
 * Class ContaCorrentePassivoAnteriorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MinutaAlteracaoPassivoAnteriorCrudController extends CrudController
{
    public function setup()
    {

        $minuta_id = Route::current()->parameter('minuta_id');
        $remessa = Route::current()->parameter('remessa');
        $minuta = MinutaEmpenho::find($minuta_id);
        $contaContabilPassivoAnterior = $minuta->conta_contabil_passivo_anterior;
        $this->crud->update_form = strpos(Route::current()->uri, 'edit');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ContaCorrentePassivoAnterior');

        $this->crud->setRoute(config('backpack.base.route_prefix') . 'empenho/minuta/' . $minuta_id . "/alteracao/passivo-anterior/$remessa");

        $this->crud->setEntityNameStrings('Conta Corrente Passivo Anterior', 'Contas Corrente Passivo Anterior');

        $this->crud->addClause(
            'rightJoin',
            'minutaempenhos',
            'minutaempenhos.id',
            '=',
            'conta_corrente_passivo_anterior.minutaempenho_id'
        );
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'minutaempenhos.fornecedor_empenho_id');

        $this->crud->addClause('join', 'compras', 'compras.id', '=', 'minutaempenhos.compra_id');

        $this->crud->addClause('where', 'minutaempenhos.id', $minuta_id);

        $this->crud->groupBy(["conta_corrente_passivo_anterior.id", "minutaempenhos.id", "fornecedores.cpf_cnpj_idgener"]);

        if ($minuta->empenho_por === 'Compra' || $minuta->empenho_por === 'Suprimento') {
            $this->crud->addClause(
                'select',
                'conta_corrente_passivo_anterior.*',
                'minutaempenhos.id',
                'minutaempenhos.passivo_anterior',
                'minutaempenhos.etapa',
                'fornecedores.cpf_cnpj_idgener',
                DB::raw('sum(compra_item_minuta_empenho.valor) as valor_total'),
                'minutaempenhos.valor_total',
                'minutaempenhos.conta_contabil_passivo_anterior',
                DB::raw("replace(replace(replace(fornecedores.cpf_cnpj_idgener,'-',''),'.',''),'/','') as conta_corrente_padrao")
            );
            $this->crud->addClause('join', 'compra_item_minuta_empenho', 'compra_item_minuta_empenho.minutaempenho_id', '=', 'minutaempenhos.id');
            $this->crud->addClause('where', 'compra_item_minuta_empenho.minutaempenhos_remessa_id', $remessa);
            $valor_total = CompraItemMinutaEmpenho::where('compra_item_minuta_empenho.minutaempenho_id', $minuta_id);
        }
        if ($minuta->empenho_por === 'Contrato') {
            $this->crud->addClause(
                'select',
                'conta_corrente_passivo_anterior.*',
                'minutaempenhos.id',
                'minutaempenhos.passivo_anterior',
                'minutaempenhos.etapa',
                'fornecedores.cpf_cnpj_idgener',
                DB::raw('sum(contrato_item_minuta_empenho.valor) as valor_total'),
                'minutaempenhos.valor_total',
                'minutaempenhos.conta_contabil_passivo_anterior',
                DB::raw("replace(replace(replace(fornecedores.cpf_cnpj_idgener,'-',''),'.',''),'/','') as conta_corrente_padrao")
            );
            $this->crud->addClause(
                'join',
                'contrato_item_minuta_empenho',
                'contrato_item_minuta_empenho.minutaempenho_id',
                '=',
                'minutaempenhos.id'
            );
            $this->crud->addClause(
                'join',
                'minutaempenhos_remessa',
                'minutaempenhos_remessa.id',
                '=',
                'contrato_item_minuta_empenho.minutaempenhos_remessa_id'
            );
            $this->crud->addClause('where', 'contrato_item_minuta_empenho.minutaempenhos_remessa_id', $remessa);
            $valor_total = ContratoItemMinutaEmpenho::where(
                'contrato_item_minuta_empenho.minutaempenho_id',
                $minuta_id
            );
        }

        $valor_total = $valor_total->select(DB::raw('coalesce(sum(valor),0) as sum'))
            ->first()->toArray();


        $query = $this->crud->query->first();
        $params = ['valor_total' => $valor_total['sum'], 'conta_corrente_padrao' => $query->conta_corrente_padrao];

        $this->crud->params = $params;

        $this->crud->setCreateView('vendor.backpack.crud.empenho.create_passivo');
//        $this->crud->setEditView('vendor.backpack.crud.empenho.edit');
        $this->crud->urlVoltar = route(
            'empenho.crud.alteracao.edit',
            ['minuta_id' => $minuta_id, 'remessa_id' => $remessa, 'minuta' => $minuta_id]
        );

//        dd($this->crud->query->toSql());
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->fields($minuta_id, $remessa, $contaContabilPassivoAnterior, $valor_total['sum']);

        // add asterisk for fields that are required in ContaCorrentePassivoAnteriorRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        $minuta = MinutaEmpenho::find($request->minutaempenho_id);
        $remessa_id = Route::current()->parameter('remessa');

        DB::beginTransaction();
        try {
            $valor_total_conta = array_sum($request->valor);
            if ($this->crud->params['valor_total'] != $valor_total_conta) {
                Alert::warning('Somatório das contas não pode ser diferente do valor total da minuta!')->flash();
                return redirect()->back();
            }

            foreach ($request->numConta as $key => $value) {
                $conta = new ContaCorrentePassivoAnterior();
                $conta->minutaempenho_id = $request->minutaempenho_id;
                $conta->conta_corrente = $value;
                $conta->valor = $request->valor[$key];
                $conta->minutaempenhos_remessa_id = $remessa_id;
                $conta->save();
            }

            $modRemessa = MinutaEmpenhoRemessa::find($remessa_id);
            $modRemessa->etapa = 2;
            $modRemessa->save();
            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
        }

        return Redirect::to("empenho/minuta/{$request->minutaempenho_id}/alteracao/{$remessa_id}/" .
            "show/{$request->minutaempenho_id}");
    }

    public function update(UpdateRequest $request)
    {

        $remessa_id = Route::current()->parameter('remessa');

        DB::beginTransaction();
        try {
            $valor_total_conta = array_sum($request->valor);
            if ($this->crud->params['valor_total'] != $valor_total_conta) {
                Alert::warning('Somatório das contas não pode ser diferente do valor total da minuta!')->flash();
                return redirect()->back();
            }

            ContaCorrentePassivoAnterior::where('minutaempenhos_remessa_id', $remessa_id)->forceDelete();

            foreach ($request->numConta as $key => $value) {
                $conta = new ContaCorrentePassivoAnterior();
                $conta->minutaempenho_id = $request->minutaempenho_id;
                $conta->conta_corrente = $value;
                $conta->valor = $request->valor[$key];
                $conta->minutaempenhos_remessa_id = $remessa_id;
                $conta->save();
            }

            $modRemessa = MinutaEmpenhoRemessa::find($remessa_id);
            $modRemessa->etapa = 2;
            $modRemessa->save();
            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
        }

        return Redirect::to("empenho/minuta/{$request->minutaempenho_id}/alteracao/{$remessa_id}/" .
            "show/{$request->minutaempenho_id}");
    }

    public function deletaPassivoAnterior(array $modPassivoAnterior)
    {
        foreach ($modPassivoAnterior as $key => $value) {
            ContaCorrentePassivoAnterior::where('id', $value['id'])->forceDelete();
        }
    }

    private function fields($minuta_id, $remessa, $contaContabilPassivoAnterior, $valor_total_minuta): void
    {
        $this->setFieldMinutaId($minuta_id);
        $this->setFieldRemessa($remessa);
        $this->setFieldPassivoAnterior();
        $this->setFieldValorTotalMinuta($valor_total_minuta);
        $this->setFieldContaContabil($contaContabilPassivoAnterior);
        $this->setFieldTablePassivoAnterior();
    }

    private function setFieldMinutaId($minuta_id): void
    {
        $this->crud->addField([
            'name' => 'minutaempenho_id',
            'type' => 'hidden',
            'value' => $minuta_id
        ]);
    }

    private function setFieldRemessa($remessa): void
    {
        $this->crud->addField([
            'name' => 'remessa',
            'type' => 'hidden',
            'value' => $remessa
        ]);
    }

    private function setFieldPassivoAnterior(): void
    {
        $this->crud->addField([
            'name' => 'passivo_anterior',
            'label' => 'Passivo Anterior',
            'type' => 'checkbox',
            'attributes' => [
                'id' => 'passivo',
                'disabled' => 'disabled'
            ],
            'value' => true
        ]);
    }

    private function setFieldValorTotalMinuta($valor_total_minuta): void
    {
        $this->crud->addField([
            'name' => 'valor_total',
            'label' => 'Valor Total da Minuta:',
            'type' => 'text',
            'value' => $valor_total_minuta,
            'attributes' => [
                'disabled' => 'disabled',
            ],
        ]);
    }

    private function setFieldContaContabil($contaContabilPassivoAnterior): void
    {

        $this->crud->addField([
            'name' => 'conta_contabil_passivo_anterior',
            'label' => 'Conta Contábil',
            'type' => 'text',
            'attributes' => [
                'disabled' => 'disabled'
            ],
            'value' => $contaContabilPassivoAnterior,
        ]);
    }

    private function setFieldTablePassivoAnterior(): void
    {

        $this->crud->addField([
            'name' => 'conta_corrente_json',
            'label' => 'Conta Corrente',
            'type' => 'empenho_nova_table',
            'entity_singular' => 'options', // used on the "Add X" button
            'columns' => [
                'conta_corrente' => 'Número Conta Corrente',
                'valor' => 'Valor'
            ],
            'max' => 99, // maximum rows allowed in the table
            'min' => 0, // minimum rows allowed in the table
        ]);
    }
}
