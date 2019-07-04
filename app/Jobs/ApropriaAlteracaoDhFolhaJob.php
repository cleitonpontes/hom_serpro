<?php

namespace App\Jobs;

use App\Models\BackpackUser;
use App\Models\SfPadrao;
use App\XML\Execsiafi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ApropriaAlteracaoDhFolhaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $user_ug;
    protected $sfpadrao;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BackpackUser $user,string $user_ug,SfPadrao $sfpadrao)
    {
        $this->user = $user;
        $this->user_ug = $user_ug;
        $this->sfpadrao = $sfpadrao;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $xml = new Execsiafi();
        $retorno = $xml->apropriaAlteracaoDh($this->user, $this->user_ug, 'PROD', $this->sfpadrao->anodh, $this->sfpadrao);

        if ($retorno->resultado[0] == 'SUCESSO') {
            $this->sfpadrao->msgretorno = $retorno->resultado[2];
            $this->sfpadrao->situacao = 'E';
            $this->sfpadrao->save();
        }

        if ($retorno->resultado[0] == 'FALHA') {
            $this->sfpadrao->msgretorno = $retorno->resultado[1];
            $this->sfpadrao->situacao = 'E';
            $this->sfpadrao->save();
        }

    }
}
