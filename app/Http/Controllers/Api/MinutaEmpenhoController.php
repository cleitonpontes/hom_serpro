<?php

namespace App\Http\Controllers\Api;

use App\Models\AmparoLegal;
use App\Models\Codigoitem;
use App\Models\CompraItem;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\ContaCorrentePassivoAnterior;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\Naturezasubitem;
use App\Models\SaldoContabil;
use App\Models\SfCelulaOrcamentaria;
use App\Models\SfItemEmpenho;
use App\Models\SfOperacaoItemEmpenho;
use App\Models\SfOrcEmpenhoDados;
use App\Models\SfPassivoAnterior;
use App\Models\SfPassivoPermanente;
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

            DB::beginTransaction();
            try {

            $sforcempenhodados = $this->gravaSfOrcEmpenhoDados($modMinutaEmpenho);
            $this->gravaSfCelulaOrcamentaria($sforcempenhodados,$modSaldoContabil);
            if($modMinutaEmpenho->passivo_anterior){
                $this->gravaSfPassivoAnterior($sforcempenhodados,$modMinutaEmpenho);
            }
            $this->gravaSfItensEmpenho($modMinutaEmpenho,$sforcempenhodados);

            DB::commit();
            $retorno['resultado'] = true;

            } catch (\Exception $exc) {
                DB::rollback();
            }

        return $retorno;
    }

    public function gravaSfOrcEmpenhoDados(MinutaEmpenho $modMinutaEmpenho)
    {
        $modSfOrcEmpenhoDados = new SfOrcEmpenhoDados();
        $tipoEmpenho = Codigoitem::find($modMinutaEmpenho->tipo_empenho_id);
        $favorecido = Fornecedor::find($modMinutaEmpenho->fornecedor_empenho_id);
        $amparoLegal = AmparoLegal::find($modMinutaEmpenho->amparo_legal_id);
        $ugemitente = Unidade::find($modMinutaEmpenho->id);
        $codfavorecido = (str_replace('-','', str_replace('/', '', str_replace('.', '', $favorecido->cpf_cnpj_idgener))));

        $modSfOrcEmpenhoDados->minutaempenho_id = $modMinutaEmpenho->id;
        $modSfOrcEmpenhoDados->ugemitente = $ugemitente->codigo;
        $modSfOrcEmpenhoDados->anoempenho = (int)date('Y');
        $modSfOrcEmpenhoDados->tipoempenho = $tipoEmpenho->descres;
        $modSfOrcEmpenhoDados->numempenho = (!is_null($modMinutaEmpenho->numero_empenho_sequencial))?$modMinutaEmpenho->numero_empenho_sequencial:NULL;
        $modSfOrcEmpenhoDados->dtemis = $modMinutaEmpenho->data_emissao;
        $modSfOrcEmpenhoDados->txtprocesso = (!is_null($modMinutaEmpenho->processo))?$modMinutaEmpenho->processo:NULL;
        $modSfOrcEmpenhoDados->vlrtaxacambio = (!is_null($modMinutaEmpenho->taxa_cambio))?$modMinutaEmpenho->taxa_cambio:NULL;
        $modSfOrcEmpenhoDados->vlrempenho = (!is_null($modMinutaEmpenho->valor_total))?$modMinutaEmpenho->valor_total:NULL;
        $modSfOrcEmpenhoDados->codfavorecido = $codfavorecido;
        $modSfOrcEmpenhoDados->codamparolegal = $amparoLegal->codigo;
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
        $modSfCelulaOrcamentaria->esfera = (int)substr($modSaldoContabil->conta_corrente,0,1);
        $modSfCelulaOrcamentaria->codptres = substr($modSaldoContabil->conta_corrente,1,6);
        $modSfCelulaOrcamentaria->codfonterec = substr($modSaldoContabil->conta_corrente,7,10);
        $modSfCelulaOrcamentaria->codnatdesp = (int)substr($modSaldoContabil->conta_corrente,17,6);
        $modSfCelulaOrcamentaria->ugresponsavel = (int)substr($modSaldoContabil->conta_corrente,23,8); //VERIFICAR SE ESSA É A UGR DA TRIPA
        $modSfCelulaOrcamentaria->codplanointerno = substr($modSaldoContabil->conta_corrente,31,11);

        $modSfCelulaOrcamentaria->save();
        return $modSfCelulaOrcamentaria;
    }

    public function gravaSfPassivoAnterior(SfOrcEmpenhoDados $sforcempenhodados,MinutaEmpenho $modMinutaEmpenho)
    {
        $modSfPassivoAnterior = new SfPassivoAnterior();
        $modSfPassivoAnterior->sforcempenhodado_id = $sforcempenhodados->id;
        $modSfPassivoAnterior->codcontacontabil = $modMinutaEmpenho->conta_contabil_passivo_anterior;
        $modSfPassivoAnterior->save();

        $this->gravaSfPassivoPermanente($modSfPassivoAnterior,$modMinutaEmpenho);

        return $modSfPassivoAnterior;
    }

    public function gravaSfPassivoPermanente(SfPassivoAnterior $sfpassivoanterior,MinutaEmpenho $modMinutaEmpenho)
    {
        $modCCPassivoAnterior = ContaCorrentePassivoAnterior::where('minutaempenho_id',$modMinutaEmpenho->id)->get();
        foreach ($modCCPassivoAnterior as $key => $conta){
            $modSfPassivoPermanente = new SfPassivoPermanente();
            $modSfPassivoPermanente->sfpassivoanterior_id = $sfpassivoanterior->id;
            $modSfPassivoPermanente->contacorrente = $conta->conta_corrente;
            $modSfPassivoPermanente->vlrrelacionado = $conta->valor;
            $modSfPassivoPermanente->save();
        }
        return $modSfPassivoPermanente;
    }

    public function gravaSfItensEmpenho(MinutaEmpenho $modMinutaEmpenho,SfOrcEmpenhoDados $sforcempenhodados)
    {

        $modCompraItemEmpenho = CompraItemMinutaEmpenho::where('minutaempenho_id',$modMinutaEmpenho->id)->get();

        foreach ($modCompraItemEmpenho as $key => $item){

            $modSfItemEmpenho = new SfItemEmpenho();
            $modSubelemento = Naturezasubitem::find($item->subelemento_id);

            $modSfItemEmpenho->sforcempenhodado_id = $sforcempenhodados->id;
            $modSfItemEmpenho->numseqitem = $key+1;
            $modSfItemEmpenho->codsubelemento = $modSubelemento->codigo;
            $modSfItemEmpenho->descricao = (strlen($modSubelemento->descricao) < 1248 ) ? $modSubelemento->descricao : substr($modSubelemento->descricao,0,1248);
            $modSfItemEmpenho->save();

            $this->gravaSfOperacaoItemEmpenho($modSfItemEmpenho,$item);
        }

    }

    public function gravaSfOperacaoItemEmpenho(SfItemEmpenho $modSfItemEmpenho,CompraItemMinutaEmpenho $item)
    {
            $modSfOpItemEmpenho = new SfOperacaoItemEmpenho();
            $modSfOpItemEmpenho->sfitemempenho_id = $modSfItemEmpenho->id;
            $modSfOpItemEmpenho->tipooperacaoitemempenho = 'INCLUSAO'; // Incluir nas tabelas codigo (OPERACAOITEMEMPENHO) e codigoitens (INCLUSÃO - REFORCO - ANULACAO - CANCELAMENTO)
            $modSfOpItemEmpenho->quantidade = $item->quantidade;
            $modSfOpItemEmpenho->vlrunitario = ($item->valor / $item->quantidade);
            $modSfOpItemEmpenho->vlroperacao = $item->valor;
            $modSfOpItemEmpenho->save();
    }

    public function novoEmpenhoMesmaCompra()
    {
        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);

        DB::beginTransaction();
        try {
            $novoEmpenho = new MinutaEmpenho();
            $novoEmpenho->unidade_id = $modMinutaEmpenho->unidade_id;
            $novoEmpenho->compra_id = $modMinutaEmpenho->compra_id;
            $novoEmpenho->informacao_complementar = $modMinutaEmpenho->informacao_complementar;
            $novoEmpenho->etapa = 2;
            $novoEmpenho->save();
            DB::commit();
            return json_encode($novoEmpenho->id);
        } catch (\Exception $exc) {
            DB::rollback();
        }



    }


}
