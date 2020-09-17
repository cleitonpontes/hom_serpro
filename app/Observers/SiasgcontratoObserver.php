<?php

namespace App\Observers;

use App\Jobs\AtualizaSiasgContratoJob;
use App\Models\ContratoSiasgIntegracao;
use App\Models\Siasgcompra;
use App\Models\Siasgcontrato;
use App\XML\ApiSiasg;

class SiasgcontratoObserver
{

    public function created(\App\Models\Siasgcontrato $siasgcontrato)
    {

    }

    public function updated(Siasgcontrato $siasgcontrato)
    {

    }

    public function deleted(Siasgcontrato $siasgcontrato)
    {
        //
    }

}
