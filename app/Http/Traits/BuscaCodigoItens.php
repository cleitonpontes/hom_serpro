<?php

namespace App\Http\Traits;

use App\Models\Catmatsergrupo;
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

    public function retornaIdCodigoItemPorDescres($descres, $descricao)
    {
        return Codigoitem::whereHas('codigo', function ($query) use ($descricao) {
            $query->where('descricao', '=', $descricao)
                ->whereNull('deleted_at');
        })
            ->whereNull('deleted_at')
            ->where('descres', '=', $descres)
            ->first()->id;
    }

    public function retornaDescresCodigoItem($descCodigo, $descCodItem)
    {
        return Codigoitem::whereHas('codigo', function ($query) use ($descCodigo) {
            $query->where('descricao', '=', $descCodigo)
                ->whereNull('deleted_at');
        })
            ->whereNull('deleted_at')
            ->where('descricao', '=', $descCodItem)
            ->first()->descres;
    }

    public function retornaIdCatMatSerGrupo($descCodigo)
    {
        return Catmatsergrupo::whereNull('deleted_at')
            ->where('descricao', '=', $descCodigo)
            ->first()->id;
    }

}
