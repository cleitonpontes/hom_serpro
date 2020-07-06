<?php

namespace App\Observers;

use App\Models\Contratosfpadrao;
use App\XML\ChainOfResponsabilities\ProcessaXmlSiafi;
use App\XML\Execsiafi;

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
        $xml = new Execsiafi();
        $xmlSiafi = $xml->consultaDh(backpack_user(), session()->get('user_ug'), 'HOM', $contratosfpadrao->anodh,$contratosfpadrao);

        $processamento =  new ProcessaXmlSiafi();
        $resultado = $processamento->process($xmlSiafi,$contratosfpadrao);
        dd($resultado);
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
