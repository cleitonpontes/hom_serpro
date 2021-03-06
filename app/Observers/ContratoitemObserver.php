<?php

namespace App\Observers;

use App\Models\Codigoitem;
use App\Models\Contratohistorico;
use App\Models\Contratoitem;
use App\Models\Saldohistoricoitem;

class ContratoitemObserver
{

    public function created(Contratoitem $contratoitem)
    {
        if (!isset($contratoitem->contratohistorico_id)) {
            $contratohistorico = Contratohistorico::whereHas('tipo', function ($query) {
                $query->where('descricao', '<>', 'Termo Aditivo')
                    ->where('descricao', '<>', 'Termo de Apostilamento');
            })
                ->where('contrato_id', $contratoitem->contrato_id)
                ->first();
        } else {
            $contratohistorico = Contratohistorico::find($contratoitem->contratohistorico_id);
        }

        $codigoitem = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Tipo Saldo Itens');
        })
            ->where('descricao', 'Saldo Inicial Contrato Historico')
            ->first();

        $saldohistoricoitem = $contratohistorico->saldosItens()->create([
            'contratoitem_id' => $contratoitem->id,
            'tiposaldo_id' => $codigoitem->id,
            'quantidade' => $contratoitem->quantidade,
            'valorunitario' => $contratoitem->valorunitario,
            'valortotal' => $contratoitem->valortotal,
            'periodicidade' => $contratoitem->periodicidade,
            'data_inicio' => $contratoitem->data_inicio,
            'numero_item_compra' => $contratoitem->numero_item_compra,
        ]);

    }

    /**
     * Handle the contratoitem "updated" event.
     *
     * @param \App\Contratoitem $contratoitem
     * @return void
     */
    public function updated(Contratoitem $contratoitem)
    {
    }

    /**
     * Handle the contratoitem "deleted" event.
     *
     * @param \App\Contratoitem $contratoitem
     * @return void
     */
    public function deleted(Contratoitem $contratoitem)
    {
        $contratohistorico = Contratohistorico::whereHas('tipo', function ($query) {
            $query->where('descricao', '<>', 'Termo Aditivo')
                ->where('descricao', '<>', 'Termo de Apostilamento');
        })
            ->where('contrato_id', $contratoitem->contrato_id)
            ->first();

        $codigoitem = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Tipo Saldo Itens');
        })
            ->where('descricao', 'Saldo Inicial Contrato Historico')
            ->first();

        $saldohistoricoitem = $contratohistorico->saldosItens()
            ->where('contratoitem_id', $contratoitem->id)
            ->delete();
    }

    /**
     * Handle the contratoitem "restored" event.
     *
     * @param \App\Contratoitem $contratoitem
     * @return void
     */
    public function restored(Contratoitem $contratoitem)
    {

    }

    /**
     * Handle the contratoitem "force deleted" event.
     *
     * @param \App\Contratoitem $contratoitem
     * @return void
     */
    public function forceDeleted(Contratoitem $contratoitem)
    {
        //
    }


}
