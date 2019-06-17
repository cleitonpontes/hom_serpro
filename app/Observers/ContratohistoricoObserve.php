<?php

namespace App\Observers;

use App\Models\Contratocronograma;
use App\Models\Contratohistorico;
use Illuminate\Support\Facades\DB;

class ContratohistoricoObserve
{

    public function __construct(Contratocronograma $contratocronograma)
    {
        $this->contratocronograma = $contratocronograma;
    }

    /**
     * Handle the contratohistorico "created" event.
     *
     * @param  \App\Contratohistorico  $contratohistorico
     * @return void
     */
    public function created(Contratohistorico $contratohistorico)
    {
        if ($contratohistorico->num_parcelas AND $contratohistorico->vigencia_inicio AND $contratohistorico->valor_parcela) {

            $data = date_create($contratohistorico->vigencia_inicio);
            $mesref = date_format($data, 'Y-m');
            $mesrefnew = $mesref . "-01";

            $t = $contratohistorico->num_parcelas;
            $dado = [];
            for ($i = 1; $i <= $t; $i++) {
                $vencimento = date('Y-m-d', strtotime("+" . $i . " month", strtotime($mesrefnew)));
                $ref = date('Y-m-d', strtotime("-1 month", strtotime($vencimento)));

                $dado[] = [
                    'contrato_id' => $contratohistorico->contrato_id,
                    'contratohistorico_id' => $contratohistorico->id,
                    'receita_despesa' => $contratohistorico->receita_despesa,
                    'mesref' => date('m',strtotime($ref)),
                    'anoref' => date('Y',strtotime($ref)),
                    'vencimento' => $vencimento,
                    'valor' => $contratohistorico->valor_parcela,
                ];

            }

            $cronograma = $this->contratocronograma->where('contrato_id', '=' ,$contratohistorico->contrato_id)
                ->where('contratohistorico_id','=',$contratohistorico->id)
                ->get();

            if($cronograma){
                foreach ($cronograma as $cron){
                    $cron->delete();
                }
            }

            DB::table('contratocronograma')->insert($dado);

        }
    }

    /**
     * Handle the contratohistorico "updated" event.
     *
     * @param  \App\Contratohistorico  $contratohistorico
     * @return void
     */
    public function updated(Contratohistorico $contratohistorico)
    {
        //
    }

    /**
     * Handle the contratohistorico "deleted" event.
     *
     * @param  \App\Contratohistorico  $contratohistorico
     * @return void
     */
    public function deleted(Contratohistorico $contratohistorico)
    {
        $contratohistorico->cronograma()->delete();
    }

    /**
     * Handle the contratohistorico "restored" event.
     *
     * @param  \App\Contratohistorico  $contratohistorico
     * @return void
     */
    public function restored(Contratohistorico $contratohistorico)
    {
        //
    }

    /**
     * Handle the contratohistorico "force deleted" event.
     *
     * @param  \App\Contratohistorico  $contratohistorico
     * @return void
     */
    public function forceDeleted(Contratohistorico $contratohistorico)
    {
        //
    }
}
