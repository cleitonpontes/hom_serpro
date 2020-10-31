<?php

namespace App\Http\Controllers\Api;

use App\Models\AmparoLegal;
use App\Models\CompraItem;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\SaldoContabil;
use App\Models\SfCelulaOrcamentaria;
use App\Models\SfOrcEmpenhoDados;
use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Route;
use App\Http\Controllers\Controller;


class MinutaEmpenhoController extends Controller
{
    public function populaTabelasSiafi(Request $request)
    {
        $retorno['resultado'] = false;
        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
        $modSaldoContabil = SaldoContabil::find($modMinutaEmpenho->saldo_contabil_id);
        dump(substr($modSaldoContabil->conta_corrente,31,11));
        dd($modSaldoContabil);
//        $ug = $unidade->codigo;
//        $gestao = $unidade->gestao;
//        $contacontabil = config('app.conta_contabil_credito_disponivel');

            DB::beginTransaction();
            try {
            $sforcempenhodados = $this->gravaSfOrcEmpenhoDados($modMinutaEmpenho);
            $sfcelulaorcamentaria = $this->gravaSfCelulaOrcamentaria($sforcempenhodados);

            } catch (\Exception $exc) {
                DB::rollback();
            }

        return $retorno;
    }

    public function gravaSfOrcEmpenhoDados(MinutaEmpenho $modMinutaEmpenho)
    {
        $modSfOrcEmpenhoDados = new SfOrcEmpenhoDados();
        $modSfOrcEmpenhoDados->minutaempenho_id = $modMinutaEmpenho->id;
        $modSfOrcEmpenhoDados->ugemitente = $modMinutaEmpenho->unidade_id;
        $modSfOrcEmpenhoDados->anoempenho = $modMinutaEmpenho->date('Y');
        $modSfOrcEmpenhoDados->tipoempenho = (CompraItem::where('id',$modMinutaEmpenho->tipo_empenho_id)->select('descres')->get());
        $modSfOrcEmpenhoDados->numempenho = (!is_null($modMinutaEmpenho->numero_empenho_sequencial))?$modMinutaEmpenho->numero_empenho_sequencial:'';
        $modSfOrcEmpenhoDados->dtemis = (!is_null($modMinutaEmpenho->data_emissao))?$modMinutaEmpenho->data_emissao:'';
        $modSfOrcEmpenhoDados->txtprocesso = (!is_null($modMinutaEmpenho->processo))?$modMinutaEmpenho->processo:'';
        $modSfOrcEmpenhoDados->vlrtaxacambio = (!is_null($modMinutaEmpenho->taxa_cambio))?$modMinutaEmpenho->taxa_cambio:'';
        $modSfOrcEmpenhoDados->vlrempenho = (!is_null($modMinutaEmpenho->valor_total))?$modMinutaEmpenho->valor_total:'';
        $modSfOrcEmpenhoDados->codfavorecido = (Fornecedor::where('id',$modMinutaEmpenho->fornecedor_empenho_id)->select('cpf_cnpj_idgener')->get());
        $modSfOrcEmpenhoDados->codamparolegal = (AmparoLegal::where('id',$modMinutaEmpenho->amparo_legal_id)->select('codigo')->get());
        $modSfOrcEmpenhoDados->txtinfocompl = $modMinutaEmpenho->informacao_complementar;
        $modSfOrcEmpenhoDados->txtlocalentrega = $modMinutaEmpenho->local_entrega;
        $modSfOrcEmpenhoDados->txtdescricao = $modMinutaEmpenho->descricao;
        $modSfOrcEmpenhoDados->situacao = 'PENDENTE';

        $modSfOrcEmpenhoDados->save();
        return $modSfOrcEmpenhoDados;
    }


    public function gravaSfCelulaOrcamentaria(SfOrcEmpenhoDados $sforcempenhodados,SaldoContabil $modSaldoContabil)
    {
        $modSfCelulaOrcamentaria = new SfCelulaOrcamentaria();
        $modSfCelulaOrcamentaria->sforcempenhodado_id = $sforcempenhodados->id;
        $modSfCelulaOrcamentaria->esfera = substr($modSaldoContabil->conta_corrente,0,1);
        $modSfCelulaOrcamentaria->codptres = substr($modSaldoContabil->conta_corrente,1,6);
        $modSfCelulaOrcamentaria->codfonterec = substr($modSaldoContabil->conta_corrente,7,10);
        $modSfCelulaOrcamentaria->codnatdesp = substr($modSaldoContabil->conta_corrente,17,6);
        $modSfCelulaOrcamentaria->ugresponsavel = substr($modSaldoContabil->conta_corrente,23,8); //VERIFICAR SE ESSA É A UGR DA TRIPA
        $modSfCelulaOrcamentaria->codplanointerno = substr($modSaldoContabil->conta_corrente,31,11);

        $modSfCelulaOrcamentaria->save();
        return $modSfCelulaOrcamentaria;
    }

}
