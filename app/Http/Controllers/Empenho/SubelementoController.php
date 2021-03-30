<?php

namespace App\Http\Controllers\Empenho;

use App\Http\Controllers\Empenho\Minuta\BaseControllerEmpenho;
use App\Http\Traits\BuscaCodigoItens;
use App\Models\CompraItem;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\ContratoItemMinutaEmpenho;
use App\Models\CompraItemUnidade;
use App\Models\Codigoitem;
use App\Models\MinutaEmpenho;
use App\Models\Naturezasubitem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Route;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Http\Traits\Formatador;
use Alert;
use App\Http\Traits\CompraTrait;

class SubelementoController extends BaseControllerEmpenho
{
    use Formatador;
    use CompraTrait;
    use BuscaCodigoItens;

    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index(Request $request)
    {

        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
//        $fornecedor_id = $modMinutaEmpenho->fornecedor_empenho_id;
        $fornecedor_id = $modMinutaEmpenho->fornecedor_compra_id;

        $codigoitem = Codigoitem::find($modMinutaEmpenho->tipo_empenhopor_id);
        if ($codigoitem->descricao === 'Contrato') {
            $tipo = 'contrato_item_id';
            $itens = MinutaEmpenho::join(
                'contrato_item_minuta_empenho',
                'contrato_item_minuta_empenho.minutaempenho_id',
                '=',
                'minutaempenhos.id'
            )
                ->join(
                    'contratoitens',
                    'contratoitens.id',
                    '=',
                    'contrato_item_minuta_empenho.contrato_item_id'
                )
                ->join(
                    'compras',
                    'compras.id',
                    '=',
                    'minutaempenhos.compra_id'
                )
                ->join(
                    'codigoitens as tipo_compra',
                    'tipo_compra.id',
                    '=',
                    'compras.tipo_compra_id'
                )
                ->join(
                    'codigoitens',
                    'codigoitens.id',
                    '=',
                    'contratoitens.tipo_id'
                )
                ->join(
                    'saldo_contabil',
                    'saldo_contabil.id',
                    '=',
                    'minutaempenhos.saldo_contabil_id'
                )
                ->join(
                    'naturezadespesa',
                    'naturezadespesa.codigo',
                    '=',
                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6)")
                )
                ->join(
                    'catmatseritens',
                    'catmatseritens.id',
                    '=',
                    'contratoitens.catmatseritem_id'
                )
                ->where('minutaempenhos.id', $minuta_id)
                ->where('minutaempenhos.unidade_id', session('user_ug_id'))
                ->select(
                    [
                        'contrato_item_minuta_empenho.contrato_item_id',
                        'tipo_compra.descricao as tipo_compra_descricao',
                        'codigoitens.descricao',
                        'catmatseritens.codigo_siasg',
                        'catmatseritens.descricao as catmatser_desc',

                        DB::raw("SUBSTRING(catmatseritens.descricao for 50) AS catmatser_desc_simplificado"),
                        'contratoitens.descricao_complementar as descricaodetalhada',
                        DB::raw("SUBSTRING(contratoitens.descricao_complementar for 50) AS descricaosimplificada"),
                        'contratoitens.quantidade as qtd_item',
                        'contratoitens.valorunitario as valorunitario',
                        'naturezadespesa.codigo as natureza_despesa',
                        'naturezadespesa.id as natureza_despesa_id',
                        'contratoitens.valortotal',
                        'saldo_contabil.saldo',
                        'contrato_item_minuta_empenho.subelemento_id',
                        'contrato_item_minuta_empenho.quantidade',
                        'contrato_item_minuta_empenho.valor',
                        DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6) AS natureza_despesa")
                    ]
                )
                ->distinct()
                ->get()
                ->toArray();

