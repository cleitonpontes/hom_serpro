<?php

namespace App\Jobs;

use App\Http\Controllers\AdminController;
use App\Models\Naturezadespesa;
use App\Models\Naturezasubitem;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AtualizaNaturezaDespesasJob implements ShouldQueue
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
        $migracao_url = config('migracao.api_sta');
        $url = $migracao_url . '/api/estrutura/naturezadespesas';

        $base = new AdminController();
        $dados = $base->buscaDadosUrl($url);

        foreach ($dados as $dado) {
            $nd = new Naturezadespesa();
            $busca_nd = $nd->buscaNaturezaDespesa($dado);

            $subitem = new Naturezasubitem();
            $busca_si = $subitem->buscaNaturezaSubitem($dado, $busca_nd);

        }
    }
}
