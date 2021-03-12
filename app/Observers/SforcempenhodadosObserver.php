<?php

namespace App\Observers;

use App\Http\Controllers\Execfin\EmpenhoCrudController;
use App\Jobs\AlterarEmpenhoWSJob;
use App\Jobs\IncluirEmpenhoWSJob;
use App\Jobs\AtualizarSaldoEmpenhoJob;
use App\Models\Codigoitem;
use App\Models\DevolveMinutaSiasg;
use App\Models\Empenho;
use App\Models\MinutaEmpenho;
use App\Models\MinutaEmpenhoRemessa;
use App\Models\Naturezasubitem;
use App\Models\SfOrcEmpenhoDados;
use App\Repositories\Base;
use App\XML\Execsiafi;

class SforcempenhodadosObserver
{
    public function created(SfOrcEmpenhoDados $sfOrcEmpenhoDados)
    {
        if ($sfOrcEmpenhoDados->situacao == 'EM PROCESSAMENTO') {
            if ($sfOrcEmpenhoDados->alteracao == false) {
                IncluirEmpenhoWSJob::dispatch($sfOrcEmpenhoDados)->onQueue('enviarempenhosiafi');
            } else {
                AlterarEmpenhoWSJob::dispatch($sfOrcEmpenhoDados)->onQueue('enviarempenhosiafi');
            }
        }
    }

    public function updated(SfOrcEmpenhoDados $sfOrcEmpenhoDados)
    {

        if ($sfOrcEmpenhoDados->situacao == 'EMITIDO' or $sfOrcEmpenhoDados->situacao == 'ERRO') {
            $situacao = $this->buscaSituacao($sfOrcEmpenhoDados->situacao);

            if ($sfOrcEmpenhoDados->alteracao == false) {
                $minutaempenho = MinutaEmpenho::find($sfOrcEmpenhoDados->minutaempenho_id);
                $minutaempenho->mensagem_siafi = $sfOrcEmpenhoDados->mensagemretorno;
                $minutaempenho->situacao_id = $situacao->id;
                $minutaempenho->save();
                if ($sfOrcEmpenhoDados->situacao == 'ERRO') {
                    $remessa = MinutaEmpenhoRemessa::find($sfOrcEmpenhoDados->minutaempenhos_remessa_id);
                    $remessa->sfnonce = $this->geraNonceSequencial($sfOrcEmpenhoDados);
                    $remessa->save();
                }

            } else {
                $remessa = MinutaEmpenhoRemessa::find($sfOrcEmpenhoDados->minutaempenhos_remessa_id);
                $remessa->mensagem_siafi = $sfOrcEmpenhoDados->mensagemretorno;
                if ($sfOrcEmpenhoDados->situacao == 'ERRO') {
                    $remessa->sfnonce = $this->geraNonceSequencial($sfOrcEmpenhoDados);
                }
                $remessa->situacao_id = $situacao->id;
                $remessa->save();
            }

            if ($sfOrcEmpenhoDados->situacao == 'EMITIDO') {
                $empenhoCrud = new EmpenhoCrudController();
                $objEmpenho = $empenhoCrud->criaEmpenhoFromMinuta($sfOrcEmpenhoDados);

                /*Atualiza o saldo do empenho*/
                if ($objEmpenho) {
                    foreach ($objEmpenho->empenhodetalhado as $empDetalhado) {
                        $subitem = $empDetalhado->naturezasubitem->codigo;
                        $ug = $objEmpenho->unidade->codigo;
                        $empenho = $objEmpenho->numero;
                        AtualizarSaldoEmpenhoJob::dispatch($ug, $empenho, $subitem, $objEmpenho->unidade_id)->onQueue('atualizasaldone');
                    }
                }

                DevolveMinutaSiasg::create([
                    'minutaempenho_id' => $sfOrcEmpenhoDados->minutaempenho_id,
                    'situacao' => 'Pendente',
                    'alteracao' => $sfOrcEmpenhoDados->alteracao,
                    'minutaempenhos_remessa_id' => $sfOrcEmpenhoDados->minutaempenhos_remessa_id
                ]);
            }
        }

        if ($sfOrcEmpenhoDados->situacao == 'EM PROCESSAMENTO') {
            if ($sfOrcEmpenhoDados->alteracao == false) {
                IncluirEmpenhoWSJob::dispatch($sfOrcEmpenhoDados)->onQueue('enviarempenhosiafi');
            } else {
                AlterarEmpenhoWSJob::dispatch($sfOrcEmpenhoDados)->onQueue('enviarempenhosiafi');
            }
        }
    }

    private function geraNonceSequencial($sforcempenhodados)
    {
        if (!$sforcempenhodados->remessa->sfnonce) {
            $base = new Base();
            $nonce = $base->geraNonceSiafiEmpenho($sforcempenhodados->remessa->minutaempenho_id,$sforcempenhodados->remessa->id);
            return $nonce;
        }

        $array = explode('_', $sforcempenhodados->remessa->sfnonce);

        if (isset($array[3])) {
            $array[3] = $array[3]+1;
        }else{
            $array[3] = '1';
        }

        return implode('_',$array);
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
