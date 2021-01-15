<?php

namespace App\Http\Controllers\Empenho;

use Alert;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\ContaCorrentePassivoAnterior;
use App\Models\MinutaEmpenho;
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

        $minuta_id = isset($minuta_id) ? $minuta_id : '';

        $passivoAnterior = '';
        $contaContabilPassivoAnterior = '';


        if (!(null !== Route::current()->parameter('minuta_id'))
            && $this->crud->getCurrentEntryId() !== false
        ) {
            $modPassivoAnterior = ContaCorrentePassivoAnterior::find($this->crud->getCurrentEntryId());
            $modMinuta = $modPassivoAnterior->minutaempenho()->first();
            $minuta_id = $modMinuta->id;

            $valor_total_minuta = $modMinuta->valor_total;
            $passivoAnterior = $modMinuta->passivo_anterior;
            $contaContabilPassivoAnterior = $modMinuta->conta_contabil_passivo_anterior;
        }

        if (Route::current()->parameter('minuta_id') == null && $this->crud->getCurrentEntryId() == false) {
            $minuta_id = $this->crud->request->minutaempenho_id;
        }

        $minuta = MinutaEmpenho::find($minuta_id);
        $valor_total_minuta = $minuta->valor_total;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ContaCorrentePassivoAnterior');
        //todo verificar esta rota

//        $this->crud->setRoute(config('backpack.base.route_prefix') . 'empenho/passivo-anterior');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'empenho/minuta/' . $minuta_id . "/alteracao/passivo-anterior/$remessa");

        $this->crud->setEntityNameStrings('Conta Corrente Passivo Anterior', 'Contas Corrente Passivo Anterior');

        $this->crud->addClause('rightJoin', 'minutaempenhos', 'minutaempenhos.id', '=', 'conta_corrente_passivo_anterior.minutaempenho_id');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'minutaempenhos.fornecedor_empenho_id');
        $this->crud->addClause('join', 'compras', 'compras.id', '=', 'minutaempenhos.compra_id');
        $this->crud->addClause('join', 'compra_item_minuta_empenho', 'compra_item_minuta_empenho.minutaempenho_id', '=', 'minutaempenhos.id');
        $this->crud->addClause('where', 'minutaempenhos.id', $minuta_id);
        $this->crud->addClause('where', 'compra_item_minuta_empenho.minutaempenhos_remessa_id', $remessa);
//        $this->crud->addClause('join', 'compra_items', 'compra_items.compra_id', '=', 'compras.id');
        $this->crud->groupBy(["conta_corrente_passivo_anterior.id", "minutaempenhos.id", "fornecedores.cpf_cnpj_idgener"]);
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
//        dd($this->crud->query->getBindings(),$this->crud->query->toSql());
//        dd($this->crud->query->first());
        $query = $this->crud->query->first();
        $params = ['valor_total' => $query->valor_total, 'conta_corrente_padrao' => $query->conta_corrente_padrao];

        $this->crud->params = $params;

        $this->crud->setCreateView('vendor.backpack.crud.empenho.create_passivo');
        $this->crud->setEditView('vendor.backpack.crud.empenho.edit');
        $this->crud->urlVoltar = route(
            'empenho.crud./minuta.edit',
            ['minutum' => $minuta_id]
        );

//        dd($this->crud->query->toSql());
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->fields($minuta_id, $remessa, $passivoAnterior, $contaContabilPassivoAnterior, $valor_total_minuta);

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
            if ($request->passivo_anterior == 1) {
                $valor_total_conta = array_sum($request->valor);
                if ($minuta->valor_total != $valor_total_conta) {
                    Alert::warning('Somatório das contas não pode ser diferente do valor total da minuta!')->flash();
                    return redirect()->back();
                }

                foreach ($request->numConta as $key => $value) {
                    $itens[$key]['minutaempenho_id'] = $request->minutaempenho_id;
                    $itens[$key]['conta_corrente'] = $value;
                    $itens[$key]['valor'] = $request->valor[$key];
                    $itens[$key]['minutaempenhos_remessa_id'] = $remessa_id;
                }

                ContaCorrentePassivoAnterior::insert($itens);
            }
            DB::commit();
            return Redirect::to("empenho/minuta/{$request->minutaempenho_id}/alteracao/{$remessa_id}/" .
                "show/{$request->minutaempenho_id}");
        } catch (Exception $exc) {
            DB::rollback();
        }

        return Redirect::to('empenho/minuta/' . $minuta->id);
    }

    public function update(UpdateRequest $request)
    {
        dump('update');
        dd($request->all());
        $minuta = MinutaEmpenho::find($request->minutaempenho_id);
        $itens = json_decode($request->get('conta_corrente_json'), true);
        $arrayPassivoAnterior = ContaCorrentePassivoAnterior::where(
            'minutaempenho_id',
            $request->minutaempenho_id
        )->get()->toArray();
        dd($arrayPassivoAnterior);

        $itens = array_map(
            function ($itens) use ($request, $arrayPassivoAnterior) {
                $itens['minutaempenho_id'] = $arrayPassivoAnterior[0]['minutaempenho_id'];
                $itens['conta_corrente_json'] = $request->conta_corrente_json;
                return $itens;
            },
            $itens
        );

        DB::beginTransaction();
        try {
            $this->deletaPassivoAnterior($arrayPassivoAnterior);
            ContaCorrentePassivoAnterior::insert($itens);
            DB::commit();
        } catch (Exception $exc) {
            //  dd($exc);
            DB::rollback();
        }

        return Redirect::to('empenho/minuta/' . $minuta->id);
    }


    public function deletaPassivoAnterior(array $modPassivoAnterior)
    {
        foreach ($modPassivoAnterior as $key => $value) {
            ContaCorrentePassivoAnterior::where('id', $value['id'])->forceDelete();
        }
    }

    private function fields($minuta_id, $remessa, $passivoAnterior, $contaContabilPassivoAnterior, $valor_total_minuta): void
    {
        $this->setFieldMinutaId($minuta_id);
        $this->setFieldRemessa($remessa);
        $this->setFieldPassivoAnterior($passivoAnterior);
        $this->setFieldValorTotalMinuta($valor_total_minuta);
        $this->setFieldContaContabil($contaContabilPassivoAnterior, $passivoAnterior);
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

    private function setFieldPassivoAnterior($passivoAnterior): void
    {
        $this->crud->addField([
            'name' => 'passivo_anterior',
            'label' => 'Passivo Anterior',
            'type' => 'passivo_anterior_checkbox',
            'attributes' => [
                'id' => 'passivo'
            ],
            'value' => $passivoAnterior
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

    private function setFieldContaContabil($contaContabilPassivoAnterior, $passivoAnterior): void
    {

        $this->crud->addField([
            'name' => 'conta_contabil_passivo_anterior',
            'label' => 'Conta Contábil',
            'type' => 'conta_contabil_text',
            'attributes' => [
                'id' => 'contabil'
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
