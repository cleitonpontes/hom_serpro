<?php

namespace App\Http\Traits;

use App\Models\Catmatseritem;
use App\Models\CompraItem;
use App\Models\CompraItemFornecedor;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\CompraItemUnidade;
use App\Models\Fornecedor;
use App\Models\Unidade;
use App\XML\ApiSiasg;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Builder;
use Kris\LaravelFormBuilder\Form;
use FormBuilder;


trait CompraTrait
{

    public function retornaSaldoAtualizado($compraitem_id)
    {
//        dd($compraitem_id);
//        $teste = CompraItemMinutaEmpenho::select(
        return CompraItemMinutaEmpenho::select(
            DB::raw('CASE
                           WHEN compra_item_unidade.quantidade_autorizada - sum(compra_item_minuta_empenho.quantidade) IS NOT NULL
                               THEN compra_item_unidade.quantidade_autorizada - sum(compra_item_minuta_empenho.quantidade)
                           ELSE compra_item_unidade.quantidade_autorizada
                           END AS saldo')
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
            ->groupBy('compra_item_unidade.quantidade_autorizada')
            ->first();
//        ;dd($teste->getBindings(),$teste->toSql());
    }


    public function gravaParametroItensdaCompraSISPP($compraSiasg, $compra): void
    {
//        $unidade_autorizada_id = $this->retornaUnidadeAutorizada($compraSiasg, $compra);
        $unidade_autorizada_id = session('user_ug_id');

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

    public function gravaParametroItensdaCompraSISRP($compraSiasg, $compra): void
    {
//        $unidade_autorizada_id = $this->retornaUnidadeAutorizada($compraSiasg, $compra);
        $unidade_autorizada_id = session('user_ug_id');
        $consultaCompra = new ApiSiasg();

            if (!is_null($compraSiasg->data->linkSisrpCompleto)) {
                foreach ($compraSiasg->data->linkSisrpCompleto as $key => $item) {
                    $dadosItemCompra = ($consultaCompra->consultaCompraByUrl($item->linkSisrpCompleto));
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
        $catmatseritem = Catmatseritem::updateOrCreate(
            ['codigo_siasg' => (int)$codigo_siasg],
            ['descricao' => $item->descricao, 'grupo_id' => $catGrupo[$item->tipo]]
        );
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
                'quantidade_empenhada' => (isset($item->quantidadeEmpenhada)) ? $item->quantidadeEmpenhada : 0
            ]
        );
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


    public function gravaCompraItemUnidadeSisrp($compraitem, $unidade_autorizada_id, $item, $dadosGerenciadoraParticipante, $carona, $dadosFornecedor, $tipoUasg)
    {
        $qtd_autorizada = $dadosGerenciadoraParticipante->quantidadeAAdquirir - $dadosGerenciadoraParticipante->quantidadeAdquirida;
        $fornecedor_id = null;
        if (!is_null($carona)) {
            $carona = (object)$carona;
            $qtd_autorizada = $carona->quantidadeAutorizada;
            $fornecedor = $this->retornaFornecedor((object)$dadosFornecedor[0]);
            $fornecedor_id = $fornecedor->id;
        }

        $compraItemUnidade = CompraItemUnidade::updateOrCreate(
            [
                'compra_item_id' => $compraitem->id,
                'unidade_id' => $unidade_autorizada_id,
                'fornecedor_id' => $fornecedor_id,

            ],
            [
                'quantidade_autorizada' => $qtd_autorizada,
                'quantidade_saldo' => $qtd_autorizada,
                'tipo_uasg' => $tipoUasg,
                'quantidade_adquirir' => $dadosGerenciadoraParticipante->quantidadeAAdquirir,
                'quantidade_adquirida' => $dadosGerenciadoraParticipante->quantidadeAdquirida
            ]
        );

        $saldo = $this->retornaSaldoAtualizado($compraitem->id);
        $compraItemUnidade->quantidade_saldo = $saldo->saldo;
        $compraItemUnidade->save();
    }

    public function retonaFormModal($unidade_id, $minuta_id)
    {
        return FormBuilder::create(InserirItemContratoMinutaForm::class, [
//            'url' => route('api.saldo.inserir.modal'),
//            'method' => 'POST',
            'id' => 'form_modal'

        ])->add('unidade_id', 'hidden', [
            'value' => $unidade_id,
            'attr' => [
                'id' => 'unidade_id'
            ]
        ])->add('minuta_id', 'hidden', [
            'value' => $minuta_id,
            'attr' => [
                'id' => 'minuta_id'
            ]
        ]);
    }

}
