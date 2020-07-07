<?php

namespace App\Observers;

use App\Models\Siasgcompra;
use App\XML\ApiSiasg;

class SiasgcompraObserver
{
    /**
     * Handle the siasgcompra "created" event.
     *
     * @param \App\Models\Siasgcompra $siasgcompra
     * @return void
     */
    public function created(Siasgcompra $siasgcompra)
    {
        $this->teste($siasgcompra);
    }

    /**
     * Handle the siasgcompra "updated" event.
     *
     * @param \App\Models\Siasgcompra $siasgcompra
     * @return void
     */
    public function updated(Siasgcompra $siasgcompra)
    {
        $this->teste($siasgcompra);
    }

    /**
     * Handle the siasgcompra "deleted" event.
     *
     * @param \App\Models\Siasgcompra $siasgcompra
     * @return void
     */
    public function deleted(Siasgcompra $siasgcompra)
    {
        //
    }

    /**
     * Handle the siasgcompra "restored" event.
     *
     * @param \App\Models\Siasgcompra $siasgcompra
     * @return void
     */
    public function restored(Siasgcompra $siasgcompra)
    {
        //
    }

    /**
     * Handle the siasgcompra "force deleted" event.
     *
     * @param \App\Models\Siasgcompra $siasgcompra
     * @return void
     */
    public function forceDeleted(Siasgcompra $siasgcompra)
    {
        //
    }

    private function teste(Siasgcompra $siasgcompra)
    {
        $tipoconsulta = 'Compra';

        $apiSiasg = new ApiSiasg;
        $dado = [
            'ano' => $siasgcompra->ano,
            'modalidade' => $siasgcompra->modalidade->descres,
            'numero' => $siasgcompra->numero,
            'uasg' => $siasgcompra->unidade->codigosiasg
        ];

        $retorno = $apiSiasg->executaConsulta($tipoconsulta,$dado);
        $json_var = json_decode($retorno);

        $siasgcompra->json = $retorno;
        $siasgcompra->mensagem = $json_var->messagem;
        $siasgcompra->save();

        return true;

    }

}
