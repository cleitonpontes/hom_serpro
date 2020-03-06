<?php

namespace App\Observers;

use App\Models\Contratohistorico;
use App\Models\Contratoitem;
use App\Models\Saldohistoricoitem;

class SaldohistoricoitemObserver
{
    public function __construct(Contratoitem $contratoitem)
    {
        $this->contratoitem = $contratoitem;
    }

    public function created(Saldohistoricoitem $saldohistoricoitem)
    {
        $this->contratoitem->atualizaSaldoContratoItem($saldohistoricoitem);
    }


    public function updated(Saldohistoricoitem $saldohistoricoitem)
    {
        $this->contratoitem->atualizaSaldoContratoItem($saldohistoricoitem);
    }


    public function deleted(Saldohistoricoitem $saldohistoricoitem)
    {
        $this->contratoitem->atualizaSaldoContratoItem($saldohistoricoitem);
    }



}
