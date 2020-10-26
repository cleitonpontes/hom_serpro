<?php

namespace App\Http\Controllers\Api;

use App\Models\SaldoContabil;
use App\Models\Unidade;
use App\STA\ConsultaApiSta;
use Illuminate\Support\Facades\DB;
use Route;
use App\Http\Controllers\Controller;


class SaldoContabilController extends Controller
{
    public function atualizaSaldosPorUnidade()
    {
        $cod_unidade = Route::current()->parameter('cod_unidade');

        $unidade = Unidade::where('codigo',$cod_unidade)->first();

        $ano = date('Y');
        $ug = $unidade->codigo;
        $gestao = $unidade->gestao;
        $contacontabil = config('app.conta_contabil_credito_disponivel');

        $apiSta = new ConsultaApiSta();
        $saldosContabeis = json_encode($apiSta->saldocontabilAnoUgGestaoContacontabil($ano,$ug,$gestao,$contacontabil));
        DB::beginTransaction();
        try {
            foreach (json_decode($saldosContabeis) as $key => $saldo) {
                $modSaldoContabil = new SaldoContabil();
                $modSaldoContabil->gravaSaldoContabil($ano, $unidade->id, $saldo->contacorrente, $contacontabil, $saldo->saldo);
            }
            DB::commit();

            $saldos = json_encode($modSaldoContabil->retornaSaldos());

        } catch (\Exception $exc) {
            DB::rollback();
        }

        return $saldos;
    }

}
