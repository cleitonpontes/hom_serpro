<?php

namespace App\Jobs;

use App\Http\Controllers\Execfin\EmpenhoCrudController;
use App\Http\Traits\Busca;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MigracaoCargaEmpenhoJob implements ShouldQueue
{
    use Busca, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200;

    protected $ug_id;
    protected $ano;

    public function __construct(string $ug_id, $ano)
    {
        $this->ug_id = $ug_id;
        $this->ano = $ano;
    }

    public function handle()
    {
        $migracao_empenho = new EmpenhoCrudController;
        $migracao_empenho->migracaoEmpenho($this->ug_id, $this->ano);
    }
}
