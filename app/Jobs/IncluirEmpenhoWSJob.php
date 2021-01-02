<?php

namespace App\Jobs;

use App\Models\BackpackUser;
use App\Models\SfOrcEmpenhoDados;
use App\XML\Execsiafi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;

class IncluirEmpenhoWSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sforcempenhodados;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SfOrcEmpenhoDados $sfOrcEmpenhoDados)
    {
        $this->sforcempenhodados = $sfOrcEmpenhoDados;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = BackpackUser::where('cpf', $this->sforcempenhodados->cpf_user)
            ->first();
        $ws_siafi = new Execsiafi;
        $ano = config('app.ano_minuta_empenho');

        try {
            dump('entrei try');
            $retorno = $ws_siafi->incluirNe($user, $this->sforcempenhodados->ugemitente, env('AMBIENTE_SIAFI'), $ano, $this->sforcempenhodados);
            dump($retorno);
        } catch (Exception $e) {
            dump('entrei catch');
            $retorno['mensagemretorno'] = (string)  $e;
            $retorno['situacao'] = 'ERRO';
            dump('retorno catch'.$retorno);
        }
        dd('fim');

        $this->sforcempenhodados->update($retorno);
    }
}
