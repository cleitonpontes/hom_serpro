<?php

namespace App\Http\Controllers\Api;

use App\Models\Saldohistoricoitem;
use Route;
use App\Http\Controllers\Controller;

class SaldoHistoricoItemController extends Controller
{

    /* Metodo para retonar os itens contrato item
     * utilizado para listar os items em: Termo aditivo e termo de apostilamento
     *
     * return array contratoitens
     */
    public function retonaSaldoHistoricoItens($id)
    {
        return Saldohistoricoitem::where('saldoable_id', $id)
            ->select(
                'saldohistoricoitens.id',
                'codigoitens.descricao',
                'catmatseritens.codigo_siasg',
                'catmatseritens.descricao as descricao_complementar',
                'saldohistoricoitens.quantidade',
                'saldohistoricoitens.valorunitario',
                'saldohistoricoitens.valortotal',
                'saldohistoricoitens.periodicidade',
                'saldohistoricoitens.data_inicio',
                'contratoitens.catmatseritem_id',
                'contratoitens.tipo_id as tipo_item_id',
                'contratoitens.numero_item_compra as numero'
            )
            ->leftJoin('contratoitens', 'saldohistoricoitens.contratoitem_id', '=', 'contratoitens.id')
            ->leftJoin('codigoitens', 'codigoitens.id', '=', 'contratoitens.tipo_id')
            ->leftJoin('catmatseritens', 'catmatseritens.id', '=', 'contratoitens.catmatseritem_id')
            ->whereNull('contratoitens.deleted_at')
            ->get()->toArray();
    }
}
