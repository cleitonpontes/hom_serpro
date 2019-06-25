<?php

namespace App\Observers;

use App\Models\Codigoitem;
use App\Models\Contrato;
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
     * @param  \App\Models\Contratohistorico $contratohistorico
     * @return void
     */
    public function created(Contratohistorico $contratohistorico)
    {
        $historico = Contratohistorico::where('contrato_id', '=', $contratohistorico->contrato_id)
            ->orderBy('data_assinatura')
            ->get();

        $this->contratocronograma->inserirCronogramaFromHistorico($contratohistorico);

        $this->atualizaContrato($historico);


    }

    /**
     * Handle the contratohistorico "updated" event.
     *
     * @param  \App\Models\Contratohistorico $contratohistorico
     * @return void
     */
    public function updated(Contratohistorico $contratohistorico)
    {
        $historico = Contratohistorico::where('contrato_id', '=', $contratohistorico->contrato_id)
            ->orderBy('data_assinatura')
            ->get();

        $this->contratocronograma->atualizaCronogramaFromHistorico($contratohistorico);

        $this->atualizaContrato($historico);

    }

    /**
     * Handle the contratohistorico "deleted" event.
     *
     * @param  \App\Models\Contratohistorico $contratohistorico
     * @return void
     */
    public function deleted(Contratohistorico $contratohistorico)
    {
        $contratohistorico->cronograma()->delete();
    }

    /**
     * Handle the contratohistorico "restored" event.
     *
     * @param  \App\Models\Contratohistorico $contratohistorico
     * @return void
     */
    public function restored(Contratohistorico $contratohistorico)
    {
        //
    }

    /**
     * Handle the contratohistorico "force deleted" event.
     *
     * @param  \App\Models\Contratohistorico $contratohistorico
     * @return void
     */
    public function forceDeleted(Contratohistorico $contratohistorico)
    {
        //
    }

    private function atualizaContrato($contratohistorico)
    {
        foreach ($contratohistorico as $h) {

            $contrato_id = $h->contrato_id;
            $arrayhistorico = $h->toArray();

            $tipo = Codigoitem::find($arrayhistorico['tipo_id']);

            if ($tipo->descricao == 'Termo Aditivo' or $tipo->descricao == 'Termo de Apostilamento') {
                unset($arrayhistorico['numero']);
                unset($arrayhistorico['receita_despesa']);
                unset($arrayhistorico['tipo_id']);
                unset($arrayhistorico['categoria_id']);
                unset($arrayhistorico['processo']);
                unset($arrayhistorico['modalidade_id']);
                unset($arrayhistorico['licitacao_numero']);
                unset($arrayhistorico['data_assinatura']);
                unset($arrayhistorico['data_publicacao']);
                unset($arrayhistorico['valor_inicial']);
                unset($arrayhistorico['novo_valor_global']);
                unset($arrayhistorico['novo_num_parcelas']);
                unset($arrayhistorico['novo_valor_parcela']);
                unset($arrayhistorico['data_inicio_novo_valor']);

            }
            unset($arrayhistorico['id']);
            unset($arrayhistorico['contrato_id']);
            unset($arrayhistorico['observacao']);
            unset($arrayhistorico['created_at']);
            unset($arrayhistorico['updated_at']);
            unset($arrayhistorico['retroativo']);
            unset($arrayhistorico['retroativo_mesref_de']);
            unset($arrayhistorico['retroativo_anoref_de']);
            unset($arrayhistorico['retroativo_mesref_ate']);
            unset($arrayhistorico['retroativo_anoref_ate']);
            unset($arrayhistorico['retroativo_vencimento']);
            unset($arrayhistorico['retroativo_valor']);

            $array = array_filter($arrayhistorico, function ($a) {
                return trim($a) !== "";
            });

            Contrato::where('id', '=', $contrato_id)
                ->update($array);

        }
    }

}
