<?php

namespace App\Jobs;

use App\Http\Controllers\AdminController;
use App\Models\Contrato;
use App\Models\MigracaoSistemaConta;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MigracaoSistemaContaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200;

    protected $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $base = new AdminController();
        $dados = $base->buscaDadosUrlMigracao($this->url);

        foreach ($dados as $dado){
            $contrato = new MigracaoSistemaConta();
            $retorno = $contrato->trataDadosMigracaoConta($dado);
        }
    }
}
