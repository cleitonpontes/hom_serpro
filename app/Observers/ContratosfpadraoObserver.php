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

    }


    /**
     * Handle the models contratosfpadrao "updating" event.
     *
     * @param  \App\Models\Contratosfpadrao  $contratosfpadrao
     * @return void
     */
    public function updated(Contratosfpadrao $contratosfpadrao)
    {
        $params = $contratosfpadrao->toArray();
        unset($params['id']);

        if($params['situacao'] == 'P') {

            DB::beginTransaction();
            try {
                $contratosfpadrao->delete();
                $novoContratoSfPadrao = new Contratosfpadrao($params);
                $novoContratoSfPadrao->save();
                DB::commit();
            } catch (\Exception $exc) {
                DB::rollback();
                dd($exc->getMessage());
            }
        }
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
