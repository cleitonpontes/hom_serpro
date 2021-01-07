<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Publicacao\DiarioOficialClass;
use App\Http\Traits\BuscaCodigoItens;
use App\Models\Contratohistorico;
use App\Models\ContratoPublicacoes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExecutaDouController extends Controller
{
    use BuscaCodigoItens;

    public function executaRotinaEnviaDou($datapub)
    {
        $diarioOficial = new DiarioOficialClass();

        $data = Carbon::createFromFormat('Y-m-d',$datapub);
        dump($data);
        $status_publicacao_id = $this->retornaIdCodigoItem('Situacao Publicacao', 'A PUBLICAR');


        $arr_contrato_publicacao = ContratoPublicacoes::where('status', 'Pendente')
            ->where('status_publicacao_id', $status_publicacao_id)
            ->whereNotNull('texto_dou')
            ->where('texto_dou','!=','')
            ->get();
        dump($arr_contrato_publicacao->count());

        foreach ($arr_contrato_publicacao as $contrato_publicacao) {

            $contrato_publicacao->data_publicacao = $data->toDateString();
            $contrato_publicacao->save();
            dump($contrato_publicacao->data_publicacao);
            $contrato_historico = Contratohistorico::where('id', $contrato_publicacao->contratohistorico_id)->first();
            dump('ContratoHistorico', $contrato_historico->id);
            $retorno = $diarioOficial->enviarPublicacaoCommand($contrato_historico, $contrato_publicacao);
            dd($retorno);
        }
        dd('Terminou!!');
    }
}
