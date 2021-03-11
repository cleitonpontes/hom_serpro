<?php

namespace App\Console\Commands;

use App\Models\CompraItem;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\Contratoitem;
use App\Models\ContratoItemMinutaEmpenho;
use App\Models\MinutaEmpenho;
use App\Models\MinutaEmpenhoRemessa;
use App\Models\SfItemEmpenho;
use Illuminate\Console\Command;
use App\Models\Contrato;
use App\XML\ApiSiasg;
use App\Models\Siasgcompra;
use App\Models\Unidade;
use App\Models\Codigoitem;
use DB;

class SanitizarSequencial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SanitizarSequencial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reordenar o campo numseq';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->atualizaNumSeqRemessaOriginalCompra();
            $this->atualizaNumSeqRemessaAlteracaoCompra();
            $this->atualizaNumSeqRemessaFaltanteCompra();
            $this->atualizaNumSeqRemessaOriginalContrato();
            $this->atualizaNumSeqRemessaAlteracaoContratos();
            $this->atualizaNumSeqRemessaFaltanteContrato();

        } catch (Exception $e) {
            throw new Exception("Error ao Processar a Requisição", $e->getMessage());
        }

    }

    private function atualizaNumSeqRemessaFaltanteCompra()
    {
        $remessas = MinutaEmpenhoRemessa::join('compra_item_minuta_empenho', 'minutaempenhos_remessa.id', '=', 'compra_item_minuta_empenho.minutaempenhos_remessa_id')
            ->select('minutaempenhos_remessa.id')
            ->distinct()
            ->whereNull('compra_item_minuta_empenho.numseq')
            ->get();

        foreach ($remessas as $remessa) {
            $itens = CompraItemMinutaEmpenho::where('minutaempenhos_remessa_id', $remessa->id)
                ->whereNull('numseq')
                ->orderBy('id', 'asc')
                ->get();
            $i = 1;
            foreach ($itens as $item) {
                $item->numseq = $i;
                $item->save();
                $i++;
            }
        }
        echo "Terminou Remessas de Faltantes de Compra! ";
    }

    private function atualizaNumSeqRemessaFaltanteContrato()
    {
        $remessas = MinutaEmpenhoRemessa::join('contrato_item_minuta_empenho', 'minutaempenhos_remessa.id', '=', 'contrato_item_minuta_empenho.minutaempenhos_remessa_id')
            ->select('minutaempenhos_remessa.id')
            ->distinct()
            ->whereNull('contrato_item_minuta_empenho.numseq')
            ->get();

        foreach ($remessas as $remessa) {
            $itens = ContratoItemMinutaEmpenho::where('minutaempenhos_remessa_id', $remessa->id)
                ->whereNull('numseq')
                ->orderBy('id', 'asc')
                ->get();
            $i = 1;
            foreach ($itens as $item) {
                $item->numseq = $i;
                $item->save();
                $i++;
            }
        }
        echo "Terminou Remessas de Faltantes de Contrato! ";
    }

    private function atualizaNumSeqRemessaOriginalCompra()
    {
        $minutas = $this->buscaMinutasOriginaisCompra();

        $sfitems = SfItemEmpenho::join('sfoperacaoitemempenho', 'sfoperacaoitemempenho.sfitemempenho_id', '=', 'sfitemempenho.id')
            ->join('sforcempenhodados', 'sforcempenhodados.id', '=', 'sfitemempenho.sforcempenhodado_id')
            ->join('minutaempenhos', 'minutaempenhos.id', '=', 'sforcempenhodados.minutaempenho_id')
            ->join('minutaempenhos_remessa', function ($join) {
                $join->on('minutaempenhos_remessa.minutaempenho_id', '=', 'minutaempenhos.id');
                $join->on('sforcempenhodados.minutaempenhos_remessa_id', '=', 'minutaempenhos_remessa.id');
            })
            ->where('minutaempenhos_remessa.remessa', '=', 0)
            ->where('minutaempenhos.tipo_empenhopor_id', 255)
            ->whereNotNull('sfitemempenho.numseqitem')
            ->whereNotIn('minutaempenhos.id', $minutas)
            ->select(
                'sfitemempenho.numseqitem',
                'minutaempenhos.id',
                'sfoperacaoitemempenho.quantidade',
                'sfoperacaoitemempenho.vlroperacao'
            )->distinct()
            ->get();

        $this->rodaRemessaOriginal($sfitems);

    }

    private function atualizaNumSeqRemessaAlteracaoCompra()
    {

        $cime = CompraItemMinutaEmpenho::whereNotNull('compra_item_minuta_empenho.numseq')
            ->select(
                'compra_item_minuta_empenho.id',
                'compra_item_minuta_empenho.compra_item_id',
                'compra_item_minuta_empenho.numseq',
                'compra_item_minuta_empenho.minutaempenho_id'
            )->get();

        $this->rodaRemessaAlteracao($cime);
    }


    private function atualizaNumSeqRemessaAlteracaoContratos()
    {

        $cime = ContratoItemMinutaEmpenho::whereNotNull('contrato_item_minuta_empenho.numseq')
            ->select(
                'contrato_item_minuta_empenho.id',
                'contrato_item_minuta_empenho.contrato_item_id',
                'contrato_item_minuta_empenho.numseq',
                'contrato_item_minuta_empenho.minutaempenho_id'
            )->get();

        $this->rodaRemessaAlteracaoContrato($cime);
    }

    private function atualizaNumSeqRemessaOriginalContrato()
    {
        $minutas = $this->buscaMinutasOriginaisContrato();

        $sfitems = SfItemEmpenho::join('sfoperacaoitemempenho', 'sfoperacaoitemempenho.sfitemempenho_id', '=', 'sfitemempenho.id')
            ->join('sforcempenhodados', 'sforcempenhodados.id', '=', 'sfitemempenho.sforcempenhodado_id')
            ->join('minutaempenhos', 'minutaempenhos.id', '=', 'sforcempenhodados.minutaempenho_id')
            ->join('minutaempenhos_remessa', function ($join) {
                $join->on('minutaempenhos_remessa.minutaempenho_id', '=', 'minutaempenhos.id');
                $join->on('sforcempenhodados.minutaempenhos_remessa_id', '=', 'minutaempenhos_remessa.id');
            })
            ->where('minutaempenhos_remessa.remessa', '=', 0)
            ->where('minutaempenhos.tipo_empenhopor_id', 256)
            ->whereNotNull('sfitemempenho.numseqitem')
            ->whereNotIn('minutaempenhos.id', $minutas)
            ->select(
                'sfitemempenho.numseqitem',
                'minutaempenhos.id',
                'sfoperacaoitemempenho.quantidade',
                'sfoperacaoitemempenho.vlroperacao'
            )->distinct()
            ->get();

        $this->rodaRemessaOriginalContrato($sfitems);

    }


    private function buscaMinutasOriginaisCompra()
    {

        $minutas = CompraItem::
        join('compra_item_minuta_empenho', 'compra_item_minuta_empenho.compra_item_id', '=', 'compra_items.id')
            ->join('minutaempenhos', 'minutaempenhos.id', '=', 'compra_item_minuta_empenho.minutaempenho_id')
            ->join('minutaempenhos_remessa', function ($join) {
                $join->on('minutaempenhos_remessa.minutaempenho_id', '=', 'minutaempenhos.id');
                $join->on('compra_item_minuta_empenho.minutaempenhos_remessa_id', '=', 'minutaempenhos_remessa.id');
            })
            ->where('minutaempenhos_remessa.remessa', '=', 0)
            ->where('minutaempenhos.tipo_empenhopor_id', 255)
            ->groupby(
                'minutaempenhos.id',
                'minutaempenhos_remessa.id',
                'compra_items.compra_id',
                'compra_item_minuta_empenho.quantidade',
                'compra_item_minuta_empenho.valor'
            )->having(DB::raw('count(*)'), '>', 1)
            ->select('minutaempenhos.id')->distinct();

        return $minutas->pluck('minutaempenhos.id');

    }

    private function buscaMinutasOriginaisContrato()
    {

        $minutas = Contratoitem::
        join('contrato_item_minuta_empenho', 'contrato_item_minuta_empenho.contrato_item_id', '=', 'contratoitens.id')
            ->join('minutaempenhos', 'minutaempenhos.id', '=', 'contrato_item_minuta_empenho.minutaempenho_id')
            ->join('minutaempenhos_remessa', function ($join) {
                $join->on('minutaempenhos_remessa.minutaempenho_id', '=', 'minutaempenhos.id');
                $join->on('contrato_item_minuta_empenho.minutaempenhos_remessa_id', '=', 'minutaempenhos_remessa.id');
            })
            ->where('minutaempenhos_remessa.remessa', '=', 0)
            ->where('minutaempenhos.tipo_empenhopor_id', 256)
            ->groupby(
                'minutaempenhos.id',
                'minutaempenhos_remessa.id',
                'contratoitens.contrato_id',
                'contrato_item_minuta_empenho.quantidade',
                'contrato_item_minuta_empenho.valor'
            )->having(DB::raw('count(*)'), '>', 1)
            ->select('minutaempenhos.id')->distinct();
        return $minutas->pluck('minutaempenhos.id');

    }


    private function rodaRemessaOriginal($sfitems)
    {

        foreach ($sfitems as $key => $item) {

            $cime = CompraItemMinutaEmpenho::
            join('minutaempenhos', 'minutaempenhos.id', '=', 'compra_item_minuta_empenho.minutaempenho_id')
                ->join('minutaempenhos_remessa', function ($join) {
                    $join->on('minutaempenhos_remessa.minutaempenho_id', '=', 'minutaempenhos.id');
                    $join->on('compra_item_minuta_empenho.minutaempenhos_remessa_id', '=', 'minutaempenhos_remessa.id');
                })
                ->join('compra_items', 'compra_items.id', '=', 'compra_item_minuta_empenho.compra_item_id')
                ->where('minutaempenhos.id', $item->id)
                ->where('minutaempenhos_remessa.remessa', 0)
                ->where('compra_item_minuta_empenho.quantidade', $item->quantidade)
                ->where('compra_item_minuta_empenho.valor', $item->vlroperacao)
                ->update(['numseq' => $item->numseqitem]);

        }
        echo "Terminou Remessas Originais de Compra! ";
    }

    private function rodaRemessaAlteracao($dados)
    {

        foreach ($dados as $key => $item) {

            CompraItemMinutaEmpenho::where('compra_item_minuta_empenho.minutaempenho_id', $item->minutaempenho_id)
                ->where('compra_item_minuta_empenho.compra_item_id', $item->compra_item_id)
                ->whereNull('compra_item_minuta_empenho.numseq')
                ->update(['numseq' => $item->numseq]);

        }
        echo "Terminou as Alterações Compra!";
    }

    private function rodaRemessaAlteracaoContrato($dados)
    {

        foreach ($dados as $key => $item) {

            $cime = ContratoItemMinutaEmpenho::where('contrato_item_minuta_empenho.minutaempenho_id', $item->minutaempenho_id)
                ->where('contrato_item_minuta_empenho.contrato_item_id', $item->contrato_item_id)
                ->whereNull('contrato_item_minuta_empenho.numseq')
                ->update(['numseq' => $item->numseq]);
//            dd($cime->getBindings(),$cime->toSql());
        }
        echo "Terminou as Alterações Contrato!";
    }


    private function rodaRemessaOriginalContrato($sfitems)
    {

        foreach ($sfitems as $key => $item) {

            $cime = ContratoItemMinutaEmpenho::
            join('minutaempenhos', 'minutaempenhos.id', '=', 'contrato_item_minuta_empenho.minutaempenho_id')
                ->join('minutaempenhos_remessa', function ($join) {
                    $join->on('minutaempenhos_remessa.minutaempenho_id', '=', 'minutaempenhos.id');
                    $join->on('contrato_item_minuta_empenho.minutaempenhos_remessa_id', '=', 'minutaempenhos_remessa.id');
                })
                ->where('minutaempenhos.id', $item->id)
                ->where('minutaempenhos_remessa.remessa', 0)
                ->where('contrato_item_minuta_empenho.quantidade', $item->quantidade)
                ->where('contrato_item_minuta_empenho.valor', $item->vlroperacao)
                ->update(['numseq' => $item->numseqitem]);

        }
        echo "Terminou Remessas Originais de Contrato! ";
    }

}
