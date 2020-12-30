<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\BuscaCodigoItens;
use App\Models\AmparoLegal;
use App\Models\Codigoitem;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\CompraItemUnidade;
use App\Models\ContaCorrentePassivoAnterior;
use App\Models\ContratoItemMinutaEmpenho;
use App\Models\ContratoMinutaEmpenho;
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
use App\Models\Catmatseritem;
use App\XML\ApiSiasg;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Route;
use App\Http\Controllers\Controller;
use App\Http\Traits\CompraTrait;

class MinutaEmpenhoController extends Controller
{

    use CompraTrait;
    use BuscaCodigoItens;

    public function populaTabelasSiafi(Request $request)
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
        }

        return $retorno;
    }

    public function gravaSfOrcEmpenhoDados(MinutaEmpenho $modMinutaEmpenho)
    {
        $modSfOrcEmpenhoDados = new SfOrcEmpenhoDados();
        $tipoEmpenho = Codigoitem::find($modMinutaEmpenho->tipo_empenho_id);
        $favorecido = Fornecedor::find($modMinutaEmpenho->fornecedor_empenho_id);
        $amparoLegal = AmparoLegal::find($modMinutaEmpenho->amparo_legal_id);
        $ugemitente = Unidade::find($modMinutaEmpenho->saldo_contabil->unidade_id);
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

    public function gravaSfItensEmpenho(MinutaEmpenho $modMinutaEmpenho, SfOrcEmpenhoDados $sforcempenhodados)
    {

//        dd($modMinutaEmpenho, $sforcempenhodados);
        $tipo_contrato = $this->retornaIdCodigoItem('Tipo Empenho Por', 'Contrato');
        if ($modMinutaEmpenho->tipo_empenhopor_id == $tipo_contrato) {
            $modItem = ContratoItemMinutaEmpenho::where('minutaempenho_id', $modMinutaEmpenho->id)->get();
        }
        $tipo_compra = $this->retornaIdCodigoItem('Tipo Empenho Por', 'Compra');
        if ($modMinutaEmpenho->tipo_empenhopor_id == $tipo_compra) {
            $modItem = CompraItemMinutaEmpenho::where('minutaempenho_id', $modMinutaEmpenho->id)->get();
        }
//        dd('fora');
//        $modItem = CompraItemMinutaEmpenho::where('minutaempenho_id', $modMinutaEmpenho->id)->get();
//        dd($modItem);

        foreach ($modItem as $key => $item) {
            dump('232323');
//            dump($item);
//            dump($item->subelemento_id);
            $modSfItemEmpenho = new SfItemEmpenho();
            $modSubelemento = Naturezasubitem::find($item->subelemento_id);
            $modSfItemEmpenho->sforcempenhodado_id = $sforcempenhodados->id;
            $modSfItemEmpenho->numseqitem = $key + 1;
//            dd('forea',$modSubelemento);
            dump('forea');
            $modSfItemEmpenho->codsubelemento = $modSubelemento->codigo;
            $modSfItemEmpenho->descricao = $this->buscaDescricao($item,$modMinutaEmpenho->tipo_empenhopor_id );
            dump('fim');

            $modSfItemEmpenho->save();

            $this->gravaSfOperacaoItemEmpenho($modSfItemEmpenho, $item);
        }
        dd(112233);
    }

    public function gravaSfOperacaoItemEmpenho(SfItemEmpenho $modSfItemEmpenho, CompraItemMinutaEmpenho $item)
    {
        $modSfOpItemEmpenho = new SfOperacaoItemEmpenho();
        $modSfOpItemEmpenho->sfitemempenho_id = $modSfItemEmpenho->id;
        $modSfOpItemEmpenho->tipooperacaoitemempenho = 'INCLUSAO'; // Incluir nas tabelas codigo (OPERACAOITEMEMPENHO) e codigoitens (INCLUSÃO - REFORCO - ANULACAO - CANCELAMENTO)
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

    public function buscaDescricao($compra_id, $tipo_empenhopor_id)
    {
        dd($compra_id);
        $item->compra_item_id;
        dd(123,$compra_id);
        $compra_id->compra_item_id;
        $descricao = '';
        $modCompraItem = CompraItem::find($compra_id);
        $modcatMatSerItem = Catmatseritem::find($modCompraItem->catmatseritem_id);
        (!empty($modCompraItem->descricaodetalhada))
            ? $descricao = $modCompraItem->descricaodetalhada
            : $descricao = $modcatMatSerItem->descricao;
        return (strlen($descricao) < 1248) ? $descricao : substr($descricao, 0, 1248);
    }

    /**
     * Método para buscar as minutas de empenho de acordo com uasg da pessoa logada
     * e o id do fornecedor passado na request utilizado no formulário de contrato.
     *
     * @return  array $minutaEmpenho
     */

    public function minutaempenhoparacontrato(Request $request)
    {
        $search_term = $request->input('q');

        $form = collect($request->input('form'))->pluck('value', 'name');
        $arr_contrato_minuta_empenho_pivot = ContratoMinutaEmpenho::select('minuta_empenho_id')->get()->toArray();
        $situacao = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Situações Minuta Empenho');
        })
            ->where('descricao', 'EMPENHO EMITIDO')
            ->select('codigoitens.id')->first();

        $options = MinutaEmpenho::query();

        if (!$form['fornecedor_id']) {
            return [];
        }

        if ($form['fornecedor_id']) {
            $options
                ->select(['minutaempenhos.id',
                    DB::raw("CONCAT(minutaempenhos.mensagem_siafi, ' - ', to_char(data_emissao, 'DD/MM/YYYY')  )
                             as nome_minuta_empenho")])
                ->distinct('minutaempenhos.id')
                ->join('compras', 'minutaempenhos.compra_id', '=', 'compras.id')
                ->join('codigoitens', 'codigoitens.id', '=', 'compras.modalidade_id')
                ->join('unidades', 'minutaempenhos.unidade_id', '=', 'unidades.id')
                ->leftJoin('contrato_minuta_empenho_pivot', 'minutaempenhos.id', '=', 'contrato_minuta_empenho_pivot.minuta_empenho_id')
                ->where('minutaempenhos.fornecedor_compra_id', $form['fornecedor_id'])
                ->where('minutaempenhos.unidade_id', '=', session()->get('user_ug_id'))
                ->where('minutaempenhos.situacao_id', '=', $situacao->id)
                ->whereNotIn('minutaempenhos.id', $arr_contrato_minuta_empenho_pivot);
        }

        if ($search_term) {
            $options->where('minutaempenhos.numero_empenho_sequencial', 'LIKE', '%' . $search_term . '%');
        }

        return $options->paginate(10);
    }
}
