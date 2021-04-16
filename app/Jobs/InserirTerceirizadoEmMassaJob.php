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

    protected $array_dado;
    protected $contrato_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $array_dado, int $contrato_id)
    {
        $this->array_dado = $array_dado;
        $this->contrato_id = $contrato_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $executa = new ImportacaoCrudController();
        $executa->executaInsercaoMassaTerceirizado($this->array_dado, $this->contrato_id);
    }
}