            $valor_utilizado = ContratoItemMinutaEmpenho::where('contrato_item_minuta_empenho.minutaempenho_id', $minuta_id)
                ->select(DB::raw('coalesce(sum(valor),0) as sum'))
                ->first()->toArray();
        }

        if ($codigoitem->descricao === 'Compra') {
            $tipo = 'compra_item_id';
            $itens = MinutaEmpenho::join(
                'compra_item_minuta_empenho',
                'compra_item_minuta_empenho.minutaempenho_id',
                '=',
                'minutaempenhos.id'
            )
                ->join(
                    'compra_items',
                    'compra_items.id',
                    '=',
                    'compra_item_minuta_empenho.compra_item_id'
                )
                ->join(
                    'compras',
                    'compras.id',
                    '=',
                    'compra_items.compra_id'
                )
                ->join(
                    'codigoitens as tipo_compra',
                    'tipo_compra.id',
                    '=',
                    'compras.tipo_compra_id'
                )
                ->join(
                    'codigoitens',
                    'codigoitens.id',
                    '=',
                    'compra_items.tipo_item_id'
                )
                ->join(
                    'saldo_contabil',
                    'saldo_contabil.id',
                    '=',
                    'minutaempenhos.saldo_contabil_id'
                )
                ->join(
                    'naturezadespesa',
                    'naturezadespesa.codigo',
                    '=',
                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6)")
                )
                ->join(
                    'compra_item_fornecedor',
                    'compra_item_fornecedor.compra_item_id',
                    '=',
                    'compra_items.id'
                )
                ->join(
                    'catmatseritens',
                    'catmatseritens.id',
                    '=',
                    'compra_items.catmatseritem_id'
                )
                ->join(
                    'compra_item_unidade',
                    'compra_item_unidade.compra_item_id',
                    '=',
                    'compra_items.id'
                )
                ->where('minutaempenhos.id', $minuta_id)
                ->where('compra_item_unidade.unidade_id', session('user_ug_id'))
