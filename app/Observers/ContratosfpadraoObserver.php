<?php

namespace App\Observers;

use App\Models\Contratosfpadrao;
use App\XML\ChainOfResponsabilities\ProcessaXmlSiafi;
use App\XML\Execsiafi;
use App\XML\PadroesExecSiafi;
use Illuminate\Support\Facades\DB;

class ContratosfpadraoObserver
{
    /**
     * Handle the models contratosfpadrao "created" event.
     *
     * @param  \App\Models\Contratosfpadrao  $contratosfpadrao
     * @return void
     */
    public function created(Contratosfpadrao $contratosfpadrao)
    {
        $params['dtemis'] = date("Y-m-d H:i:s");
        $xml = new Execsiafi();
        $xmlSiafi = $xml->consultaDh(backpack_user(), session()->get('user_ug'), 'HOM', $contratosfpadrao->anodh,$contratosfpadrao);

        $xml = simplexml_load_string(str_replace(':', '', $xmlSiafi));
        $json = json_encode($xml);
        $array = json_decode($json);

        $retornoSIAFI = $array->soapHeader->ns2EfetivacaoOperacao->resultado;

        if($retornoSIAFI == 'SUCESSO'){
            $padraoExecSiafi =  new PadroesExecSiafi();
            $body = $array->soapBody->ns3cprDHDetalharDHResponse->cprDhDetalharResposta->documentoHabil;
            $resultado = $padraoExecSiafi->processamento($body,$contratosfpadrao);
            $params['situacao'] = 'I';
            $params['msgretorno'] = 'Importado com Sucesso!';
            if(!$resultado){
                $params['situacao'] = 'E';
                $params['msgretorno'] = 'Erro ao tentar importar!';
            }
        }else{
            $msgErro = $array->soapBody->soapFault->faultstring;
            $params['situacao'] = 'E';
            $params['msgretorno'] = $msgErro;
        };


        DB::beginTransaction();
        try {
            $contratosfpadrao->update($params);
            DB::commit();
//           dd('importado com sucesso');
        } catch (\Exception $exc) {
            DB::rollback();
        }
    }

    /**
     * Handle the models contratosfpadrao "updated" event.
     *
     * @param  \App\Models\Contratosfpadrao  $contratosfpadrao
     * @return void
     */
    public function updated(Contratosfpadrao $contratosfpadrao)
    {
        //
    }


    /**
     * Handle the models contratosfpadrao "deleted" event.
     *
     * @param  \App\Models\Contratosfpadrao  $contratosfpadrao
     * @return void
     */
    public function deleted(Contratosfpadrao $contratosfpadrao)
    {
        //
    }

    /**
     * Handle the models contratosfpadrao "restored" event.
     *
     * @param  \App\Models\Contratosfpadrao  $contratosfpadrao
     * @return void
     */
    public function restored(Contratosfpadrao $contratosfpadrao)
    {
        //
    }

    /**
     * Handle the models contratosfpadrao "force deleted" event.
     *
     * @param  \App\Models\Contratosfpadrao  $contratosfpadrao
     * @return void
     */
    public function forceDeleted(Contratosfpadrao $contratosfpadrao)
    {
        //
    }

}
