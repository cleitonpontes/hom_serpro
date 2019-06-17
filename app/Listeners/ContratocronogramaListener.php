<?php

namespace App\Listeners;

use App\Events\ContratocronogramaEvent;
use App\Models\Contratocronograma;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class ContratocronogramaListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Contratocronograma $contratocronograma)
    {
        $this->contratocronograma = $contratocronograma;
    }

    /**
     * Handle the event.
     *
     * @param  ContratocronogramaEvent $event
     * @return void
     */
    public function handle(ContratocronogramaEvent $event)
    {

        if ($event->contratohistorico->num_parcelas AND $event->contratohistorico->vigencia_inicio AND $event->contratohistorico->valor_parcela) {

            $data = date_create($event->contratohistorico->vigencia_inicio);
            $mesref = date_format($data, 'Y-m');
            $mesrefnew = $mesref . "-01";

            $t = $event->contratohistorico->num_parcelas;
            $dado = [];
            for ($i = 1; $i <= $t; $i++) {
                $vencimento = date('Y-m-d', strtotime("+" . $i . " month", strtotime($mesrefnew)));
                $ref = date('Y-m-d', strtotime("-1 month", strtotime($vencimento)));

                $dado[] = [
                        'contrato_id' => $event->contratohistorico->contrato_id,
                        'contratohistorico_id' => $event->contratohistorico->id,
                        'receita_despesa' => $event->contratohistorico->receita_despesa,
                        'mesref' => date('m',strtotime($ref)),
                        'anoref' => date('Y',strtotime($ref)),
                        'vencimento' => $vencimento,
                        'valor' => $event->contratohistorico->valor_parcela,
                    ];

            }

            $cronograma = $this->contratocronograma->where('contrato_id', '=' ,$event->contratohistorico->contrato_id)
                ->where('contratohistorico_id','=',$event->contratohistorico->id)
                ->get();

            if($cronograma){
                $cronograma->delete();
            }

            DB::table('contratocronograma')->insert($dado);

        }


    }
}
