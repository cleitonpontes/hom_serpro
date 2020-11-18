<?php

namespace App\Http\Traits;

use App\Models\CompraItemMinutaEmpenho;
use Illuminate\Support\Facades\DB;

trait CompraTrait
{
    public function retornaSaldoAtualizado($compraitem_id)
    {
//        $teste = CompraItemMinutaEmpenho::select(
        return CompraItemMinutaEmpenho::select(
            DB::raw('CASE
                           WHEN compra_item_unidade.quantidade_autorizada - sum(compra_item_minuta_empenho.quantidade) IS NOT NULL
                               THEN compra_item_unidade.quantidade_autorizada - sum(compra_item_minuta_empenho.quantidade)
                           ELSE compra_item_unidade.quantidade_autorizada
                           END AS saldo')
        )
            ->join(
                'compra_items',
                'compra_items.id',
                '=',
                'compra_item_minuta_empenho.compra_item_id'
            )
            ->rightJoin(
                'compra_item_unidade',
                'compra_item_unidade.compra_item_id',
                '=',
                'compra_items.id'
            )
            ->where('compra_item_unidade.compra_item_id', $compraitem_id)
            ->groupBy('compra_item_unidade.quantidade_autorizada')
            ->first();
//        ;dd($teste->getBindings(),$teste->toSql());
    }
}
