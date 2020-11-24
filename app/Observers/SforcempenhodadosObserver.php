<?php

namespace App\Observers;

use App\Models\Codigoitem;
use App\Models\MinutaEmpenho;
use App\Models\SfOrcEmpenhoDados;

class SforcempenhodadosObserver
{
    public function created(SfOrcEmpenhoDados $sfOrcEmpenhoDados)
    {
        //
    }

    public function updated(SfOrcEmpenhoDados $sfOrcEmpenhoDados)
    {
        if ($sfOrcEmpenhoDados->situacao == 'EMITIDO' or $sfOrcEmpenhoDados->situacao == 'ERRO') {
            $situacao = $this->buscaSituacao($sfOrcEmpenhoDados->situacao);
            $minutaempenho = MinutaEmpenho::find($sfOrcEmpenhoDados->minutaempenho_id);
            $minutaempenho->mensagem_siafi = $sfOrcEmpenhoDados->mensagemretorno;
            $minutaempenho->situacao_id = $situacao->id;
            $minutaempenho->save();
        }
    }

    private function buscaSituacao(string $situacao)
    {
        $codigoitens = Codigoitem::whereHas('codigo', function ($q) {
            $q->where('descricao', 'Situações Minuta Empenho');
        })
            ->where('descres', $situacao)
            ->first();
        return $codigoitens;
    }

}