//                ->where('compra_item_unidade.fornecedor_id', $fornecedor_id)
//                ->where('compra_item_fornecedor.fornecedor_id', $fornecedor_id)
                ->select(
                    [
                        'compra_item_minuta_empenho.compra_item_id',
                        'compra_item_fornecedor.fornecedor_id',
                        'tipo_compra.descricao as tipo_compra_descricao',
                        'codigoitens.descricao',
                        'catmatseritens.codigo_siasg',
                        'catmatseritens.descricao as catmatser_desc',
                        DB::raw("SUBSTRING(catmatseritens.descricao for 50) AS catmatser_desc_simplificado"),
                        'compra_items.descricaodetalhada',
                        DB::raw("SUBSTRING(compra_items.descricaodetalhada for 50) AS descricaosimplificada"),
                        'compra_item_unidade.quantidade_saldo as qtd_item',
                        'compra_item_fornecedor.valor_unitario as valorunitario',
                        'naturezadespesa.codigo as natureza_despesa',
                        'naturezadespesa.id as natureza_despesa_id',
                        'compra_item_fornecedor.valor_negociado as valortotal',
                        'saldo_contabil.saldo',
                        'compra_item_minuta_empenho.subelemento_id',
                        'compra_item_minuta_empenho.quantidade',
                        'compra_item_minuta_empenho.valor',
                        DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6) AS natureza_despesa")
                    ]
                )
                ->distinct();

            $itens = $this->setCondicaoFornecedor(
                $modMinutaEmpenho,
                $itens,
                $codigoitem->descricao,
                $modMinutaEmpenho->fornecedor_empenho_id,
                $fornecedor_id
            );

            $itens = $itens->get()
                ->toArray();


            $valor_utilizado = CompraItemMinutaEmpenho::where('compra_item_minuta_empenho.minutaempenho_id', $minuta_id)
                ->select(DB::raw('coalesce(sum(valor),0) as sum'))
                ->first()->toArray();
        }

        if ($codigoitem->descricao === 'Suprimento') {
            $tipo = 'compra_item_id';

            $itens = MinutaEmpenho::join(
                'compra_item_minuta_empenho',
                'compra_item_minuta_empenho.minutaempenho_id',
                '=',
                'minutaempenhos.id'
            )
                ->join(
                    'compra_items',
                    'compra_items.id',
                    '=',
                    'compra_item_minuta_empenho.compra_item_id'
                )
                ->join(
                    'compras',
                    'compras.id',
                    '=',
                    'compra_items.compra_id'
                )
                ->join(
                    'codigoitens as tipo_compra',
                    'tipo_compra.id',
                    '=',
                    'compras.tipo_compra_id'
                )
                ->join(
                    'codigoitens',
                    'codigoitens.id',
                    '=',
                    'compra_items.tipo_item_id'
                )
                ->join(
                    'saldo_contabil',
                    'saldo_contabil.id',
                    '=',
                    'minutaempenhos.saldo_contabil_id'
                )
                ->join(
                    'naturezadespesa',
                    'naturezadespesa.codigo',
                    '=',
                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6)")
                )
                ->join(
                    'compra_item_fornecedor',
                    'compra_item_fornecedor.compra_item_id',
                    '=',
                    'compra_items.id'
                )
                ->join(
                    'catmatseritens',
                    'catmatseritens.id',
                    '=',
                    'compra_items.catmatseritem_id'
                )
                ->join(
                    'compra_item_unidade',
                    'compra_item_unidade.compra_item_id',
                    '=',
                    'compra_items.id'
                )
                ->where('minutaempenhos.id', $minuta_id)
                ->where('compra_item_fornecedor.fornecedor_id', $modMinutaEmpenho->fornecedor_empenho_id)
                ->where('compra_item_unidade.unidade_id', session('user_ug_id'))
                ->select(
                    [
                        'compra_item_minuta_empenho.compra_item_id',
                        'compra_item_fornecedor.fornecedor_id',
                        'tipo_compra.descricao as tipo_compra_descricao',
                        'codigoitens.descricao',
                        'catmatseritens.codigo_siasg',
                        'catmatseritens.descricao as catmatser_desc',
                        DB::raw("SUBSTRING(catmatseritens.descricao for 50) AS catmatser_desc_simplificado"),
                        'compra_items.descricaodetalhada',
                        DB::raw("SUBSTRING(compra_items.descricaodetalhada for 50) AS descricaosimplificada"),
                        'compra_item_unidade.quantidade_saldo as qtd_item',
                        'compra_item_fornecedor.valor_unitario as valorunitario',
                        'naturezadespesa.codigo as natureza_despesa',
                        'naturezadespesa.id as natureza_despesa_id',
                        'compra_item_fornecedor.valor_negociado as valortotal',
                        'saldo_contabil.saldo',
                        'compra_item_minuta_empenho.subelemento_id',
                        'compra_item_minuta_empenho.quantidade',
                        'compra_item_minuta_empenho.valor',
                        DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6) AS natureza_despesa")
                    ]
                )
                ->get()
                ->toArray();
            $valor_utilizado = CompraItemMinutaEmpenho::where('compra_item_minuta_empenho.minutaempenho_id', $minuta_id)
                ->select(DB::raw('coalesce(sum(valor),0) as sum'))
                ->first()->toArray();
        }

        if ($request->ajax()) {
            $dados = DataTables::of($itens)
                ->addColumn(
                    'ci_id',
                    function ($item) use ($modMinutaEmpenho) {

                        return $this->addColunaCompraItemId($item);
                    }
                )
                ->addColumn(
                    'subitem',
                    function ($item) use ($modMinutaEmpenho) {

                        return $this->addColunaSubItem($item);
                    }
                )
                ->addColumn(
                    'quantidade',
                    function ($item) use ($tipo) {
                        return $this->addColunaQuantidade($item, $tipo);
                    }
                )
                ->addColumn(
                    'valor_total',
                    function ($item) use ($tipo) {
                        return $this->addColunaValorTotal($item, $tipo);
                    }
                )
                ->addColumn(
                    'valor_total_item',
                    function ($item) use ($tipo) {
                        return $this->addColunaValorTotalItem($item, $tipo);
                    }
                )
                ->addColumn('descricaosimplificada', function ($itens) use ($modMinutaEmpenho) {
                    if ($itens['descricaosimplificada'] != null && $itens['descricaosimplificada'] !== 'undefined') {
                        return $this->retornaDescricaoDetalhada(
                            $itens['descricaosimplificada'],
                            $itens['descricaodetalhada']
                        );
                    }
                    return $this->retornaDescricaoDetalhada(
                        $itens['catmatser_desc_simplificado'],
                        $itens['catmatser_desc']
                    );
                })
                ->rawColumns(['subitem', 'quantidade', 'valor_total', 'valor_total_item', 'descricaosimplificada'])
                ->make(true);

            return $dados;
        }

        $html = $this->retornaGridItens();

        $tipo = ($codigoitem->descricao === 'Compra' || $codigoitem->descricao === 'Suprimento') ? 'compra_item_id' : 'contrato_item_id';

        return view(
            'backpack::mod.empenho.Etapa5SubElemento',
            compact('html')
        )->with([
            'credito' => $itens[0]['saldo'],
            'valor_utilizado' => $valor_utilizado['sum'],
            'saldo' => $itens[0]['saldo'] - $valor_utilizado['sum'],
            'update' => false,
            'tipo' => $tipo,
            'tipo_item' => $itens[0]['descricao'],
            'update' => $valor_utilizado['sum'] > 0,
            //           'fornecedor_id' => $itens[0]['fornecedor_id'],
        ]);
    }

    /*    private function retornaDescricaoDetalhada($descricao, $descricaocompleta)
        {
            $retorno = '';
            $retorno .= $descricao.' <i class="fa fa-info-circle" title="'.$descricaocompleta.'"></i>';

            return $retorno;
        }*/

    /**
     * Monta $html com definições do Grid
     *
     * @return Builder
     */
    private function retornaGridItens()
    {
        $html = $this->htmlBuilder
            ->addColumn(
                [
                    'data' => 'ci_id',
                    'name' => 'ci_id',
                    'title' => '',
                    'orderable' => false,
                    'searchable' => false,
                    'visible' => false
                ]
            )
            ->addColumn(
                [
                    'data' => 'descricao',
                    'name' => 'descricao',
                    'title' => 'Tipo',
                    'orderable' => false,
                    'searchable' => false
                ]
            )
            ->addColumn(
                [
                    'data' => 'codigo_siasg',
                    'name' => 'codigo_siasg',
                    'title' => 'Codigo',
                ]
            )
            ->addColumn(
                [
                    'data' => 'descricaosimplificada',
                    'name' => 'descricaosimplificada',
                    'title' => 'Descrição',
                ]
            )
            ->addColumn(
                [
                    'data' => 'qtd_item',
                    'name' => 'qtd_item',
                    'title' => 'Qtd. de Item',
                ]
            )
            ->addColumn(
                [
                    'data' => 'valorunitario',
                    'name' => 'valorunitario',
                    'title' => 'Valor Unit.',
                ]
            )
            ->addColumn(
                [
                    'data' => 'valor_total_item',
                    'name' => 'valor_total_item',
                    'title' => 'Valor Total do Item',
                ]
            )
            ->addColumn(
                [
                    'data' => 'natureza_despesa',
                    'name' => 'natureza_despesa',
                    'title' => 'Natureza da Despesa',
                ]
            )
            ->addColumn(
                [
                    'data' => 'subitem',
                    'name' => 'subitem',
                    'title' => 'Subelemento',
                    'orderable' => false,
                    'searchable' => false
                ]
            )
            ->addColumn(
                [
                    'data' => 'quantidade',
                    'name' => 'quantidade',
                    'title' => 'Qtd',
                    'orderable' => false,
                    'searchable' => false
                ]
            )
            ->addColumn(
                [
                    'data' => 'valor_total',
                    'name' => 'valor_total',
                    'title' => 'Valor Total',
                    'orderable' => false,
                    'searchable' => false
                ]
            )
            ->parameters(
                [
                    'processing' => true,
                    'serverSide' => true,
                    'info' => true,
                    'order' => [
                        0,
                        'desc'
                    ],
                    'autoWidth' => false,
                    'bAutoWidth' => false,
                    'paging' => true,
                    'lengthChange' => true,
                    'lengthMenu' => [
                        [10, 25, 50, 100, -1],
                        ['10', '25', '50', '100', 'Todos']
                    ],
                    'language' => [
                        'url' => asset('/json/pt_br.json')
                    ],
                    'initComplete' => 'function() { $(\'.subitem\').select2(); atualizaMascara() }',
                    'responsive' => [true,
                        'details' => [
                            'renderer' => '$.fn.dataTable.Responsive.renderer.listHiddenNodes()'
                        ]
                    ]
                ]
            );

        return $html;
    }

    private function addColunaSubItem($item)
    {
        $subItens = Naturezasubitem::where('naturezadespesa_id', $item['natureza_despesa_id'])
            ->orderBy('codigo', 'asc')
            ->get()->pluck('codigo_descricao', 'id');

        $retorno = '<select name="subitem[]" id="subitem" class="subitem" style="width:200px;">';
        foreach ($subItens as $key => $subItem) {
            $selected = ($key == $item['subelemento_id']) ? 'selected' : '';
            $retorno .= "<option value='$key' $selected>$subItem</option>";
        }
        $retorno .= '</select>';
        return $this->addColunaCompraItemId($item) . $retorno;
    }

    private function addColunaQuantidade($item, $tipo)
    {
        $quantidade = $item['quantidade'];

        if ($tipo === 'contrato_item_id' && $item['descricao'] === 'Serviço') {
            return " <input  type='number' max='" . $item['qtd_item'] . "' min='1' class='form-control qtd"
                . $item[$tipo] . "' id='qtd" . $item[$tipo]
                . "' data-tipo='' name='qtd[]' value='$quantidade' readonly  > "
                . " <input  type='hidden' id='quantidade_total" . $item[$tipo]
                . "' data-tipo='' name='quantidade_total[]' value='"
                . $item['qtd_item'] . " readonly'> ";
        }

        if ($item['tipo_compra_descricao'] === 'SISPP' && $item['descricao'] === 'Serviço') {
            return " <input  type='number' max='" . $item['qtd_item'] . "' min='1' class='form-control qtd"
                . $item[$tipo] . "' id='qtd" . $item[$tipo]
                . "' data-tipo='' name='qtd[]' value='" . $quantidade . "' readonly> "
                . " <input  type='hidden' id='quantidade_total" . $item[$tipo]
                . "' data-tipo='' name='quantidade_total[]' value='"
                . $item['qtd_item'] . " '> ";
        }
        //caso seja suprimento
        if (strpos($item['catmatser_desc'], 'SUPRIMENTO') !== false) {
            return " <input  type='number' max='" . $item['qtd_item'] . "' min='1' class='form-control qtd"
                . $item[$tipo] . "' id='qtd" . $item[$tipo]
                . "' data-tipo='' name='qtd[]' value='$quantidade' readonly  > "
                . " <input  type='hidden' id='quantidade_total" . $item[$tipo]
                . "' data-tipo='' name='quantidade_total[]' value='"
                . $item['qtd_item'] . " readonly'> ";
        }

        return " <input type='number' max='" . $item['qtd_item'] . "' min='1' id='qtd" . $item[$tipo]
            . "' data-$tipo='" . $item[$tipo]
            . "' data-valor_unitario='" . $item['valorunitario'] . "' name='qtd[]'"
            . " class='form-control qtd' value='$quantidade' > "
            . " <input  type='hidden' id='quantidade_total" . $item[$tipo]
            . "' data-tipo='' name='quantidade_total[]' value='" . $item['qtd_item'] . "'> ";
    }

    private function addColunaValorTotal($item, $tipo)
    {

        $valor = $item['valor'];

        //se for contrato e serviço OU sispp e serviço OU se for suprimento
        if (($tipo == 'contrato_item_id' && $item['descricao'] === 'Serviço') ||
            ($item['tipo_compra_descricao'] === 'SISPP' && $item['descricao'] === 'Serviço') ||
            (strpos($item['catmatser_desc'], 'SUPRIMENTO') !== false)
        ) {
            return " <input  type='text' class='form-control col-md-12 valor_total vrtotal"
                . $item[$tipo] . "'"
                . "id='vrtotal" . $item[$tipo]
                . "' data-qtd_item='" . $item['qtd_item'] . "' name='valor_total[]' value='$valor'"
                . " data-$tipo='" . $item[$tipo] . "'"
                . " data-valor_unitario='" . $item['valorunitario'] . "'"
                . " onkeyup='calculaQuantidade(this)' >";
        }

        return " <input  type='text' class='form-control valor_total vrtotal" . $item[$tipo] . "'"
            . "id='vrtotal" . $item[$tipo]
            . "' data-tipo='' name='valor_total[]' value='$valor' disabled > ";
    }

    private function addColunaValorTotalItem($item, $tipo)
    {
        return "<td>" . $item['qtd_item'] * $item['valorunitario'] . "</td>"
            . " <input  type='hidden' id='valor_total_item" . $item[$tipo] . "'"
            . " name='valor_total_item[]"
            . "' value='" . $item['qtd_item'] * $item['valorunitario'] . "'> ";
    }

    private function addColunaCompraItemId($item)
    {
        if (isset($item['compra_item_id'])) {
            return " <input  type='hidden' id='" . ''
                . "' data-tipo='' name='compra_item_id[]' value='" . $item['compra_item_id'] . "'   > ";
        }
        return " <input  type='hidden' id='" . ''
            . "' data-tipo='' name='contrato_item_id[]' value='" . $item['contrato_item_id'] . "'   > ";
    }

    public function store(Request $request)
    {

        $credito = (number_format($request->credito, 2, '.', ''));
        $valor_utilizado = (number_format($request->valor_utilizado, 2, '.', ''));

        $minuta_id = $request->get('minuta_id');
        $modMinuta = MinutaEmpenho::find($minuta_id);

        $tipo = $modMinuta->tipo_empenhopor->descricao;

        if ($credito - $valor_utilizado < 0) {
            Alert::error('O saldo não pode ser negativo.')->flash();
            return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
        }

        $valores = $request->valor_total;

        $valores = array_map(
            function ($valores) {
                return $this->retornaFormatoAmericano($valores);
            },
            $valores
        );

        if ($tipo == 'Compra') {
            if (in_array(0, $valores) || in_array('', $valores)) {
                Alert::error('O item não pode estar com valor zero.')->flash();
                return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
            }
        }

        DB::beginTransaction();
        try {
            if ($tipo == 'Contrato') {
                $contrato_item_ids = $request->contrato_item_id;

                foreach ($contrato_item_ids as $index => $item) {
                    ContratoItemMinutaEmpenho::where('contrato_item_id', $item)
                        ->where('minutaempenho_id', $request->minuta_id)
                        ->update([
                            'subelemento_id' => $request->subitem[$index],
                            'quantidade' => ($request->qtd[$index]),
                            'valor' => $valores[$index]
                        ]);
                }
            }
            if ($tipo == 'Compra') {
                $compra_item_ids = $request->compra_item_id;
                foreach ($compra_item_ids as $index => $item) {
                    if ($valores[$index] > $request->valor_total_item[$index]) {
                        Alert::error('O valor selecionado não pode ser maior do que o valor total do item.')->flash();
                        return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
                    }

                    if ($request->qtd[$index] == 0) {
                        Alert::error('A quantidade selecionada não pode ser zero.')->flash();
                        return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
                    }

                    CompraItemMinutaEmpenho::where('compra_item_id', $item)
                        ->where('minutaempenho_id', $request->minuta_id)
                        ->update([
                            'subelemento_id' => $request->subitem[$index],
                            'quantidade' => ($request->qtd[$index]),
                            'valor' => $valores[$index]
                        ]);

                    $compraItemUnidade = CompraItemUnidade::where('compra_item_id', $item)
                        ->where('unidade_id', session('user_ug_id'))
                        //->where('fornecedor_id', $request->fornecedor_id)
                        ->first();

                    $saldo = $this->retornaSaldoAtualizado($item);
                    $compraItemUnidade->quantidade_saldo = $saldo->saldo;
                    $compraItemUnidade->save();
                }
            }

            if ($tipo == 'Suprimento') {
                $compra_item_ids = $request->compra_item_id;
                foreach ($compra_item_ids as $index => $item) {
                    if ($request->qtd[$index] == 0) {
                        Alert::error('A quantidade selecionada não pode ser zero.')->flash();
                        return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
                    }

                    CompraItemMinutaEmpenho::where('compra_item_id', $item)
                        ->where('minutaempenho_id', $request->minuta_id)
                        ->update([
                            'subelemento_id' => $request->subitem[$index],
                            'quantidade' => ($request->qtd[$index]),
                            'valor' => $valores[$index]
                        ]);
                }
            }

            $modMinuta = MinutaEmpenho::find($minuta_id);
            $modMinuta->etapa = 6;
            $modMinuta->valor_total = $request->valor_utilizado;
            $modMinuta->save();

            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
        }

        return redirect()->route('empenho.crud./minuta.edit', ['minutum' => $modMinuta->id]);
    }

    public function update(Request $request)
    {
        $minuta_id = $request->get('minuta_id');

        $modMinuta = MinutaEmpenho::find($minuta_id);
        $tipo = $modMinuta->tipo_empenhopor->descricao;

        if ($request->credito - $request->valor_utilizado < 0) {
            Alert::error('O saldo não pode ser negativo.')->flash();
            return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
        }

        $valores = $request->valor_total;

        $valores = array_map(
            function ($valores) {
                return $this->retornaFormatoAmericano($valores);
            },
            $valores
        );

        if ($tipo == 'Compra') {
            if (in_array(0, $valores) || in_array('', $valores)) {
                Alert::error('O item não pode estar com valor zero.')->flash();
                return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
            }
        }

        DB::beginTransaction();
        try {
            if ($tipo === 'Contrato') {
                $contrato_item_ids = $request->contrato_item_id;

                foreach ($contrato_item_ids as $index => $item) {
                    ContratoItemMinutaEmpenho::where('contrato_item_id', $item)
                        ->where('minutaempenho_id', $request->minuta_id)
                        ->update([
                            'subelemento_id' => $request->subitem[$index],
                            'quantidade' => ($request->qtd[$index]),
                            'valor' => $valores[$index]
                        ]);
                }
            }

            if ($tipo === 'Compra') {
                $compra_item_ids = $request->compra_item_id;
                foreach ($compra_item_ids as $index => $item) {
                    if ($valores[$index] > $request->valor_total_item[$index]) {
                        Alert::error('O valor selecionado não pode ser maior do que o valor total do item.')->flash();
                        return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
                    }

                    CompraItemMinutaEmpenho::where('compra_item_id', $item)
                        ->where('minutaempenho_id', $request->minuta_id)
                        ->update([
                            'subelemento_id' => $request->subitem[$index],
                            'quantidade' => ($request->qtd[$index]),
                            'valor' => $valores[$index]
                        ]);

                    $compraItemUnidade = CompraItemUnidade::where('compra_item_id', $item)
                        ->where('unidade_id', session('user_ug_id'))
                        ->first();

                    $saldo = $this->retornaSaldoAtualizado($item);
                    $compraItemUnidade->quantidade_saldo = $saldo->saldo;
                    $compraItemUnidade->save();
                }
            }
            if ($tipo === 'Suprimento') {
                $compra_item_ids = $request->compra_item_id;
                foreach ($compra_item_ids as $index => $item) {
                    if ($request->qtd[$index] == 0) {
                        Alert::error('A quantidade selecionada não pode ser zero.')->flash();
                        return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
                    }
                    CompraItemMinutaEmpenho::where('compra_item_id', $item)
                        ->where('minutaempenho_id', $request->minuta_id)
                        ->update([
                            'subelemento_id' => $request->subitem[$index],
                            'quantidade' => ($request->qtd[$index]),
                            'valor' => $valores[$index]
                        ]);
                }
            }

            $modMinuta = MinutaEmpenho::find($minuta_id);
            $modMinuta->etapa = 6;
            $modMinuta->valor_total = $request->valor_utilizado;
            $modMinuta->save();

            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
        }

        return redirect()->route('empenho.crud./minuta.edit', ['minutum' => $modMinuta->id]);
    }
}
