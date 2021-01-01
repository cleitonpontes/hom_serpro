<?php

namespace App\Observers;

use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Contratohistorico;
use App\Models\ContratoPublicacoes;

class ContratopublicacaoObserver
{
    public function created(ContratoPublicacoes $publicacao)
    {
//        dd($publicacao, $publicacao->contratohistorico);

        //PublicaPreviewOficioJob::dispatch($publicacao)->onQueue('envia_preview_oficio');
    }
}
