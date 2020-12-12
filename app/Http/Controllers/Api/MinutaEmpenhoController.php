<?php

namespace App\Http\Controllers\Api;

use App\Models\AmparoLegal;
use App\Models\Codigoitem;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\CompraItemUnidade;
use App\Models\ContaCorrentePassivoAnterior;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\MinutaEmpenhoRemessa;
use App\Models\Naturezasubitem;
use App\Models\SaldoContabil;
use App\Models\SfCelulaOrcamentaria;
use App\Models\SfItemEmpenho;
use App\Models\SfOperacaoItemEmpenho;
use App\Models\SfOrcEmpenhoDados;
use App\Models\SfPassivoAnterior;
use App\Models\SfPassivoPermanente;
use App\Models\SfRegistroAlteracao;
use App\Models\Unidade;
use App\Models\Catmatseritem;
use App\XML\ApiSiasg;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Route;
use App\Http\Controllers\Controller;
use App\Http\Traits\CompraTrait;

//use function GuzzleHttp\Promise\all;

class MinutaEmpenhoController extends Controller
{

    use CompraTrait;

    public function populaTabelasSiafi(Request $request): array
    {
        $retorno['resultado'] = false;
        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
        $modSaldoContabil = SaldoContabil::find($modMinutaEmpenho->saldo_contabil_id);

        DB::beginTransaction();
        try {
            $sforcempenhodados = $this->gravaSfOrcEmpenhoDados($modMinutaEmpenho);
            $this->gravaSfCelulaOrcamentaria($sforcempenhodados, $modSaldoContabil);
            if ($modMinutaEmpenho->passivo_anterior) {
                $this->gravaSfPassivoAnterior($sforcempenhodados, $modMinutaEmpenho);
            }
            $this->gravaSfItensEmpenho($modMinutaEmpenho, $sforcempenhodados);
            $this->gravaMinuta($modMinutaEmpenho);

            DB::commit();
            $retorno['resultado'] = true;
        } catch (Exception $exc) {
            DB::rollback();
//            dd($exc);
        }

        return $retorno;
    }

    public function gravaSfOrcEmpenhoDados(MinutaEmpenho $modMinutaEmpenho)
    {
        $modSfOrcEmpenhoDados = new SfOrcEmpenhoDados();
        $tipoEmpenho = Codigoitem::find($modMinutaEmpenho->tipo_empenho_id);
        $favorecido = Fornecedor::find($modMinutaEmpenho->fornecedor_empenho_id);
        $amparoLegal = AmparoLegal::find($modMinutaEmpenho->amparo_legal_id);
        $ugemitente = Unidade::find($modMinutaEmpenho->unidade_id);
        $codfavorecido = (str_replace('-', '', str_replace('/', '', str_replace('.', '', $favorecido->cpf_cnpj_idgener))));

        $modSfOrcEmpenhoDados->minutaempenho_id = $modMinutaEmpenho->id;
        $modSfOrcEmpenhoDados->ugemitente = $ugemitente->codigo;
        $modSfOrcEmpenhoDados->anoempenho = (int)date('Y');
        $modSfOrcEmpenhoDados->tipoempenho = $tipoEmpenho->descres;
        $modSfOrcEmpenhoDados->numempenho = (!is_null($modMinutaEmpenho->numero_empenho_sequencial)) ? $modMinutaEmpenho->numero_empenho_sequencial : null;
        $modSfOrcEmpenhoDados->dtemis = $modMinutaEmpenho->data_emissao;
        $modSfOrcEmpenhoDados->txtprocesso = (!is_null($modMinutaEmpenho->processo)) ? $modMinutaEmpenho->processo : null;
        $modSfOrcEmpenhoDados->vlrtaxacambio = (!is_null($modMinutaEmpenho->taxa_cambio)) ? $modMinutaEmpenho->taxa_cambio : null;
        $modSfOrcEmpenhoDados->vlrempenho = (!is_null($modMinutaEmpenho->valor_total)) ? $modMinutaEmpenho->valor_total : null;
        $modSfOrcEmpenhoDados->codfavorecido = $codfavorecido;
        $modSfOrcEmpenhoDados->codamparolegal = $amparoLegal->codigo;
        $modSfOrcEmpenhoDados->txtinfocompl = $modMinutaEmpenho->informacao_complementar;
        $modSfOrcEmpenhoDados->txtlocalentrega = $modMinutaEmpenho->local_entrega;
        $modSfOrcEmpenhoDados->txtdescricao = $modMinutaEmpenho->descricao;
        $modSfOrcEmpenhoDados->situacao = 'EM PROCESSAMENTO';
        $modSfOrcEmpenhoDados->cpf_user = backpack_user()->cpf;

        $modSfOrcEmpenhoDados->save();
        return $modSfOrcEmpenhoDados;
    }

