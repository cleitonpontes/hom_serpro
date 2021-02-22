<?php

namespace App\Jobs;

use App\Models\Codigoitem;
use App\Models\Contratoempenho;
use App\Models\Empenho;
use App\Models\MinutaEmpenho;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ContratoEmpenhoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $minutasEmpenhos = MinutaEmpenho::whereHas('situacao', function ($q) {
            $q->whereHas('codigo', function ($query) {
                $query->where('descricao', 'Situações Minuta Empenho');
            })
                ->where('descricao', 'EMPENHO EMITIDO');
        })->whereNotNull('contrato_id')->get();

        foreach ($minutasEmpenhos as $minutasEmpenho) {

            $ugemitente = $minutasEmpenho->saldo_contabil->unidade_id;
            $numempenho = $minutasEmpenho->mensagem_siafi;
            $empenho = $this->buscaEmpenho($ugemitente, trim($numempenho));

            if ($empenho) {

                $arrMinutasEmpenhos['contrato_id'] = $minutasEmpenho->contrato_id;
                $arrMinutasEmpenhos['empenho_id'] = $empenho->id;

                if (empty($this->verificaSeJaExiste($arrMinutasEmpenhos))) {
                    $contratoEmpenho = new Contratoempenho();
                    $contratoEmpenho->contrato_id = $minutasEmpenho->contrato_id;
                    $contratoEmpenho->fornecedor_id = $empenho->fornecedor_id;
                    $contratoEmpenho->empenho_id = $empenho->id;
                    $contratoEmpenho->save();
                }

            }
        }
    }

    private function buscaEmpenho(int $unidade_id, string $num_empenho)
    {
        return Empenho::where('unidade_id', $unidade_id)
            ->where('numero', $num_empenho)->first();
    }

    private function verificaSeJaExiste($minutaEmpenho)
    {
        return Contratoempenho::where('empenho_id', $minutaEmpenho['empenho_id'])
            ->where('contrato_id', $minutaEmpenho['contrato_id'])
            ->get()->toArray();
    }
}
