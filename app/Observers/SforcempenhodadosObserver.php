<?php

namespace App\Observers;

use App\Http\Controllers\Execfin\EmpenhoCrudController;
use App\Jobs\IncluirEmpenhoWSJob;
use App\Models\Codigoitem;
use App\Models\DevolveMinutaSiasg;
use App\Models\MinutaEmpenho;
use App\Models\MinutaEmpenhoRemessa;
use App\Models\SfOrcEmpenhoDados;

class SforcempenhodadosObserver
{
    public function created(SfOrcEmpenhoDados $sfOrcEmpenhoDados)
    {
        $remessa = MinutaEmpenhoRemessa::find($sfOrcEmpenhoDados->minutaempenhos_remessa_id);
        if ($sfOrcEmpenhoDados->situacao == 'EM PROCESSAMENTO' and $remessa->remessa == 0) {
            IncluirEmpenhoWSJob::dispatch($sfOrcEmpenhoDados)->onQueue('enviarempenhosiafi');
        }
    }

    public function updated(SfOrcEmpenhoDados $sfOrcEmpenhoDados)
    {
        $remessa = MinutaEmpenhoRemessa::find($sfOrcEmpenhoDados->minutaempenhos_remessa_id);
        if ($sfOrcEmpenhoDados->situacao == 'EMITIDO' or $sfOrcEmpenhoDados->situacao == 'ERRO') {
            $situacao = $this->buscaSituacao($sfOrcEmpenhoDados->situacao);
            $minutaempenho = MinutaEmpenho::find($sfOrcEmpenhoDados->minutaempenho_id);
            $minutaempenho->mensagem_siafi = $sfOrcEmpenhoDados->mensagemretorno;
            $minutaempenho->situacao_id = $situacao->id;
            $minutaempenho->save();

            if($sfOrcEmpenhoDados->situacao == 'EMITIDO'){
                $empenhoCrud = new EmpenhoCrudController();
                $empenhoCrud->criaEmpenhoFromMinuta($sfOrcEmpenhoDados);

                DevolveMinutaSiasg::create([
                    'minutaempenho_id' => $sfOrcEmpenhoDados->minutaempenho_id,
                    'situacao' => 'Pendente'
                ]);
            }

        }

        if ($sfOrcEmpenhoDados->situacao == 'EM PROCESSAMENTO' and $remessa->remessa == 0) {
            IncluirEmpenhoWSJob::dispatch($sfOrcEmpenhoDados)->onQueue('enviarempenhosiafi');
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
