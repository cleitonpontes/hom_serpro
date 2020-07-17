<?php

namespace App\Jobs;

use App\Models\Contratosfpadrao;
use App\XML\ApiSiasg;
use App\XML\PadroesExecSiafi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AtualizaSfPadraoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sfpadrao;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Contratosfpadrao $sfpadrao)
    {
        $this->sfpadrao = $sfpadrao;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $padraoExecSiafi = new PadroesExecSiafi();

        $xmlSiafi = $padraoExecSiafi->retornaXmlSiafi($this->sfpadrao);

        $params = $padraoExecSiafi->importaDadosSiafi($xmlSiafi,$this->sfpadrao);

        $this->sfpadrao->atualizaMensagemSituacao($this->sfpadrao->id,$params);

    }

}
