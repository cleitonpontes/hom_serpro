<?php

namespace App\Http\Traits;

use App\Models\Codigoitem;

trait BuscaCodigoItens
{
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

    public function retornaDescCodigoItem($id)
    {
        return Codigoitem::where('id', $id)
            ->select('descricao')->first()->descricao;
    }

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
}
