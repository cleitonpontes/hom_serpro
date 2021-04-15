<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\ImportacaoCrudController;
use App\Models\Importacao;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InserirTerceirizadoEmMassaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $params_importacao;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $params_importacao)
    {
        $this->params_importacao = $params_importacao;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $executa = new ImportacaoCrudController();
        $executa->executaInsercaoMassaTerceirizado($this->params_importacao);
    }
}
