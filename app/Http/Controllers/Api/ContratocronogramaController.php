<?php

namespace App\Http\Controllers\Api;

use App\Models\Contratocronograma;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContratocronogramaController extends Controller
{
    public function cronogramaPorUg(int $ug)
    {
        $cronogramas = new Contratocronograma();
        $cronogramas = $cronogramas->buscaCronogramasPorUg($ug);

        return json_encode($cronogramas);

    }
}