    public function gravaSfCelulaOrcamentaria(SfOrcEmpenhoDados $sforcempenhodados, SaldoContabil $modSaldoContabil)
    {
        $modSfCelulaOrcamentaria = new SfCelulaOrcamentaria();
        $modSfCelulaOrcamentaria->sforcempenhodado_id = $sforcempenhodados->id;
        $modSfCelulaOrcamentaria->esfera = (int)substr($modSaldoContabil->conta_corrente, 0, 1);
        $modSfCelulaOrcamentaria->codptres = substr($modSaldoContabil->conta_corrente, 1, 6);
        $modSfCelulaOrcamentaria->codfonterec = substr($modSaldoContabil->conta_corrente, 7, 10);
        $modSfCelulaOrcamentaria->codnatdesp = (int)substr($modSaldoContabil->conta_corrente, 17, 6);
        $modSfCelulaOrcamentaria->ugresponsavel = (int)substr($modSaldoContabil->conta_corrente, 23, 8);
        $modSfCelulaOrcamentaria->codplanointerno = substr($modSaldoContabil->conta_corrente, 31, 11);

        $modSfCelulaOrcamentaria->save();
        return $modSfCelulaOrcamentaria;
    }

    public function gravaSfPassivoAnterior(SfOrcEmpenhoDados $sforcempenhodados, MinutaEmpenho $modMinutaEmpenho)
    {
        $modSfPassivoAnterior = new SfPassivoAnterior();
        $modSfPassivoAnterior->sforcempenhodado_id = $sforcempenhodados->id;
        $modSfPassivoAnterior->codcontacontabil = $modMinutaEmpenho->conta_contabil_passivo_anterior;
        $modSfPassivoAnterior->save();

        $this->gravaSfPassivoPermanente($modSfPassivoAnterior, $modMinutaEmpenho);

        return $modSfPassivoAnterior;
    }

    public function gravaSfPassivoPermanente(SfPassivoAnterior $sfpassivoanterior, MinutaEmpenho $modMinutaEmpenho)
    {
        $modCCPassivoAnterior = ContaCorrentePassivoAnterior::where('minutaempenho_id', $modMinutaEmpenho->id)->get();
        foreach ($modCCPassivoAnterior as $key => $conta) {
            $modSfPassivoPermanente = new SfPassivoPermanente();
            $modSfPassivoPermanente->sfpassivoanterior_id = $sfpassivoanterior->id;
            $modSfPassivoPermanente->contacorrente = "P" . $conta->conta_corrente;
            $modSfPassivoPermanente->vlrrelacionado = $conta->valor;
            $modSfPassivoPermanente->save();
        }
        return $modSfPassivoPermanente;
    }

    public function gravaSfItensEmpenho(MinutaEmpenho $modMinutaEmpenho, SfOrcEmpenhoDados $sforcempenhodados, $remessa_id = 0)
    {

        $modCompraItemEmpenho = CompraItemMinutaEmpenho::where('minutaempenho_id', $modMinutaEmpenho->id)
            ->where('minutaempenhos_remessa_id', $remessa_id)
            ->get();
//        dd($modCompraItemEmpenho);

        foreach ($modCompraItemEmpenho as $key => $item) {
            if ($item->operacao !== 'NENHUMA') {
                $modSfItemEmpenho = new SfItemEmpenho();
                $modSubelemento = Naturezasubitem::find($item->subelemento_id);
                $modSfItemEmpenho->sforcempenhodado_id = $sforcempenhodados->id;
                $modSfItemEmpenho->numseqitem = $key + 1;
                $modSfItemEmpenho->codsubelemento = $modSubelemento->codigo;
                $modSfItemEmpenho->descricao = $this->buscaDescricao($item->compra_item_id);
                $modSfItemEmpenho->save();

                $this->gravaSfOperacaoItemEmpenho($modSfItemEmpenho, $item);
            }
        }
    }

