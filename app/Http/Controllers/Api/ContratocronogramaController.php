<?php

namespace App\Http\Controllers\Api;

use App\Models\Contratocronograma;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContratocronogramaController extends Controller
{
    public function cronogramaPorUg(int $ug)
    {
        $cronograma_array = [];
        $cronogramas = new Contratocronograma();
        $cronogramas = $cronogramas->buscaCronogramasPorUg($ug);

        foreach ($cronogramas as $cronograma) {
            $cronograma_array[] = [
                'unidade' => $cronograma['unidade'],
                'mesref' => $cronograma['mesref'],
                'valor' => number_format($cronograma['valor'], 2, ',', '.'),
            ];
        }

        return json_encode($cronograma_array);

    }
}
