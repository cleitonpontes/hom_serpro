<?php

namespace App\Observers;

use App\Models\Contrato;
use App\Models\Contratodespesaacessoria;
use Illuminate\Support\Facades\DB;

class ContratodespesaacessoriaObserver
{

    public function created(Contratodespesaacessoria $contratodespesaacessoria)
    {
        $this->atualizaTotalDespesaAcessoria($contratodespesaacessoria->contrato_id);
    }

    public function updated(Contratodespesaacessoria $contratodespesaacessoria)
    {
        $this->atualizaTotalDespesaAcessoria($contratodespesaacessoria->contrato_id);
    }


    public function deleted(Contratodespesaacessoria $contratodespesaacessoria)
    {
        $this->atualizaTotalDespesaAcessoria($contratodespesaacessoria->contrato_id);
    }

    private function atualizaTotalDespesaAcessoria($contrato_id)
    {
        $dado = DB::table('contratodespesaacessoria')
            ->select(DB::raw('SUM(valor) as total'))
            ->where('contrato_id',$contrato_id)
            ->where('deleted_at',null)
            ->first();

        $contrato =  Contrato::find($contrato_id);
        $contrato->total_despesas_acessorias = $dado->total;
        $contrato->save();
    }

}
