<?php

namespace App\Http\Traits;

use App\Models\Codigoitem;

trait BuscaCodigoItens
{
    public function retornaIdCodigoItem($descCodigo, $descCodItem)
    {
        return Codigoitem::whereHas('codigo', function ($query) use ($descCodigo) {
            $query->where('descricao', '=', $descCodigo);
        })
            ->where('descricao', '=', $descCodItem)
            ->first()->id;
    }
}
