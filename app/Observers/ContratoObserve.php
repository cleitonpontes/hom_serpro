<?php

namespace App\Observers;

use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Contratohistorico;

class ContratoObserve
{


    public function created(Contrato $contrato)
    {
        $con = $contrato;
        $contrato_array = $contrato->toArray();
        unset($contrato_array['id']);

        Contratohistorico::create($contrato_array + [
                'contrato_id' => $contrato->id,
                'observacao' => 'CELEBRAÇÃO DO CONTRATO: ' . $con->numero . ' DE ACORDO COM PROCESSO NÚMERO: ' . $con->processo,
            ]);

    }

    public function updated(Contrato $contrato)
    {
        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '<>', 'Termo Aditivo')
            ->where('descricao', '<>', 'Termo de Apostilamento')
            ->orderBy('descricao')
            ->pluck('id')
            ->toArray();

        $contrato_array = $contrato->toArray();

        unset($contrato_array['id']);
        unset($contrato_array['unidade_id']);
        unset($contrato_array['total_despesas_acessorias']);
        unset($contrato_array['numero']);
        unset($contrato_array['tipo_id']);

        Contratohistorico::where('unidade_id', $contrato->unidade_id)
            ->where('contrato_id',)
            ->where('numero', $contrato->numero)
            ->whereIn('tipo_id', $tipos)
            ->update($contrato_array);

    }

    public function deleted(Contrato $contrato)
    {
        $contrato->historico()->delete();
        $contrato->cronograma()->delete();
        $contrato->responsaveis()->delete();
        $contrato->garantias()->delete();
        $contrato->arquivos()->delete();
        $contrato->empenhos()->delete();
        $contrato->faturas()->delete();
        $contrato->ocorrencias()->delete();
        $contrato->terceirizados()->delete();
    }


}