    public function gravaSfOperacaoItemEmpenho(SfItemEmpenho $modSfItemEmpenho, CompraItemMinutaEmpenho $item)
    {

        $modSfOpItemEmpenho = new SfOperacaoItemEmpenho();
        $modSfOpItemEmpenho->sfitemempenho_id = $modSfItemEmpenho->id;
        $modSfOpItemEmpenho->tipooperacaoitemempenho = $item->operacao; // Incluir nas tabelas codigo (OPERACAOITEMEMPENHO) e codigoitens (INCLUSÃO - REFORCO - ANULACAO - CANCELAMENTO)
        $modSfOpItemEmpenho->quantidade = $item->quantidade;
        $modSfOpItemEmpenho->vlrunitario = ($item->valor / $item->quantidade);
        $modSfOpItemEmpenho->vlroperacao = $item->valor;
        $modSfOpItemEmpenho->save();
    }

    public function gravaMinuta(MinutaEmpenho $modMinutaEmpenho)
    {

        $situacao = Codigoitem::wherehas('codigo', function ($q) {
            $q->where('descricao', '=', 'Situações Minuta Empenho');
        })
            ->where('descricao', 'EM PROCESSAMENTO')
            ->first();

        $modMinutaEmpenho->situacao_id = $situacao->id;
        $modMinutaEmpenho->save();
    }

