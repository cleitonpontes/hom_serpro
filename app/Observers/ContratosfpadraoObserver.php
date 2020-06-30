<?php

namespace App\Observers;

use App\Models\Contratosfpadrao;
use App\Models\SfDadosBasicos;
use App\XML\ChainOfResponsabilities\DadosBasicos;
use App\XML\Execsiafi;
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
        $xml = new Execsiafi();
        $retorno = $xml->consultaDh(backpack_user(), session()->get('user_ug'), 'HOM', $contratosfpadrao->anodh,$contratosfpadrao);


        //processa dados básicos
        $arrayXml = ['sfpadrao_id' => $contratosfpadrao->id];
        $processamento = new DadosBasicos();
        $modSfDadosBasicos = $processamento->manipulador($retorno,$arrayXml);

        dd($modSfDadosBasicos->id);




        DB::beginTransaction();
        try {
            DB::commit();
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
