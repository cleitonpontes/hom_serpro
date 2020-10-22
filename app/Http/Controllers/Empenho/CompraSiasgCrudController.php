<?php

namespace App\Http\Controllers\Empenho;

use Alert;
use App\Http\Controllers\Empenho\Minuta\Etapa1EmpenhoController;
use App\Http\Traits\Formatador;
use App\Models\Codigo;
use App\Models\Codigoitem;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\Unidade;
use App\XML\ApiSiasg;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CompraSiasgRequest as StoreRequest;
use App\Http\Requests\CompraSiasgRequest as UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Route;

class CompraSiasgCrudController extends CrudController
{
    use Formatador;

    const MATERIAL = 149;
    const SERVICO = 150;
    const SISPP = 1;
    const SISRP = 2;

//    const TIPOCOMPRASIASG =

    public function setup()
    {
        $modalidades = Codigoitem::where('codigo_id', 13)
            ->where('visivel', true)
            ->pluck('descricao', 'id')
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Compra');
        $this->crud->setRoute(
            config('backpack.base.route_prefix')
            . '/empenho/buscacompra'
        );
        $this->crud->setEntityNameStrings('Buscar Compra', 'Buscar Compras');
        $this->crud->setCreateView('vendor.backpack.crud.create_compra');

//        $this->crud->denyAccess('create');
//        $this->crud->denyAccess('update');
//        $this->crud->denyAccess('delete');
//        $this->crud->denyAccess('show');

//        (backpack_user()->can('glosa_inserir')) ? $this->crud->allowAccess('create') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        //$this->columns();
        $this->fields($modalidades);

        // add asterisk for fields that are required in GlosaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    private function fields(array $modalidades): void
    {
        $this->setFieldModalidade($modalidades);
        $this->setFieldNumeroAno();
        $this->setFieldUnidadeCompra();
    }

    private function setFieldModalidade($modalidade): void
    {
        $this->crud->addField([
            'name' => 'modalidade_id',
            'label' => "Modalidade Licitação",
            'type' => 'select2_from_array',
            'options' => $modalidade,
            'allows_null' => true
        ]);
    }

    private function setFieldNumeroAno(): void
    {
        $this->crud->addField([
            'name' => 'numero_ano',
            'label' => 'Numero / Ano',
            'type' => 'numcontrato'
        ]);
    }

    private function setFieldUnidadeCompra(): void
    {
        $this->crud->addField([
            'label' => "Unidade Compra",
            'type' => "select2_from_ajax",
            'name' => 'unidade_origem_id',
            'entity' => 'unidade_origem',
            'attribute' => "codigo",
            'attribute2' => "nomeresumido",
            'process_results_template' => 'gescon.process_results_unidade',
            'model' => "App\Models\Unidade",
            'data_source' => url("api/unidade"),
            'placeholder' => "Selecione a Unidade",
            'minimum_input_length' => 2
        ]);
    }

    public function show($id)
    {
        $content = parent::show($id);

//        $this->crud->removeColumn('contratoitem_servico_indicador_id');

        return $content;
    }

    public function store(StoreRequest $request)
    {

        // your additional operations before save here
        $retorno = $this->consultaCompraSiasg($request);

        if (is_null($retorno->data)) {
            return redirect('/empenho/minuta')->with($retorno->messagem, 'alert-warning');
        }

        $this->montaParametrosCompra($retorno, $request);

        if ($this->verificaCompraExiste($request)) {
            Alert::warning('Compra já existe no sistema.')->flash();
            return redirect('/empenho/buscacompra/create');
        }

        $redirect_location = parent::storeCrud($request);

        $params['compra_id'] = $this->crud->entry->id;
        $params['unidade_autorizada_id'] = $this->crud->entry->unidade_origem_id;

        $this->gravaParametroItensdaCompra($retorno, $params);

        $minutaEmpenho = $this->gravaMinutaEmpenho(
            ['compra_id' => $this->crud->entry->id, 'unidade_origem_id' => $this->crud->entry->unidade_origem_id]
        );
        $etapa = $minutaEmpenho->etapa + 1;
        return redirect('/empenho/fornecedor/' . $etapa . '/' . $minutaEmpenho->id);
    }

    public function consultaCompraSiasg(Request $request)
    {
        $numero_ano = explode('/', $request->get('numero_ano'));

        $apiSiasg = new ApiSiasg();

        $params = [
            'modalidade' => '05',//$request->get('modalidade_id'),
            'numeroAno' => $numero_ano[0] . $numero_ano[1],
            'uasgCompra' => '110161',//$request->get('uasg_compra'),
            'uasgUsuario' => session('user_ug')
        ];

        $retorno = json_decode($apiSiasg->executaConsulta('COMPRASISPP', $params));

        return $retorno;
    }

    public function montaParametrosCompra($compraSiasg, $request)
    {
        $unidade_subrogada = $compraSiasg->data->compraSispp->subrogada;
        $request->request->set(
            'unidade_subrrogada_id',
            ($unidade_subrogada <> '000000') ? (int)$this->buscaIdUnidade($unidade_subrogada) : null
        );
        $request->request->set('tipo_compra_id', $this->buscaTipoCompra($compraSiasg->data->compraSispp->tipoCompra));
        $request->request->set('inciso', $compraSiasg->data->compraSispp->inciso);
        $request->request->set('lei', $compraSiasg->data->compraSispp->lei);
    }

    public function buscaIdUnidade($uasg)
    {
        $unidade = Unidade::where('codigo', $uasg)->first();
        return $unidade->id;
    }

    public function buscaTipoCompra($descres)
    {
        $tipoCompraSiasg = Codigo::where('descricao', '=', 'Tipo Compra')->first();
        $tipocompra = Codigoitem::where('codigo_id', $tipoCompraSiasg->id)
            ->where('visivel', true)
            ->where('descres', '0' . $descres)
            ->first();
//        dd($tipocompra);
        return $tipocompra->id;
    }

    public function verificaCompraExiste($request)
    {

        $compra = Compra::where('unidade_origem_id', $request->get('unidade_origem_id'))
            ->where('modalidade_id', $request->get('modalidade_id'))
            ->where('numero_ano', $request->get('numero_ano'))
            ->where('tipo_compra_id', $request->get('tipo_compra_id'))
            ->exists();

        return $compra;
    }

    public function gravaParametroItensdaCompra($compraSiasg, $params)
    {
        $unidade_autorizada_id = $this->retornaUnidadeAutorizada($compraSiasg, $params);

        if (!is_null($compraSiasg->data->itemCompraSisppDTO)) {
            foreach ($compraSiasg->data->itemCompraSisppDTO as $key => $item) {
                $params['tipo_item_id'] = ($item->tipo <> 'S') ? $this::SERVICO : $this::MATERIAL;
                $params['catmatseritem_id'] = intval($item->codigo);
                $params['fornecedor_id'] = $this->retornaIdFornecedor($item);
                $params['unidade_autorizada_id'] = $unidade_autorizada_id;
                $params['descricaodetalhada'] = $item->descricaoDetalhada;
                $params['quantidade'] = $item->quantidadeTotal;
                $params['valorunitario'] = $item->valorUnitario;
                $params['valortotal'] = $item->valorTotal;

                $compraItem = new CompraItem();
                $compraItem->gravaCompraItem($params);
            }
        }
    }

    public function retornaUnidadeAutorizada($compraSiasg, $params)
    {

        $unidade_autorizada_id = null;
        $tipoCompra = $compraSiasg->data->compraSispp->tipoCompra;
        $subrrogada = $compraSiasg->data->compraSispp->subrogada;
        if ($tipoCompra == $this::SISPP) {
            ($subrrogada <> '000000')
                ? $unidade_autorizada_id = (int)$this->buscaIdUnidade($subrrogada)
                : $unidade_autorizada_id = $params['unidade_autorizada_id'];
        }
        if ($tipoCompra == $this::SISRP) {
            //tratar unidade autorizada SISRP - Aguardando Serviço ficar pronto
        }

        return $unidade_autorizada_id;
    }

    public function retornaIdFornecedor($item)
    {
        $fornecedor = new Fornecedor();
        $retorno = $fornecedor->buscaFornecedorPorNumero($item->niFornecedor);

        if (is_null($retorno)) {
            $fornecedor->tipo_fornecedor = $fornecedor->retornaTipoFornecedor($item->niFornecedor);
            $fornecedor->cpf_cnpj_idgener = $fornecedor->formataCnpjCpf($item->niFornecedor);
            $fornecedor->nome = $item->nomeFornecedor;
            $fornecedor->save();
            return $fornecedor->id;
        }
        return $retorno->id;
    }

    public function gravaMinutaEmpenho($params)
    {
        $minutaEmpenho = new MinutaEmpenho();
        $minutaEmpenho->unidade_id = $params['unidade_origem_id'];
        $minutaEmpenho->compra_id = $params['compra_id'];
        $minutaEmpenho->etapa = 1;
        //todo RETIRAR A OBRIGATORIEDADE DA INFORMACAO COMPLEMENTAR
        //todo COLOCAR O TIPO MINUTA EMPENHO
        $minutaEmpenho->informacao_complementar = 'dfadsfadsfds';

        $minutaEmpenho->save();
        return $minutaEmpenho;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $this->setRequestFaixa($request);

        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
