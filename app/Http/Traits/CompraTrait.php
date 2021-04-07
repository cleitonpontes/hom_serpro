<?php

namespace App\Http\Traits;

use App\Models\Catmatseritem;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\CompraItemFornecedor;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\CompraItemUnidade;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\Unidade;
use App\XML\ApiSiasg;
use Illuminate\Support\Facades\DB;
use stdClass;

trait CompraTrait
{

    public function retornaSaldoAtualizado($compraitem_id)
    {
        $unidade_id = session('user_ug_id');
        return CompraItemMinutaEmpenho::select(
            DB::raw("
            coalesce(coalesce(compra_item_unidade.quantidade_autorizada, 0)
                    - (
                    select coalesce(sum(cime.quantidade), 0)
                    from compra_item_minuta_empenho cime
                             join minutaempenhos m on cime.minutaempenho_id = m.id
                             left join contrato_minuta_empenho_pivot cmep on m.id = cmep.minuta_empenho_id
                    where cime.compra_item_id = $compraitem_id
                    and unidade_id = $unidade_id
                      and cmep.contrato_id is null
                )
                    - (
                    select coalesce(sum(quantidade), 0)
                    from contratoitens
                             join compras_item_unidade_contratoitens ciuc on contratoitens.id = ciuc.contratoitem_id
                             join compra_item_unidade on ciuc.compra_item_unidade_id = compra_item_unidade.id
                    where ciuc.compra_item_unidade_id = $compraitem_id
                    and unidade_id = $unidade_id
                )
           , 0) AS saldo
            ")
        )
            ->join(
                'compra_items',
                'compra_items.id',
                '=',
                'compra_item_minuta_empenho.compra_item_id'
            )
            ->rightJoin(
                'compra_item_unidade',
                'compra_item_unidade.compra_item_id',
                '=',
                'compra_items.id'
            )
            ->where('compra_item_unidade.compra_item_id', $compraitem_id)
            ->where('compra_item_unidade.unidade_id', session('user_ug_id'))
            ->groupBy('compra_item_unidade.quantidade_autorizada', 'compra_item_unidade.id')
            ->first();
//        ;dd($teste->getBindings(),$teste->toSql(), $teste->first());
    }

    public function gravaParametroItensdaCompraSISPP($compraSiasg, $compra): void
    {
        $unidade_autorizada_id = session('user_ug_id');
        $this->gravaParametroSISPP($compraSiasg, $compra, $unidade_autorizada_id);
    }

    public function gravaParametroItensdaCompraSISPPCommand($compraSiasg, $compra): void
    {
        $unidade_autorizada_id = $compra->unidade_origem_id;
        $this->gravaParametroSISPP($compraSiasg, $compra, $unidade_autorizada_id);
    }

    private function gravaParametroSISPP($compraSiasg, $compra, $unidade_autorizada_id): void
    {
        if (!is_null($compraSiasg->data->itemCompraSisppDTO)) {
            foreach ($compraSiasg->data->itemCompraSisppDTO as $key => $item) {
                $catmatseritem = $this->gravaCatmatseritem($item);

                $compraitem = $this->updateOrCreateCompraItemSispp($compra, $catmatseritem, $item);

                $fornecedor = $this->retornaFornecedor($item);

                $this->gravaCompraItemFornecedor($compraitem->id, $item, $fornecedor);

                $this->gravaCompraItemUnidadeSispp($compraitem->id, $item, $unidade_autorizada_id, $fornecedor);
            }
        }
    }

    private function gravaParametrosSuprimento(Compra $compra, string $fornecedor_empenho_id): void
    {
        $fornecedor = Fornecedor::find($fornecedor_empenho_id);

        $item = new stdClass;
        $item->tipo = 'S';
        $item->numero = '00001';
        $item->descricaoDetalhada = 'Serviço';
        $item->quantidadeTotal = 1;

        //campos para gravar no CompraItemFornecedor
        $item->classicacao = '';
        $item->situacaoSicaf = '-';
        $item->quantidadeHomologadaVencedor = 0;
        $item->valorUnitario = 1;
        $item->valorTotal = 0;
        $item->quantidadeEmpenhada = 0;

        $this->gravaSuprimento('SERVIÇO PARA SUPRIMENTO DE FUNDOS', $compra, $item, $fornecedor);

        $item->tipo = 'M';
        $item->numero = '00002';
        $item->descricaoDetalhada = 'Material';

        $this->gravaSuprimento('MATERIAL PARA SUPRIMENTO DE FUNDOS', $compra, $item, $fornecedor);
    }

    private function gravaSuprimento($descricao, $compra, $item, $fornecedor): void
    {
        $catmatseritem = Catmatseritem::where('descricao', $descricao)
            ->select('id')->first();

        $compraitem = $this->updateOrCreateCompraItemSispp($compra, $catmatseritem, $item);
        $this->gravaCompraItemFornecedor($compraitem->id, $item, $fornecedor);
        $this->gravaCompraItemUnidadeSuprimento($compraitem->id);
    }

    public function gravaParametroItensdaCompraSISRP($compraSiasg, $compra): void
    {
        $unidade_autorizada_id = session('user_ug_id');
        $this->gravaParametroSISRP($compraSiasg, $compra, $unidade_autorizada_id);
    }

    public function gravaParametroItensdaCompraSISRPCommand($compraSiasg, $compra): void
    {
        $unidade_autorizada_id = $compra->unidade_origem_id;
        $this->gravaParametroSISRP($compraSiasg, $compra, $unidade_autorizada_id);
    }

    private function gravaParametroSISRP($compraSiasg, $compra, $unidade_autorizada_id): void
    {
        $consultaCompra = new ApiSiasg();

        if (!is_null($compraSiasg->data->linkSisrpCompleto)) {
            foreach ($compraSiasg->data->linkSisrpCompleto as $key => $item) {
                $dadosItemCompra = ($consultaCompra->consultaCompraByUrl($item->linkSisrpCompleto));

                if (is_null($dadosItemCompra['data'])) {
                    continue;
                }

                $tipoUasg = (substr($item->linkSisrpCompleto, -1));
                $dadosata = (object)$dadosItemCompra['data']['dadosAta'];
                $gerenciadoraParticipante = (object)$dadosItemCompra['data']['dadosGerenciadoraParticipante'];
                $carona = $dadosItemCompra['data']['dadosCarona'];
                $dadosFornecedor = $dadosItemCompra['data']['dadosFornecedor'];

                $catmatseritem = $this->gravaCatmatseritem($dadosata);

                $modcompraItem = new CompraItem();
                $compraItem = $modcompraItem->updateOrCreateCompraItemSisrp($compra, $catmatseritem, $dadosata);

                foreach ($dadosFornecedor as $key => $itemfornecedor) {
                    $fornecedor = $this->retornaFornecedor((object)$itemfornecedor);

                    $this->gravaCompraItemFornecedor($compraItem->id, (object)$itemfornecedor, $fornecedor);
                }
                $this->gravaCompraItemUnidadeSisrp($compraItem, $unidade_autorizada_id, $item, $gerenciadoraParticipante, $carona, $dadosFornecedor, $tipoUasg);
            }
        }
    }

    public function retornaUnidadeAutorizada($compraSiasg, $compra)
    {
        $SISPP = 1;
        $SISRP = 2;

        $unidade_autorizada_id = null;
        $tipoCompra = $compraSiasg->data->compraSispp->tipoCompra;
        $subrrogada = $compraSiasg->data->compraSispp->subrogada;
        if ($tipoCompra == $SISPP) {
            ($subrrogada <> '000000')
                ? $unidade_autorizada_id = (int)$this->buscaIdUnidade($subrrogada)
                : $unidade_autorizada_id = $compra->unidade_origem_id;
        }
        if ($tipoCompra == $SISRP) {
            ($subrrogada <> '000000')
                ? $unidade_autorizada_id = (int)$this->buscaIdUnidade($subrrogada)
                : $unidade_autorizada_id = $compra->unidade_origem_id;
        }

        return $unidade_autorizada_id;
    }

    public function buscaIdUnidade($uasg)
    {
        return Unidade::where('codigo', $uasg)->first()->id;
    }

    public function gravaCatmatseritem($item)
    {
        $MATERIAL = [149, 194];
        $SERVICO = [150, 195];

        $codigo_siasg = (isset($item->codigo)) ? $item->codigo : $item->codigoItem;
        $tipo = ['S' => $SERVICO[0], 'M' => $MATERIAL[0]];
        $catGrupo = ['S' => $SERVICO[1], 'M' => $MATERIAL[1]];
        if ($item->descricao == "") {
            $catmatseritem = Catmatseritem::updateOrCreate(
                ['codigo_siasg' => (int)$codigo_siasg, 'grupo_id' => (int)$catGrupo[$item->tipo]],
                ['descricao' => $codigo_siasg . " - Descrição não informada pelo serviço.", 'grupo_id' => $catGrupo[$item->tipo]]
            );
        } else {
            $catmatseritem = Catmatseritem::updateOrCreate(
                ['codigo_siasg' => (int)$codigo_siasg, 'grupo_id' => (int)$catGrupo[$item->tipo]],
                ['descricao' => $item->descricao, 'grupo_id' => $catGrupo[$item->tipo]]
            );
        }

        return $catmatseritem;
    }

    public function updateOrCreateCompraItemSispp($compra, $catmatseritem, $item)
    {
        $MATERIAL = [149, 194];
        $SERVICO = [150, 195];
        $tipo = ['S' => $SERVICO[0], 'M' => $MATERIAL[0]];

        $compraitem = CompraItem::updateOrCreate(
            [
                'compra_id' => (int)$compra->id,
                'tipo_item_id' => (int)$tipo[$item->tipo],
                'catmatseritem_id' => (int)$catmatseritem->id,
                'numero' => (string)$item->numero,
            ],
            [
                'descricaodetalhada' => (string)$item->descricaoDetalhada,
                'qtd_total' => $item->quantidadeTotal
            ]
        );
        return $compraitem;
    }

    public function retornaFornecedor($item)
    {
        $fornecedor = new Fornecedor();

        if ($item->niFornecedor === 'ESTRANGEIRO') {
            $cpf_cnpj_idgener =
                mb_strtoupper(preg_replace('/\s/', '_', $item->niFornecedor . '_' . $item->nomeFornecedor), 'UTF-8');

            $retorno = $fornecedor->buscaFornecedorPorNumero($cpf_cnpj_idgener);

            if (is_null($retorno)) {
                $fornecedor->tipo_fornecedor = 'IDGENERICO';
                $fornecedor->cpf_cnpj_idgener = $cpf_cnpj_idgener;
                $fornecedor->nome = $item->nomeFornecedor;
                $fornecedor->save();
                return $fornecedor;
            }
            return $retorno;
        }

        $retorno = $fornecedor->buscaFornecedorPorNumero($item->niFornecedor);

        //TODO UPDATE OR INSERT FORNECEDOR
        if (is_null($retorno)) {
            $fornecedor->tipo_fornecedor = $fornecedor->retornaTipoFornecedor($item->niFornecedor);
            $fornecedor->cpf_cnpj_idgener = $fornecedor->formataCnpjCpf($item->niFornecedor);
            $fornecedor->nome = $item->nomeFornecedor;
            $fornecedor->save();
            return $fornecedor;
        }
        return $retorno;
    }

    public function gravaCompraItemFornecedor($compraitem_id, $item, $fornecedor)
    {
        $qtd_empenhada = (isset($item->quantidadeEmpenhada))
            ? preg_replace('/[^0-9]/', '', $item->quantidadeEmpenhada)
            : 0;

        CompraItemFornecedor::updateOrCreate(
            [
                'compra_item_id' => $compraitem_id,
                'fornecedor_id' => $fornecedor->id
            ],
            [
                'ni_fornecedor' => $fornecedor->cpf_cnpj_idgener,
                'classificacao' => (isset($item->classicacao)) ? $item->classicacao : '',
                'situacao_sicaf' => $item->situacaoSicaf,
                'quantidade_homologada_vencedor' => (isset($item->quantidadeHomologadaVencedor)) ? $item->quantidadeHomologadaVencedor : 0,
                'valor_unitario' => $item->valorUnitario,
                'valor_negociado' => (isset($item->valorTotal)) ? $item->valorTotal : $item->valorNegociado,
                'quantidade_empenhada' => $qtd_empenhada
            ]
        );
    }

    public function gravaCompraItemFornecedorSuprimento($minuta, $fornecedor_id)
    {
        $fornecedor = Fornecedor::find($fornecedor_id);

        foreach ($minuta->compra->compra_item as $compra_item) {
            CompraItemFornecedor::updateOrCreate(
                [
                    'compra_item_id' => $compra_item->id,
                    'fornecedor_id' => $fornecedor->id
                ],
                [
                    'ni_fornecedor' => $fornecedor->cpf_cnpj_idgener,
                    'classificacao' => '',
                    'situacao_sicaf' => '-',
                    'quantidade_homologada_vencedor' => 0,
                    'valor_unitario' => 1,
                    'valor_negociado' => 0,
                    'quantidade_empenhada' => 0
                ]
            );
        }
    }

    public function gravaCompraItemUnidadeSispp($compraitem_id, $item, $unidade_autorizada_id, $fornecedor)
    {
        $compraItemUnidade = CompraItemUnidade::updateOrCreate(
            [
                'compra_item_id' => $compraitem_id,
                'unidade_id' => $unidade_autorizada_id,
                'fornecedor_id' => $fornecedor->id
            ],
            [
                'quantidade_saldo' => $item->quantidadeTotal,
                'quantidade_autorizada' => $item->quantidadeTotal
            ]
        );

        $saldo = $this->retornaSaldoAtualizado($compraitem_id);

        $compraItemUnidade->quantidade_saldo = $saldo->saldo;
        $compraItemUnidade->save();
    }

    public function gravaCompraItemUnidadeSuprimento($compraitem_id): void
    {
        CompraItemUnidade::updateOrCreate(
            [
                'compra_item_id' => $compraitem_id,
                'unidade_id' => session('user_ug_id'),
            ],
            [
                'quantidade_saldo' => 1,
                'quantidade_autorizada' => 1
            ]
        );
    }

    public function gravaCompraItemUnidadeSisrp($compraitem, $unidade_autorizada_id, $item, $dadosGerenciadoraParticipante, $carona, $dadosFornecedor, $tipoUasg)
    {
        $fornecedor_id = null;
        if (!is_null($carona)) {
            $carona = (object)$carona[0];
            $qtd_autorizada = $carona->quantidadeAutorizada;
            $fornecedor = $this->retornaFornecedor((object)$dadosFornecedor[0]);
            $fornecedor_id = $fornecedor->id;
            $quantidadeAAdquirir = $qtd_autorizada;
            $quantidadeAdquirida = 0;
        } else {
            $qtd_autorizada = $dadosGerenciadoraParticipante->quantidadeAAdquirir - $dadosGerenciadoraParticipante->quantidadeAdquirida;
            $quantidadeAAdquirir = $dadosGerenciadoraParticipante->quantidadeAAdquirir;
            $quantidadeAdquirida = $dadosGerenciadoraParticipante->quantidadeAdquirida;
        }

        $compraItemUnidade = CompraItemUnidade::where(
            [
                'compra_item_id' => $compraitem->id,
                'unidade_id' => $unidade_autorizada_id,
                'fornecedor_id' => $fornecedor_id,

            ]
        )->first();

        if (is_null($compraItemUnidade)) {
            $compraItemUnidade = new CompraItemUnidade;
            $compraItemUnidade->compra_item_id = $compraitem->id;
            $compraItemUnidade->unidade_id = $unidade_autorizada_id;
            $compraItemUnidade->fornecedor_id = $fornecedor_id;
        }
        $compraItemUnidade->quantidade_autorizada = $qtd_autorizada;
        $compraItemUnidade->quantidade_saldo = $qtd_autorizada;
        $compraItemUnidade->tipo_uasg = $tipoUasg;
        $compraItemUnidade->quantidade_adquirir = $quantidadeAAdquirir;
        $compraItemUnidade->quantidade_adquirida = $quantidadeAdquirida;
        $compraItemUnidade->save();

        $saldo = $this->retornaSaldoAtualizado($compraitem->id);
        $compraItemUnidade->quantidade_saldo = (isset($saldo->saldo)) ? $saldo->saldo : $qtd_autorizada;
        $compraItemUnidade->save();
    }

    private function setCondicaoFornecedor(
        $minuta,
        $itens,
        string $descricao,
        $fornecedor_id,
        $fornecedor_compra_id
    ) {
        //SE FOR ESTRANGEIRO
        if ($fornecedor_id != $fornecedor_compra_id) {
            $fornecedor_id = $fornecedor_compra_id;
        }
        if ($descricao === 'Suprimento') {
            return $itens->where('compra_item_fornecedor.fornecedor_id', $fornecedor_id);
        }
        $tipo_compra = $minuta->tipo_compra;

        if ($tipo_compra === 'SISPP') {
            return $itens->where('compra_item_unidade.fornecedor_id', $fornecedor_id)
                ->where('compra_item_fornecedor.fornecedor_id', $fornecedor_id);
        }

        if ($tipo_compra === 'SISRP') {
            $tipo_uasg = MinutaEmpenho::join(
                'compras',
                'compras.id',
                '=',
                'minutaempenhos.compra_id'
            )
                ->join('compra_items', 'compra_items.compra_id', '=', 'compras.id')
                ->join('compra_item_unidade', 'compra_item_unidade.compra_item_id', '=', 'compra_items.id')
                ->where('minutaempenhos.id', $minuta->id)
                ->where('compra_item_unidade.unidade_id', $minuta->unidade_id)
                ->where(function ($query) use ($fornecedor_id) {
                    $query->where('compra_item_unidade.fornecedor_id', $fornecedor_id)
                        ->orWhereNull('compra_item_unidade.fornecedor_id');
                })
                ->select('compra_item_unidade.tipo_uasg')
                ->distinct()
                ->first()->tipo_uasg;

            if ($tipo_uasg === 'C') {
                return $itens->where('compra_item_unidade.fornecedor_id', $fornecedor_id)
                    ->where('compra_item_fornecedor.fornecedor_id', $fornecedor_id);
            }

            return $itens->whereNull('compra_item_unidade.fornecedor_id')
                ->where('compra_item_fornecedor.fornecedor_id', $fornecedor_id);
        }
    }

    private function setColunaContratoQuantidade(array $item): string
    {
        return " <input  type='number' max='" . $item['qtd_item'] . "' min='1' " .
            "class='form-control qtd" . $item['contrato_item_id'] . "' " .
            "id='qtd" . $item['contrato_item_id'] . "' " .
            "data-tipo='' name='qtd[]' value='" . $item['quantidade'] . "'   > " .
            "<input  type='hidden' id='quantidade_total" . $item['contrato_item_id'] . "' " .
            "data-tipo='' name='quantidade_total[]' value='" . $item['qtd_item'] . " '> ";
    }

    private function setColunaContratoValorTotal(array $item): string
    {
        return " <input type='text' id='vrtotal" . $item['contrato_item_id'] . "' " .
            "class='form-control col-md-12 valor_total vrtotal" . $item['contrato_item_id'] . "' " .
            "data-qtd_item='" . $item['qtd_item'] . "' name='valor_total[]' value='" . $item['valor'] . "' " .
            "data-contrato_item_id='" . $item['contrato_item_id'] . "' " .
            "data-valor_unitario='" . $item['valorunitario'] . "' " .
            "onkeyup='calculaQuantidade(this)' >";
    }

    private function setColunaSuprimentoQuantidade(array $item): string
    {
        return " <input  type='number' max='" . $item['qtd_item'] . "' min='1' " .
            "class='form-control qtd" . $item['compra_item_id'] . "' id='qtd" . $item['compra_item_id'] . "' " .
            "data-tipo='' name='qtd[]' value='" . $item['quantidade'] . "' readonly  > "
            . " <input  type='hidden' id='quantidade_total" . $item['compra_item_id']
            . "' data-tipo='' name='quantidade_total[]' value='"
            . $item['qtd_item'] . " readonly'> ";
    }

    private function setColunaSuprimentoValorTotal(array $item): string
    {
        return " <input type='text' id='vrtotal" . $item['compra_item_id'] . "' " .
            "class='form-control col-md-12 valor_total vrtotal" . $item['compra_item_id'] . "' " .
            "data-qtd_item='" . $item['qtd_item'] . "' name='valor_total[]' value='" . $item['valor'] . "' " .
            "data-compra_item_id='" . $item['compra_item_id'] . "' " .
            "data-valor_unitario='" . $item['valorunitario'] . "' " .
            "onkeyup='calculaQuantidade(this)' >";
    }

    private function setColunaCompraSisrpQuantidade(array $item): string
    {
        return " <input type='number' max='" . $item['qtd_item'] . "' min='1' " .
            "id='qtd" . $item['compra_item_id'] . "' " .
            "data-compra_item_id='" . $item['compra_item_id'] . "' " .
            "data-valor_unitario='" . $item['valorunitario'] . "' name='qtd[]' " .
            "class='form-control qtd' value='" . $item['quantidade'] . "' > " .
            "<input  type='hidden' id='quantidade_total" . $item['compra_item_id'] . "' " .
            "data-tipo='' name='quantidade_total[]' value='" . $item['qtd_item'] . "'> ";
    }

    private function setColunaCompraSisrpValorTotal(array $item): string
    {
        return " <input  type='text' class='form-control valor_total vrtotal" . $item['compra_item_id'] . "' " .
            "id='vrtotal" . $item['compra_item_id'] . "' " .
            "data-tipo='' name='valor_total[]' value='" . $item['valor'] . "' disabled > ";
    }

    private function setColunaCompraSisppMaterialQuantidade(array $item): string
    {
        return " <input type='number' max='" . $item['qtd_item'] . "' min='1' " .
            "id='qtd" . $item['compra_item_id'] . "' data-compra_item_id='" . $item['compra_item_id'] . "' " .
            "data-valor_unitario='" . $item['valorunitario'] . "' name='qtd[]' " .
            "class='form-control qtd' value='" . $item['quantidade'] . "' > " .
            "<input  type='hidden' id='quantidade_total" . $item['compra_item_id'] . "' " .
            "data-tipo='' name='quantidade_total[]' value='" . $item['qtd_item'] . "'> ";
    }

    private function setColunaCompraSisppMaterialValorTotal(array $item): string
    {
        return " <input  type='text' class='form-control valor_total vrtotal" . $item['compra_item_id'] . "' " .
            "id='vrtotal" . $item['compra_item_id'] . "' " .
            "data-tipo='' name='valor_total[]' value='" . $item['valor'] . "' disabled > ";
    }

    private function setColunaCompraSisppServicoQuantidade(array $item): string
    {
        return " <input  type='number' max='" . $item['qtd_item'] . "' min='1' " .
            "class='form-control qtd" . $item['compra_item_id'] . "' " .
            "id='qtd" . $item['compra_item_id'] . "' " .
            "data-tipo='' name='qtd[]' value='" . $item['quantidade'] . "' readonly> " .
            "<input type='hidden' id='quantidade_total" . $item['compra_item_id'] . "' " .
            "data-tipo='' name='quantidade_total[]' value='" . $item['qtd_item'] . " '> ";
    }

    private function setColunaCompraSisppServicoValorTotal(array $item): string
    {
        return " <input type='text' " .
            "class='form-control col-md-12 valor_total vrtotal" . $item['compra_item_id'] . "' " .
            "id='vrtotal" . $item['compra_item_id'] . "' data-qtd_item='" . $item['qtd_item'] . "' " .
            "name='valor_total[]' value='" . $item['valor'] . "' " .
            "data-compra_item_id='" . $item['compra_item_id'] . "' " .
            "data-valor_unitario='" . $item['valorunitario'] . "' " .
            "onkeyup='calculaQuantidade(this)' >";
    }
}
