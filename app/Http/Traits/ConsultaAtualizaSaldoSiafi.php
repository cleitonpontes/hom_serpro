<?php

namespace App\Http\Traits;

use App\Models\Empenhodetalhado;
use App\XML\Execsiafi;

trait ConsultaAtualizaSaldoSiafi {

    public function consultaAtualizaSaldoSiafi($ug, $empenho, $subitem, $id_ug)
    {
        $registro = array();
        $registro['ug'] = $ug;
        $registro['empenho'] = $empenho;
        $registro['subitem'] = $subitem;

        $contas_contabeis = config('app.contas_contabeis_empenhodetalhado_exercicioatual');

        foreach ($contas_contabeis as $key => $value){
            // Consulta saldo do empenho
            $saldoAtual = $this->consultaSaldoSiafi($registro, $value);
            if($saldoAtual >= 0 or $saldoAtual != '') {
                // Atualiza o saldo retornado
                $this->atualizaSaldo($empenho, $subitem, $saldoAtual, $id_ug, $key);
            }
        }
    }

    /**
     * Realiza consulta do saldo do empenho no SIAFI de um dado empenho + subitem
     *
     * @param array $registro
     * @return number|string
     */
    private function consultaSaldoSiafi($registro, $contacontabil1)
    {
        // Valores fixos
        $amb = config('app.ambiente_siafi');
        $meses = array('', 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ');

        $ug = $registro['ug'];
        $ano = date('Y'); //$registro['ano'];
        $mes = $meses[(int) date('m')];//$meses[(int) $registro['mes']];
        $empenho = $registro['empenho'];
        $subitem = $registro['subitem'];

        $contacorrente = 'N' . $empenho . str_pad($subitem, 2, '0', STR_PAD_LEFT);
        $saldoAtual = 0;

        try {
            $execsiafi = new Execsiafi();

            $retorno = null;
            $retorno = $execsiafi->conrazaoAPIComprasNet($ug, $amb, $ano, $ug, $contacontabil1, $contacorrente, $mes);

            if (isset($retorno->resultado[4])) {
                $saldoAtual = (string) $retorno->resultado[4];
            }
        } catch (Exception $e) {
            // dd('Erro no validaSaldo()', $e);
        }

        return $saldoAtual;
    }

    /**
     * Grava registro com a atualização do saldo consultado
     *
     * @param string $empenho
     * @param string $subitem
     * @param number $saldo
     */
    private function atualizaSaldo($empenho, $subitem, $saldo, $id_ug, $coluna)
    {
        $modelo = new Empenhodetalhado();

        $dados = $modelo->leftjoin('empenhos as E', 'E.id', '=', 'empenho_id');
        $dados->leftjoin('naturezasubitem AS S', function ($relacao) {
            $relacao->on('S.id', '=', 'naturezasubitem_id');
        });
        $dados->leftjoin('naturezadespesa AS N', function ($relacao) {
            $relacao->on('N.id', '=', 'S.naturezadespesa_id');
        });
        $dados->where('E.unidade_id', $id_ug);
        $dados->where('E.numero', $empenho);
        $dados->where('S.codigo', $subitem);


        // Atualiza saldo
        $dados->update([$coluna => $saldo]);

    }
}
