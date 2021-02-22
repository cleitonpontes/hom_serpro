<?php

namespace App\Jobs;

use App\Models\Codigoitem;
use App\Models\Contratoempenho;
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
        $situacaoEmpenhoEmitido = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Situações Minuta Empenho');
        })
            ->where('descricao', 'EMPENHO EMITIDO')
            ->select('codigoitens.id')->first();

        $arrMinutasEmpenhos = MinutaEmpenho::whereNotNull('contrato_id')->where('situacao_id',$situacaoEmpenhoEmitido->id)->get()->toArray();

        foreach ($arrMinutasEmpenhos as $minutaEmpenho) {
            if(empty($this->verificaSeJaExiste($minutaEmpenho))){
                $contratoEmpenho = new Contratoempenho();
                $contratoEmpenho->contrato_id = $minutaEmpenho['contrato_id'];
                $contratoEmpenho->fornecedor_id = $minutaEmpenho['fornecedor_empenho_id'];
                $contratoEmpenho->empenho_id = $minutaEmpenho['id'];
                $contratoEmpenho->save();
            }
        }
    }

    private function verificaSeJaExiste($minutaEmpenho)
    {
        return Contratoempenho::where('empenho_id', $minutaEmpenho['id'])
            ->where('contrato_id', $minutaEmpenho['contrato_id'])
            ->get()->toArray();
    }
}
