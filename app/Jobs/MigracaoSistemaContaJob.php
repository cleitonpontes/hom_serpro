<?php

namespace App\Jobs;

use App\Http\Controllers\AdminController;
use App\Models\Contrato;
use App\Models\MigracaoComprasnetContratos;
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
    protected $tipo_migracao;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $url, string $tipo_migracao)
    {
        $this->url = $url;
        $this->tipo_migracao = $tipo_migracao;
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
            if($this->tipo_migracao == 'CCONTRATOS'){
                $contrato = new MigracaoComprasnetContratos();
                $retorno = $contrato->trataDadosMigracaoConta($dado);
            }else{
                $contrato = new MigracaoSistemaConta();
                $retorno = $contrato->trataDadosMigracaoConta($dado);
            }
        }

        // caso seja um teste local, ao processar o último contrato, vou parar aqui antes de voltar ao sistema.
        if($this->url == 'http://localhost:8000/api/v1/contrato/486?token=base64:HMFwu3NCR6OZUipnpjNqiqvRK2w9afTl4Bjfb5Xik0M='){
            exit('FIM - linha 52 MigracaoSistemaContaJob.php');
        }



    }
}
