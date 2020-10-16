<?php

namespace App\Http\Controllers\Empenho\Minuta;

use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\Fornecedor;
use App\Models\Unidade;
use App\XML\ApiSiasg;
use App\Forms\NovoEmepenhoTela1Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Empenho\Minuta\BaseController;


class Tela1EmpenhoController extends BaseController
{
    const MATERIAL = 149;
    const SERVICO = 150;
    const SISPP = 1;
    const SISRP = 2;
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function novo()
    {

        $form = \FormBuilder::create(NovoEmepenhoTela1Form::class, [
            'url' => route('empenho.minuta.tela.1.gravar'),
            'method' => 'POST'
        ]);

        return view('backpack::mod.empenho.telas.tela1', compact('form'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gravar(Request $request)
    {
        $retorno = $this->consultaCompraSiasg($request);

        if(is_null($retorno->data)){
            return redirect('/empenho/minuta/tela/1')->with($retorno->messagem,'alert-warning');
        }

        $params['unidade_origem_id'] = $request->get('uasg_compra');
        $params['modalidade_id'] = $request->get('modalidade');
        $params['numero_ano'] = $request->get('numero_ano');

        $params = $this->montaParametrosCompra($retorno,$params);

        $novaCompra = new Compra();
        $compra_id = $novaCompra->gravaCompra($params);

        unset($params);
        $params['compra_id'] = $compra_id;
        $params['unidade_autorizada_id'] = $request->get('uasg_compra');
        $params = $this->montaParametroItensdaCompra($retorno,$params);

       dd('die');

    }

    public function consultaCompraSiasg(Request $request)
    {
        $numero_ano= explode('/',$request->get('numero_ano'));

        $apiSiasg = new ApiSiasg();

        $params = [
            'modalidade' => $request->get('modalidade'),
            'numeroAno' => $numero_ano[0].$numero_ano[1],
            'uasgCompra' => '110161'//$request->get('uasg_compra')
        ];

        $retorno = json_decode($apiSiasg->executaConsulta('COMPRASISPP', $params));

        return $retorno;
    }

    public function montaParametrosCompra($compraSiasg,$params){

        $unidade_subrogada = $compraSiasg->data->compraSispp->subrogada;
        $params['unidade_origem_id'] = $this->buscaIdUnidade($params['unidade_origem_id']);
        $params['unidade_subrrogada_id'] = ($unidade_subrogada <> '000000') ? intval($this->buscaIdUnidade($unidade_subrogada)) : null;
        $params['modalidade_id'] = intval('05');
        $params['tipo_compra_id'] = $compraSiasg->data->compraSispp->tipoCompra;
        $params['inciso'] = $compraSiasg->data->compraSispp->inciso;
        $params['lei'] = $compraSiasg->data->compraSispp->lei;

        return $params;

    }

    public function montaParametroItensdaCompra($compraSiasg,$params)
    {
        $fornecedor = [];
        $unidade_autorizada_id = $this->retornaUnidadeAutorizada($compraSiasg,$params);

        if(!is_null($compraSiasg->data->itemCompraSisppDTO)){

            foreach($compraSiasg->data->itemCompraSisppDTO as $key => $item){

                $params['compra_id'] = $params['compra_id'];
                $params['tipo_item_id'] = ($item->tipo <> 'S') ? $this::SERVICO : $this::MATERIAL;
                $params['catmatseritem_id'] = intval($item->codigo);
                $params['fornecedor_id'] = $this->retornaIdFornecedor($item);
                $params['unidade_autorizada_id'] = $unidade_autorizada_id;
                $params['descricaodetalhada'] = $item->descricaoDetalhada;
                $params['quantidade'] = $item->quantidadeTotal;
                $params['valorunitario'] = $item->valorUnitario;
                $params['valortotal'] = $item->valorTotal;

                $this->gravarItensCompra($params);
            }
        }
    }

    public function gravarItensCompra($params)
    {
        $compraItem = new CompraItem();
        $compraItem->gravaCompraItem($params);
    }

    public function retornaIdFornecedor($item)
    {
        $fornecedor = new Fornecedor();
        $retorno = $fornecedor->buscaFornecedorPorNumero($item->niFornecedor);

        if(is_null($retorno)){
            $fornecedor->tipo_fornecedor = $fornecedor->retornaTipoFornecedor($item->niFornecedor);
            $fornecedor->cpf_cnpj_idgener = $fornecedor->formataCnpjCpf($item->niFornecedor);
            $fornecedor->nome = $item->nomeFornecedor;
            $fornecedor->save();
            return $fornecedor->id;
        }
        return $retorno->id;
    }

    public function buscaIdUnidade($uasg)
    {
        $unidade = Unidade::where('codigo',$uasg)->first();
        return $unidade->id;
    }


    public function retornaUnidadeAutorizada($compraSiasg,$params)
    {
        $unidade_autorizada_id = null;
        $tipoCompra = $compraSiasg->data->compraSispp->tipoCompra;
        $subrrogada = $compraSiasg->data->compraSispp->subrogada;
        if($tipoCompra == $this::SISPP){
            ($subrrogada <> '000000') ? $unidade_autorizada_id = intval($this->buscaIdUnidade($subrrogada)) : $unidade_autorizada_id = intval($this->buscaIdUnidade($params['unidade_autorizada_id']));
        }
        if($tipoCompra == $this::SISRP){
            //tratar unidade autorizada SISRP - Aguardando Serviço ficar pronto
        }

        return $unidade_autorizada_id;
    }

}