    public function novoEmpenhoMesmaCompra()
    {
        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
        $situacao = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Situações Minuta Empenho');
        })
            ->where('descricao', 'EM ANDAMENTO')
            ->select('codigoitens.id')->first();

        DB::beginTransaction();
        try {
            $this->atualizaSaldoCompraItemUnidade($modMinutaEmpenho);
            $novoEmpenho = new MinutaEmpenho();
            $novoEmpenho->unidade_id = $modMinutaEmpenho->unidade_id;
            $novoEmpenho->compra_id = $modMinutaEmpenho->compra_id;
            $novoEmpenho->informacao_complementar = $modMinutaEmpenho->informacao_complementar;
            $novoEmpenho->situacao_id = $situacao->id;//em andamento
            $novoEmpenho->etapa = 2;
            $novoEmpenho->save();

            DB::commit();
            return json_encode($novoEmpenho->id);
        } catch (Exception $exc) {
            DB::rollback();
        }
    }

    public function atualizaSaldoCompraItemUnidade(MinutaEmpenho $modMinutaEmpenho)
    {
        $compra = Compra::find($modMinutaEmpenho->compra_id)->first();

        $compraSiasg = $this->buscaCompraSiasg($compra);

        if ($compraSiasg->data->compraSispp->tipoCompra == 1) {
            $this->gravaParametroItensdaCompraSISPP($compraSiasg, $compra);
        }

        if ($compraSiasg->data->compraSispp->tipoCompra == 2) {
            $this->gravaParametroItensdaCompraSISRP($compraSiasg, $compra);
        }
    }

    public function buscaCompraSiasg(Compra $compra)
    {
        $uasgCompra_id = (!is_null($compra->unidade_subrrogada_id)) ? $compra->unidade_subrrogada_id : $compra->unidade_origem_id;

        $modalidade = Codigoitem::find($compra->modalidade_id);
        $uasgCompra = Unidade::find($uasgCompra_id);
        $numero_ano = explode('/', $compra->numero_ano);
        $apiSiasg = new ApiSiasg();

        $params = [
            'modalidade' => $modalidade->descres,
            'numeroAno' => $numero_ano[0] . $numero_ano[1],
            'uasgCompra' => $uasgCompra->codigo,
            'uasgUsuario' => session('user_ug')
        ];

        $compra = json_decode($apiSiasg->executaConsulta('COMPRASISPP', $params));

        return $compra;
    }

    public function buscaDescricao($compra_id)
    {
        $descricao = '';
        $modCompraItem = CompraItem::find($compra_id);
        $modcatMatSerItem = Catmatseritem::find($modCompraItem->catmatseritem_id);
        (!empty($modCompraItem->descricaodetalhada)) ? $descricao = $modCompraItem->descricaodetalhada : $descricao = $modcatMatSerItem->descricao;
        return (strlen($descricao) < 1248) ? $descricao : substr($descricao, 0, 1248);
    }

    public function populaTabelasSiafiAlteracao(): array
    {
        $retorno['resultado'] = false;
        $minuta_id = Route::current()->parameter('minuta_id');
        $remessa_id = Route::current()->parameter('remessa');

        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
        $modRemessa = MinutaEmpenhoRemessa::find($remessa_id);

        DB::beginTransaction();
        try {
            $sforcempenhodadosalt = $this->gravaSfOrcEmpenhoDadosAlt($modMinutaEmpenho);

            if ($modMinutaEmpenho->passivo_anterior) {
                $this->gravaSfPassivoAnterior($sforcempenhodadosalt, $modMinutaEmpenho);
            }

            $this->gravaSfItensEmpenho($modMinutaEmpenho, $sforcempenhodadosalt, $remessa_id);

            $this->gravaSfRegistroAlteracao($sforcempenhodadosalt);

            $this->gravaRemessa($modRemessa);

            DB::commit();
            $retorno['resultado'] = true;
        } catch (Exception $exc) {
            DB::rollback();
//            dd($exc);
        }

        return $retorno;
    }

    public function gravaSfOrcEmpenhoDadosAlt(MinutaEmpenho $modMinutaEmpenho)
    {
//        dd($modMinutaEmpenho->max_remessa);
        $modSfOrcEmpenhoDados = new SfOrcEmpenhoDados();

        $ugemitente = Unidade::find($modMinutaEmpenho->unidade_id);

        $modSfOrcEmpenhoDados->minutaempenho_id = $modMinutaEmpenho->id;
        $modSfOrcEmpenhoDados->ugemitente = $ugemitente->codigo;
        $modSfOrcEmpenhoDados->anoempenho = (int)date('Y');
        $modSfOrcEmpenhoDados->numempenho = (int)substr($modMinutaEmpenho->mensagem_siafi, 6, 6);
        $modSfOrcEmpenhoDados->txtlocalentrega = $modMinutaEmpenho->local_entrega;
        $modSfOrcEmpenhoDados->txtdescricao = $modMinutaEmpenho->descricao;

        $modSfOrcEmpenhoDados->situacao = 'EM PROCESSAMENTO';
        $modSfOrcEmpenhoDados->cpf_user = backpack_user()->cpf;
        $modSfOrcEmpenhoDados->alteracao = true;
        $modSfOrcEmpenhoDados->minutaempenhos_remessa_id = $modMinutaEmpenho->max_remessa;

        $modSfOrcEmpenhoDados->save();
        return $modSfOrcEmpenhoDados;
    }

    public function gravaSfRegistroAlteracao(SfOrcEmpenhoDados $sforcempenhodados)
    {
        $sfRegistroAlteracao = new SfRegistroAlteracao();
        $sfRegistroAlteracao->sforcempenhodado_id = $sforcempenhodados->id;
        $sfRegistroAlteracao->save();
    }

    public function gravaRemessa(MinutaEmpenhoRemessa $modRemessa)
    {
        $situacao = Codigoitem::wherehas('codigo', function ($q) {
            $q->where('descricao', '=', 'Situações Minuta Empenho');
        })
            ->where('descricao', 'EM PROCESSAMENTO')
            ->first();

        $modRemessa->situacao_id = $situacao->id;
        $modRemessa->save();
    }
}
