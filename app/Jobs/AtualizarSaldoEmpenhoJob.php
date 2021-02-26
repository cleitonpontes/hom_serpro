<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Traits\ConsultaAtualizaSaldoSiafi;

class AtualizarSaldoEmpenhoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ConsultaAtualizaSaldoSiafi;

    protected $ug;
    protected $empenho;
    protected $subitem;
    protected $id_ug;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $ug, string $empenho, string $subitem, int $id_ug)
    {
        $this->ug = $ug;
        $this->empenho = $empenho;
        $this->subitem = $subitem;
        $this->id_ug = $id_ug;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->consultaAtualizaSaldoSiafi($this->ug, $this->empenho, $this->subitem, $this->id_ug);
    }
}
