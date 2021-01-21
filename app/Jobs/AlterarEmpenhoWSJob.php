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

class AlterarEmpenhoWSJob implements ShouldQueue
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

        $retorno = $ws_siafi->alterarNe($user, $this->sforcempenhodados->ugemitente, config('app.ambiente_siafi'), $ano, $this->sforcempenhodados);

        $this->sforcempenhodados->update($retorno);

    }
}
