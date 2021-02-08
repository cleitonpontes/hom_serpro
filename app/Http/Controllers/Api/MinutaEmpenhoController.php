<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\BuscaCodigoItens;
use App\Http\Traits\Formatador;
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
use App\XML\Execsiafi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Route;
use App\Http\Controllers\Controller;
use App\Http\Traits\CompraTrait;

class MinutaEmpenhoController extends Controller
{

    use CompraTrait;
    use BuscaCodigoItens;
    use Formatador;

    public function populaTabelasSiafi(Request $request): array
    {

        $retorno['resultado'] = false;
        $minuta_id = Route::current()->parameter('minuta_id');

        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
        $modSaldoContabil = SaldoContabil::find($modMinutaEmpenho->saldo_contabil_id);
        $modRemessa = MinutaEmpenhoRemessa::where('minutaempenho_id', $minuta_id)->first();

        DB::beginTransaction();
        try {
            $this->removeSfOrcEmpenhoDadosErroAndamento($modMinutaEmpenho, $modRemessa);

            $sforcempenhodados = $this->gravaSfOrcEmpenhoDados($modMinutaEmpenho);

            $this->gravaSfCelulaOrcamentaria($sforcempenhodados, $modSaldoContabil);

            if ($modMinutaEmpenho->passivo_anterior) {
                $this->gravaSfPassivoAnterior($sforcempenhodados, $modMinutaEmpenho, $modRemessa);
            }

            $this->gravaSfItensEmpenho($modMinutaEmpenho, $sforcempenhodados, $modRemessa->id);

            $this->gravaMinuta($modMinutaEmpenho);

            $this->gravaRemessaOriginal($modRemessa);

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
        $ugemitente = Unidade::find($modMinutaEmpenho->saldo_contabil->unidade_id);
        $codfavorecido = (str_replace('-', '', str_replace('/', '', str_replace('.', '', $favorecido->cpf_cnpj_idgener))));
        $informacao_complementar = $modMinutaEmpenho->informacao_complementar;
        if ($modMinutaEmpenho->numero_cipi) {
            $informacao_complementar = $informacao_complementar . ' - CIPI: ' . $modMinutaEmpenho->numero_cipi;
        }

        $modSfOrcEmpenhoDados->minutaempenho_id = $modMinutaEmpenho->id;
        $modSfOrcEmpenhoDados->ugemitente = $ugemitente->codigo;
        $modSfOrcEmpenhoDados->anoempenho = (int)config('app.ano_minuta_empenho');
        $modSfOrcEmpenhoDados->tipoempenho = $tipoEmpenho->descres;
        $modSfOrcEmpenhoDados->numempenho = (!is_null($modMinutaEmpenho->numero_empenho_sequencial)) ? $modMinutaEmpenho->numero_empenho_sequencial : null;
        $modSfOrcEmpenhoDados->dtemis = $modMinutaEmpenho->data_emissao;
        $modSfOrcEmpenhoDados->txtprocesso = (!is_null($modMinutaEmpenho->processo)) ? $modMinutaEmpenho->processo : null;
        $modSfOrcEmpenhoDados->vlrtaxacambio = (!is_null($modMinutaEmpenho->taxa_cambio)) ? $modMinutaEmpenho->taxa_cambio : null;
        $modSfOrcEmpenhoDados->vlrempenho = (!is_null($modMinutaEmpenho->valor_total)) ? $modMinutaEmpenho->valor_total : null;
        $modSfOrcEmpenhoDados->codfavorecido = $codfavorecido;
        $modSfOrcEmpenhoDados->codamparolegal = $amparoLegal->codigo;
        $modSfOrcEmpenhoDados->txtinfocompl = $informacao_complementar;
        $modSfOrcEmpenhoDados->txtlocalentrega = $modMinutaEmpenho->local_entrega;
        $modSfOrcEmpenhoDados->txtdescricao = $modMinutaEmpenho->descricao;
        $modSfOrcEmpenhoDados->situacao = 'EM PROCESSAMENTO';
        $modSfOrcEmpenhoDados->cpf_user = backpack_user()->cpf;
        $modSfOrcEmpenhoDados->minutaempenhos_remessa_id = $modMinutaEmpenho->max_remessa;
        $execsiafi = new Execsiafi();
        $nonce = $execsiafi->createNonce($ugemitente->codigo, $modMinutaEmpenho->id, 'ORCAMENTARIO');
        $modSfOrcEmpenhoDados->sfnonce_id = $nonce;
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

    public function gravaSfPassivoAnterior(
        SfOrcEmpenhoDados $sforcempenhodados,
        MinutaEmpenho $modMinutaEmpenho,
        MinutaEmpenhoRemessa $modRemessa
    ) {
        $modSfPassivoAnterior = new SfPassivoAnterior();
        $modSfPassivoAnterior->sforcempenhodado_id = $sforcempenhodados->id;
        $modSfPassivoAnterior->codcontacontabil = $modMinutaEmpenho->conta_contabil_passivo_anterior;
        $modSfPassivoAnterior->save();

        $this->gravaSfPassivoPermanente($modSfPassivoAnterior, $modMinutaEmpenho, $modRemessa);

        return $modSfPassivoAnterior;
    }

    public function gravaSfPassivoPermanente(
        SfPassivoAnterior $sfpassivoanterior,
        MinutaEmpenho $modMinutaEmpenho,
        MinutaEmpenhoRemessa $modRemessa
    ) {
        $modCCPassivoAnterior = ContaCorrentePassivoAnterior::where('minutaempenho_id', $modMinutaEmpenho->id)
            ->where('minutaempenhos_remessa_id', $modRemessa->id)
            ->get();

        foreach ($modCCPassivoAnterior as $key => $conta) {
            $modSfPassivoPermanente = new SfPassivoPermanente();
            $modSfPassivoPermanente->sfpassivoanterior_id = $sfpassivoanterior->id;
            $modSfPassivoPermanente->contacorrente = "P" . $conta->conta_corrente;
            $modSfPassivoPermanente->vlrrelacionado = $conta->valor;
            $modSfPassivoPermanente->save();
        }
        return $modSfPassivoPermanente;
    }

    public function gravaSfItensEmpenho(
        MinutaEmpenho $modMinutaEmpenho,
        SfOrcEmpenhoDados $sforcempenhodados,
        $remessa_id = 0
    ) {

        $tipo = $modMinutaEmpenho->tipo_empenhopor->descricao;

        $itens = $this->getItens($tipo, $modMinutaEmpenho->id, $remessa_id);

        foreach ($itens as $key => $item) {
            if ($item->operacao !== 'NENHUMA') {
                $descricao = $this->getDescItem($item, $tipo);

                $modSfItemEmpenho = new SfItemEmpenho();
                $modSubelemento = Naturezasubitem::find($item->subelemento_id);
                $modSfItemEmpenho->sforcempenhodado_id = $sforcempenhodados->id;
                $modSfItemEmpenho->numseqitem = $key + 1;
                $modSfItemEmpenho->codsubelemento = $modSubelemento->codigo;
                $modSfItemEmpenho->descricao = $descricao;
                $modSfItemEmpenho->save();

                $this->gravaSfOperacaoItemEmpenho($modSfItemEmpenho, $item);
            }
        }
    }

    public function gravaSfOperacaoItemEmpenho(SfItemEmpenho $modSfItemEmpenho, $item)
    {
        $vlroperacao = ($item->valor > 0) ? $item->valor : $item->valor * -1;
        $quantidade = ($item->valor < 0) ? $item->quantidade * -1 : $item->quantidade;

        $modSfOpItemEmpenho = new SfOperacaoItemEmpenho();
        $modSfOpItemEmpenho->sfitemempenho_id = $modSfItemEmpenho->id;
        $modSfOpItemEmpenho->tipooperacaoitemempenho = $item->operacao_descres; // Incluir nas tabelas codigo (OPERACAOITEMEMPENHO) e codigoitens (INCLUSÃO - REFORCO - ANULACAO - CANCELAMENTO)
        $modSfOpItemEmpenho->quantidade = $quantidade;
        $modSfOpItemEmpenho->vlrunitario = ($item->valor / $item->quantidade);
        $modSfOpItemEmpenho->vlroperacao = $vlroperacao;
        $modSfOpItemEmpenho->save();
//        dd($modSfOpItemEmpenho);
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
        $situacao_id = $this->retornaIdCodigoItem('Situações Minuta Empenho', 'EM ANDAMENTO');

        $tipo = $this->retornaIdCodigoItem('Tipo Empenho Por', 'Compra');

        DB::beginTransaction();
        try {
            $this->atualizaSaldoCompraItemUnidade($modMinutaEmpenho);
            $novoEmpenho = new MinutaEmpenho();
            $novoEmpenho->unidade_id = $modMinutaEmpenho->unidade_id;
            $novoEmpenho->compra_id = $modMinutaEmpenho->compra_id;
            $novoEmpenho->informacao_complementar = $modMinutaEmpenho->informacao_complementar;
            $novoEmpenho->situacao_id = $situacao_id;//em andamento
            $novoEmpenho->tipo_empenhopor_id = $modMinutaEmpenho->tipo_empenhopor_id;
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

        $arr_contrato_minuta_empenho_pivot = ContratoMinutaEmpenho::select('minuta_empenho_id');

        if (!empty($form['contrato_id'])) {
            $arr_contrato_minuta_empenho_pivot->where('contrato_id', '<>', $form['contrato_id']);
        }

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
                ->whereNotIn('minutaempenhos.id', $arr_contrato_minuta_empenho_pivot->get()->toArray());
        }

        if ($search_term) {
            $options->where('minutaempenhos.numero_empenho_sequencial', 'LIKE', '%' . $search_term . '%');
        }

        return $options->paginate(10);
    }

    private function getItens($tipo, $minuta_id, $remessa_id)
    {
        if ($tipo === 'Contrato') {
            return ContratoItemMinutaEmpenho::where('minutaempenho_id', $minuta_id)
                ->where('minutaempenhos_remessa_id', $remessa_id)
                ->get();
        }

        return CompraItemMinutaEmpenho::where('minutaempenho_id', $minuta_id)
            ->where('minutaempenhos_remessa_id', $remessa_id)
            ->get();
    }

    private function getDescItem($item, $tipo)
    {
        if ($tipo === 'Contrato') {
            $contrato_item = $item->contrato_item;
            $desc = $contrato_item->descricao_complementar;

            $descricao = (!is_null($desc))
                ? $desc
                : $contrato_item->item->descricao;

            return (strlen($descricao) < 1248) ? $descricao : substr($descricao, 0, 1248);
        }

        $descricao = '';
        $modCompraItem = CompraItem::find($item->compra_item_id);
        $modcatMatSerItem = Catmatseritem::find($modCompraItem->catmatseritem_id);

        (!empty($modCompraItem->descricaodetalhada))
            ? $descricao = $modCompraItem->descricaodetalhada
            : $descricao = $modcatMatSerItem->descricao;

        return (strlen($descricao) < 1248) ? $descricao : substr($descricao, 0, 1248);
    }

    public function populaTabelasSiafiAlteracao(): array
    {
        $retorno['resultado'] = false;
        $minuta_id = Route::current()->parameter('minuta_id');
        $remessa_id = Route::current()->parameter('remessa');

        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
        $modRemessa = MinutaEmpenhoRemessa::find($remessa_id);

        $this->removeSfOrcEmpenhoDadosErroAndamento($modMinutaEmpenho, $modRemessa);

        DB::beginTransaction();
        try {
            $sforcempenhodadosalt = $this->gravaSfOrcEmpenhoDadosAlt($modMinutaEmpenho, $modRemessa);

            if ($modMinutaEmpenho->passivo_anterior) {
                $this->gravaSfPassivoAnterior($sforcempenhodadosalt, $modMinutaEmpenho, $modRemessa);
            }

            $this->gravaSfItensEmpenho($modMinutaEmpenho, $sforcempenhodadosalt, $remessa_id);

            $txt_motivo = $this->getTxtMotivo($modMinutaEmpenho);

            $this->gravaSfRegistroAlteracao($sforcempenhodadosalt, $modMinutaEmpenho->data_emissao, $txt_motivo);

            $this->gravaRemessa($modRemessa);

            DB::commit();
            $retorno['resultado'] = true;
        } catch (Exception $exc) {
            DB::rollback();
//            dd($exc);
        }

        return $retorno;
    }

    public function gravaSfOrcEmpenhoDadosAlt(MinutaEmpenho $modMinutaEmpenho, MinutaEmpenhoRemessa $modRemessa)
    {
        $modSfOrcEmpenhoDados = new SfOrcEmpenhoDados();

        $ugemitente = Unidade::find($modMinutaEmpenho->saldo_contabil->unidade_id);

        $modSfOrcEmpenhoDados->minutaempenho_id = $modMinutaEmpenho->id;
        $modSfOrcEmpenhoDados->ugemitente = $ugemitente->codigo;
        $modSfOrcEmpenhoDados->anoempenho = (int)config('app.ano_minuta_empenho');
        $modSfOrcEmpenhoDados->numempenho = (int)substr($modMinutaEmpenho->mensagem_siafi, 6, 6);
        $modSfOrcEmpenhoDados->txtlocalentrega = $modMinutaEmpenho->local_entrega;
        $modSfOrcEmpenhoDados->txtdescricao = $modMinutaEmpenho->descricao;

        $modSfOrcEmpenhoDados->situacao = 'EM PROCESSAMENTO';
        $modSfOrcEmpenhoDados->cpf_user = backpack_user()->cpf;
        $modSfOrcEmpenhoDados->alteracao = true;
        $modSfOrcEmpenhoDados->minutaempenhos_remessa_id = $modRemessa->id;
        $execsiafi = new Execsiafi();
        $nonce = $execsiafi->createNonce($ugemitente->codigo, $modMinutaEmpenho->id, 'ORCAMENTARIO');
        $modSfOrcEmpenhoDados->sfnonce_id = $nonce;
        $modSfOrcEmpenhoDados->save();

        return $modSfOrcEmpenhoDados;
    }

    public function gravaSfRegistroAlteracao(SfOrcEmpenhoDados $sforcempenhodados, string $dtemis, string $txtmotivo)
    {
        $sfRegistroAlteracao = new SfRegistroAlteracao();
        $sfRegistroAlteracao->sforcempenhodado_id = $sforcempenhodados->id;
        $sfRegistroAlteracao->dtemis = date('Y-m-d');
        $sfRegistroAlteracao->txtmotivo = $txtmotivo;
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
        $modRemessa->etapa = 3;
        $modRemessa->save();
    }

    public function gravaRemessaOriginal(MinutaEmpenhoRemessa $modRemessa)
    {
        $situacao = Codigoitem::wherehas('codigo', function ($q) {
            $q->where('descricao', '=', 'Situações Minuta Empenho');
        })
            ->where('descricao', 'EM PROCESSAMENTO')
            ->first();
        $modRemessa->situacao_id = $situacao->id;
        $modRemessa->save();
    }

    private function getTxtMotivo($modMinutaEmpenho)
    {

        $tipo = $modMinutaEmpenho->empenho_por;

        if ($tipo === 'Compra' || $tipo === 'Suprimento') {
            $data_emissao = Carbon::createFromFormat('Y-m-d', $modMinutaEmpenho->data_emissao)->format('d/m/Y');

            return "REGISTRO DE ANULAÇÃO/REFORÇO/CANCELAMENTO DO EMPENHO N° $modMinutaEmpenho->mensagem_siafi " .
                "EMITIDO EM $data_emissao COMPRA: $modMinutaEmpenho->informacao_complementar.";
        }

        if ($tipo === 'Contrato') {
            DB::enableQueryLog();
            $data_emissao = Carbon::createFromFormat('Y-m-d', $modMinutaEmpenho->data_emissao)->format('d/m/Y');

            $ugOrigemContrato = $modMinutaEmpenho->contrato_vinculado->unidadeorigem->codigo;
            $tipoContrato = $modMinutaEmpenho->contrato_vinculado->tipo->descres;
            $numeroAno = implode('', explode('/', $modMinutaEmpenho->contrato_vinculado->numero));

//           dd(DB::getQueryLog());

            return "REGISTRO DE ANULAÇÃO/REFORÇO/CANCELAMENTO DO EMPENHO N° $modMinutaEmpenho->mensagem_siafi " .
                "EMITIDO EM $data_emissao CONTRATO: $ugOrigemContrato$tipoContrato$numeroAno.";
        }
    }

    public function removeSfOrcEmpenhoDadosErroAndamento(
        MinutaEmpenho $modMinutaEmpenho,
        MinutaEmpenhoRemessa $modRemessa
    ) {
        return SfOrcEmpenhoDados::where('minutaempenho_id', $modMinutaEmpenho->id)
            ->where('minutaempenhos_remessa_id', $modRemessa->id)
            ->whereIn('situacao', ['ERRO', 'EM ANDAMENTO'])->forceDelete();
    }


    public function atualizaCreditoOrcamentario(Request $request)
    {
        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
        $modSaldoContabil = SaldoContabil::find($modMinutaEmpenho->saldo_contabil_id);
        return $modSaldoContabil->saldo;
    }

}
