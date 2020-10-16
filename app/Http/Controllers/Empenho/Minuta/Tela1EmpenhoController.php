<?php

namespace App\Http\Controllers\Empenho\Minuta;

use App\Models\Compra;
use App\Models\Unidade;
use App\XML\ApiSiasg;
use App\Forms\NovoEmepenhoTela1Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Empenho\Minuta\BaseController;


class Tela1EmpenhoController extends BaseController
{

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
        $params['uasg_compra'] = $request->get('uasg_compra');
        $params['modalidade'] = $request->get('modalidade');
        $params['numero_ano'] = $request->get('numero_ano');

        $compra_id = $this->gravaCompra($retorno,$params);

        dd($compra_id);
    }

    public function consultaCompraSiasg(Request $request)
    {
        $numero_ano= explode('/',$request->get('numero_ano'));

        $apiSiasg = new ApiSiasg();

        $params = [
            'modalidade' => '05',//$request->get('modalidade'),
            'numeroAno' => $numero_ano[0].$numero_ano[1],
            'uasgCompra' => $request->get('uasg_compra')
        ];

        $retorno = json_decode($apiSiasg->executaConsulta('COMPRASISPP', $params));

        return $retorno;
    }

    public function gravaCompra($compraSiasg,$params){
        $unidade_subrogada = $compraSiasg->data->compraSispp->subrogada;

        $compra = new Compra();
        $compra->unidade_origem_id = $this->buscaIdUnidade($params['uasg_compra']);
        $compra->unidade_subrrogada_id = ($unidade_subrogada <> '000000') ? intval($this->buscaIdUnidade($unidade_subrogada)) : null;
        $compra->modalidade_id = '05';//$params['modalidade'];
        $compra->tipo_compra_id = $compraSiasg->data->compraSispp->tipoCompra;
        $compra->numero_ano = $params['numero_ano'];
        $compra->inciso = $compraSiasg->data->compraSispp->inciso;
        $compra->lei = $compraSiasg->data->compraSispp->lei;

        $id_compra = $compra->save();

        return $id_compra;

    }

    public function buscaIdUnidade($uasg)
    {
        $unidade = Unidade::where('codigo',$uasg)->first();
        return $unidade->id;
    }


}
