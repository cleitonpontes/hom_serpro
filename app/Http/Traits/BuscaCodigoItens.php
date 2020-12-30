<?php

namespace App\Http\Traits;

use App\Models\Codigoitem;

trait BuscaCodigoItens
{
    public function retornaIdCodigoItem($descCodigo, $descCodItem)
    {
        return Codigoitem::whereHas('codigo', function ($query) use ($descCodigo) {
            $query->where('descricao', '=', $descCodigo)
                ->whereNull('deleted_at');
        })
            ->whereNull('deleted_at')
            ->where('descricao', '=', $descCodItem)
            ->first()->id;
    }

    public function retornaArrayCodigosItens($descCodigo)
    {
        return Codigoitem::whereHas('codigo', function ($query) use ($descCodigo) {
            $query->where('descricao', '=', $descCodigo)
                ->whereNull('deleted_at');
        })
            ->whereNull('deleted_at')
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();
    }
}
