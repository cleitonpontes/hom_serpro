<?php

namespace App\Http\Traits;

use App\Models\CompraItemMinutaEmpenho;
use Illuminate\Support\Facades\DB;

trait CompraTrait
{
    public function retornaSaldoAtualizado($compraitem_id)
    {
        return CompraItemMinutaEmpenho::select(
            DB::raw('compra_item_unidade.quantidade_autorizada - sum(compra_item_minuta_empenho.quantidade) as saldo')
        )
            ->join(
                'compra_item_unidade',
                'compra_item_unidade.compra_item_id',
                '=',
                'compra_item_minuta_empenho.compra_item_id'
            )
            ->where('compra_item_minuta_empenho.compra_item_id', $compraitem_id)
            ->groupBy('compra_item_unidade.quantidade_autorizada')
            ->first();
    }
}
