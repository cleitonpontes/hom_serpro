<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Publicacao\SiafiEmpenhoController;
use App\Models\SaldoContabil;
use App\Models\Unidade;
use App\STA\ConsultaApiSta;
use App\XML\Execsiafi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Route;
use App\Http\Controllers\Controller;


class SaldoContabilController extends Controller
{
    public function atualizaSaldosPorLinha(Request $request)
    {
        $retorno = false;

        $saldo_id = Route::current()->parameter('saldo_id');
        $saldoAtualizado = $this->consultaSaldoSiafi($saldo_id);
        if(!empty($saldoAtualizado)) {
            DB::beginTransaction();
            try {
                $modSaldo = SaldoContabil::find($saldo_id);
                $modSaldo->saldo = $saldoAtualizado;
                $modSaldo->save();
                DB::commit();
                $retorno = true;
            } catch (\Exception $exc) {
                DB::rollback();
            }
        }
        return json_encode($retorno);
    }

    public function atualizaSaldosPorUnidade(Request $request)
    {
        $retorno = false;
        $cod_unidade = Route::current()->parameter('cod_unidade');
        $unidade = Unidade::where('codigo',$cod_unidade)->first();
        $modSaldoContabil = SaldoContabil::where('unidade_id',$unidade->id)->get();
        DB::beginTransaction();
        try {
            foreach($modSaldoContabil as $key => $saldocontabil){
                $saldoAtualizado = $this->consultaSaldoSiafi($saldocontabil->id);
//                dd($saldoAtualizado);
                if(!empty($saldoAtualizado)) {
                    $saldocontabil->saldo = $saldoAtualizado;
                    $saldocontabil->save();
                }
            }
            DB::commit();
            $retorno = true;
        } catch (\Exception $exc) {
            DB::rollback();
        }
        return json_encode($retorno);
    }

    public function inserirCelulaOrcamentaria(Request $request)
    {
        $cod_unidade = Route::current()->parameter('cod_unidade');
        $contacorrente = Route::current()->parameter('contacorrente');

        $amb = config('app.ambiente_siafi');
        $system_user = config('app.usuario_siafi');
        $pwd = config('app.senha_siafi');

        $unidade = Unidade::where('codigo',$cod_unidade)->first();
        $meses = array('', 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ');
        $ano = config('app.ano_minuta_empenho');
        $ug = $unidade->codigo;
        $contacontabil = config('app.conta_contabil_credito_disponivel');
        $conta_corrente = "N".$contacorrente;
        $mes = $meses[(int) config('app.mes_minuta_empenho')];
        $execsiafi = new Execsiafi();
        $contaSiafi = $execsiafi->conrazaoUserSystem($system_user,$pwd, $amb, $ano, $ug, $contacontabil,$conta_corrente, $mes);

        if (empty($contaSiafi->resultado[4])) {
            $retorno['resultado'] = null;
            return json_encode($retorno);
        }

        $saldoExiste = SaldoContabil::where('conta_corrente',$contacorrente)
            ->where('unidade_id',$unidade->id)->first();

        if(is_null($saldoExiste)) {
            DB::beginTransaction();
            try {
                $modSaldo = new SaldoContabil();
                $modSaldo->unidade_id = $unidade->id;
                $modSaldo->ano = $ano;
                $modSaldo->conta_contabil = $contacontabil;
                $modSaldo->conta_corrente = $contacorrente;
                $modSaldo->saldo = (string)$contaSiafi->resultado[4];
                $modSaldo->save();
                DB::commit();
                $retorno['resultado'] = true;
            } catch (\Exception $exc) {
                DB::rollback();
            }
        }else{
            $retorno['resultado'] = false;
        }


        return json_encode($retorno);
    }

    /**
     * Realiza consulta do saldo do empenho no SIAFI de um dado empenho + subitem
     *
     * @param array $registro
     * @return number|string
     */
    private function consultaSaldoSiafi($saldo_id)
    {
        $meses = array('', 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ');
        $saldo = SaldoContabil::find($saldo_id);
        $unidade = Unidade::find($saldo->unidade_id);

        $amb = config('app.ambiente_siafi');
        $ano = config('app.ano_minuta_empenho');
        $ug = $unidade->codigo;
        $contacontabil = config('app.conta_contabil_credito_disponivel');
        $contacorrente = "N".$saldo->conta_corrente;
        $system_user = config('app.usuario_siafi');
        $pwd = config('app.senha_siafi');
        $mes = $meses[(int) config('app.mes_minuta_empenho')];//$meses[(int) $registro['mes']];

//        $contacorrente = 'N11184940100000000339039        AGU0042'; //DESCOMENTE PARA TESTAR A ATUALIZACAO DO SALDO POR LINHA

        $execsiafi = new Execsiafi();
        $retorno = null;
        $retorno = $execsiafi->conrazaoUserSystem($system_user,$pwd, $amb, $ano, $ug, $contacontabil,$contacorrente, $mes);

        if (!empty($retorno->resultado[4])) {
            return (string) $retorno->resultado[4];
        }
        return "";
    }


    public function carregaSaldosPorUnidadeSiasg(Request $request)
    {
        $retorno = true;
        $cod_unidade = Route::current()->parameter('cod_unidade');

        $unidade = Unidade::where('codigo',$cod_unidade)->first();
        $ano = config('app.ano_minuta_empenho');
        $ug = $unidade->codigo;
        $gestao = $unidade->gestao;
        $contacontabil = config('app.conta_contabil_credito_disponivel');

        $apiSta = new ConsultaApiSta();
        $saldosContabeis = json_encode($apiSta->saldocontabilAnoUgGestaoContacontabil($ano, $ug, $gestao, $contacontabil));

        if(!is_null($saldosContabeis)) {
            DB::beginTransaction();
            try {
                foreach (json_decode($saldosContabeis) as $key => $saldo) {

                    $saldoLocal = SaldoContabil::where('conta_corrente', $saldo->contacorrente)->first();

                    if (!is_null($saldoLocal)){
                        if (strtotime($saldoLocal->updated_at) < strtotime($saldo->updated_at)) {
                            $saldoLocal->AtualizaSaldoContabil($ano, $unidade->id, $saldo->contacorrente, $contacontabil, $saldo->saldo);
                        }
                    }
                    if (is_null($saldoLocal)){
                        $saldoLocal = new SaldoContabil();
                        $saldoLocal->ano = $ano;
                        $saldoLocal->unidade_id = $unidade->id;
                        $saldoLocal->conta_corrente = $saldo->contacorrente;
                        $saldoLocal->conta_contabil = $contacontabil;
                        $saldoLocal->saldo = $saldo->saldo;
                        $saldoLocal->save();
//                        $saldoLocal->gravaSaldoContabil($ano, $unidade->id, $saldo->contacorrente, $contacontabil, $saldo->saldo);
                    }
                }

                DB::commit();
                $request->session()->put('unidade_ajax_id', $unidade->id);
            } catch (\Exception $exc) {
                DB::rollback();
            }
        }
        return json_encode($retorno);
    }

}
