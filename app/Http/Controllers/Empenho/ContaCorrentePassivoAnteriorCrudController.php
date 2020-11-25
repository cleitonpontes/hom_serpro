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
class ContaCorrentePassivoAnteriorCrudController extends CrudController
{
    public function setup()
    {
        $minuta_id = Route::current()->parameter('minuta_id');

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
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'empenho/passivo-anterior');
        $this->crud->setEntityNameStrings('Conta Corrente Passivo Anterior', 'Contas Corrente Passivo Anterior');
        $this->crud->addClause('rightJoin', 'minutaempenhos', 'minutaempenhos.id', '=', 'conta_corrente_passivo_anterior.minutaempenho_id');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'minutaempenhos.fornecedor_empenho_id');
        $this->crud->addClause('join', 'compras', 'compras.id', '=', 'minutaempenhos.compra_id');
        $this->crud->addClause('where', 'minutaempenhos.id', $minuta_id);
//        $this->crud->addClause('join', 'compra_items', 'compra_items.compra_id', '=', 'compras.id');
        $this->crud->addClause(
            'select',
            'conta_corrente_passivo_anterior.*',
            'minutaempenhos.id',
            'minutaempenhos.passivo_anterior',
            'minutaempenhos.etapa',
            'fornecedores.cpf_cnpj_idgener',
            'minutaempenhos.valor_total',
            'minutaempenhos.conta_contabil_passivo_anterior',
            DB::raw("replace(replace(replace(fornecedores.cpf_cnpj_idgener,'-',''),'.',''),'/','') as conta_corrente_padrao")
        );
//        dd($this->crud->query->getBindings(),$this->crud->query->toSql());
//        dd($this->crud->query->first());
        $query = $this->crud->query->first();
        $params = ['valor_total' => $query->valor_total, 'conta_corrente_padrao' => $query->conta_corrente_padrao];

        $this->crud->params = $params;

        $this->crud->setEditView('vendor.backpack.crud.empenho.edit');
        $this->crud->setCreateView('vendor.backpack.crud.empenho.create');
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

        $this->fields($minuta_id, $passivoAnterior, $contaContabilPassivoAnterior, $valor_total_minuta);

        // add asterisk for fields that are required in ContaCorrentePassivoAnteriorRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {

        $minuta = MinutaEmpenho::find($request->minutaempenho_id);
        DB::beginTransaction();
        try {
            if ($request->passivo_anterior == 1) {
                //caso precise injetar o valor padrão na consulta
                if (str_contains($request->conta_corrente_json, "{}")) {
                    $conta_corrente_json = $request->conta_corrente_json;
                    $conta_corrente_json = str_replace(
                        '{}',
                        '{"conta_corrente":"' . $request->conta_corrente_p . '","valor":"' . $request->valor_total_p . '"}',
                        $conta_corrente_json
                    );
                    $request->request->set('conta_corrente_json', $conta_corrente_json);
                }

                $itens = json_decode($request->get('conta_corrente_json'), true);
                if (!is_null($itens)) {
                    $valor_total_conta = 0;
                    foreach ($itens as $key => $item) {
                        $valor_total_conta += $item['valor'];
                    }

                    $itens = array_map(
                        function ($itens) use ($request) {
                            $itens['minutaempenho_id'] = $request->minutaempenho_id;
                            $itens['conta_corrente_json'] = $request->conta_corrente_json;
                            return $itens;
                        },
                        $itens
                    );
                    ContaCorrentePassivoAnterior::insert($itens);
                    if ($request->valor_total_p != $valor_total_conta) {
                        Alert::warning('Somatório das contas não pode ser diferente do valor total da minuta!')->flash();
                        return redirect()->back();
                    }
                }
            }

            $minuta->etapa = 8;
            $minuta->passivo_anterior = $request->passivo_anterior;
            $minuta->conta_contabil_passivo_anterior = $request->conta_contabil_passivo_anterior;

            $minuta->save();
            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
            dd($exc);
        }

        return Redirect::to('empenho/minuta/' . $minuta->id);
    }

    public function update(UpdateRequest $request)
    {
        $minuta = MinutaEmpenho::find($request->minutaempenho_id);
        $itens = json_decode($request->get('conta_corrente_json'), true);
        $arrayPassivoAnterior = ContaCorrentePassivoAnterior::where(
            'minutaempenho_id',
            $request->minutaempenho_id
        )->get()->toArray();

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
            $minuta->etapa = 8;
            $minuta->passivo_anterior = $request->passivo_anterior;
            $minuta->conta_contabil_passivo_anterior = $request->conta_contabil_passivo_anterior;

            $minuta->save();
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

    private function fields($minuta_id, $passivoAnterior, $contaContabilPassivoAnterior, $valor_total_minuta): void
    {
        $this->setFieldMinutaId($minuta_id);
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
            'type' => 'empenho_table',
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
