<?php
/**
 * Controller com métodos e funções da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */

namespace App\Http\Controllers\Empenho;

use Alert;
use App\Http\Controllers\Empenho\Minuta\BaseControllerEmpenho;
use App\Models\Codigoitem;
use App\Models\CompraItem;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\ContratoItemMinutaEmpenho;
use App\Models\CompraItemUnidade;
use App\Models\Contrato;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\MinutaEmpenhoRemessa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Route;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Http\Traits\CompraTrait;

class FornecedorEmpenhoController extends BaseControllerEmpenho
{
    use CompraTrait;

    public function index(Request $request)
    {
        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
        $codigoitem = Codigoitem::find($modMinutaEmpenho->tipo_empenhopor_id);


        if ($codigoitem->descricao === 'Contrato') {
            //$fornecedores = $modMinutaEmpenho->contrato()->first()->fornecedor;
            $fornecedores = MinutaEmpenho::join(
                'contratos',
                'contratos.id',
                '=',
                'minutaempenhos.contrato_id'
            )
                ->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id')
                ->where('minutaempenhos.id', $minuta_id)
                ->select([
                    'fornecedores.id',
                    'fornecedores.nome',
                    'fornecedores.cpf_cnpj_idgener',
                    DB::raw('1 AS situacao_sicaf')
                ])
                ->get()
                ->toArray();
//            ;dd($fornecedores->getBindings(),$fornecedores->toSql());
        }
        if ($codigoitem->descricao == 'Compra') {
            $fornecedores = MinutaEmpenho::join('compras', 'compras.id', '=', 'minutaempenhos.compra_id')
                ->join('compra_items', 'compra_items.compra_id', '=', 'compras.id')
                ->join('compra_item_unidade', 'compra_item_unidade.compra_item_id', '=', 'compra_items.id')
                ->join('unidades', 'unidades.id', '=', 'compra_item_unidade.unidade_id')
                ->join('compra_item_fornecedor', 'compra_item_fornecedor.compra_item_id', '=', 'compra_items.id')
                ->join('fornecedores', 'fornecedores.id', '=', 'compra_item_fornecedor.fornecedor_id')
                ->distinct()
                ->where('minutaempenhos.id', $minuta_id)
                ->where('compra_item_unidade.quantidade_saldo', '>', 0)
                ->select([
                    'fornecedores.id', 'fornecedores.nome',
                    'fornecedores.cpf_cnpj_idgener',
                    'compra_item_fornecedor.situacao_sicaf'
                ])
                ->get()
                ->toArray();
        }
        if ($codigoitem->descricao == 'Suprimento') {
            $fornecedores = Fornecedor::whereIn('tipo_fornecedor', ['FISICA', 'UG'])
                ->select([
                    'fornecedores.id',
                    'fornecedores.nome',
                    'fornecedores.cpf_cnpj_idgener',
                    DB::raw('1 AS situacao_sicaf')
                ])
                ->get()
                ->toArray();
        }


        if ($request->ajax()) {
            return DataTables::of($fornecedores)->addColumn('action', function ($fornecedores) use ($minuta_id) {
                return $this->retornaAcoes($fornecedores['id'], $minuta_id, $fornecedores['situacao_sicaf']);
            })->addColumn('icone', function ($fornecedores) use ($minuta_id) {
                return '<i class="fa fa-' . ($fornecedores['situacao_sicaf'] != 1 ? 'times' : 'check') . '"></i>';
            })->rawColumns(['icone', 'action'])
                ->make(true);
        }

        $html = $this->retornaGrid();

        return view('backpack::mod.empenho.Etapa2Fornecedor', compact('html'));
    }

    /**
     * Retorna html das ações disponíveis
     *
     * @param number $id
     * @return string
     */
    private function retornaAcoes($id, $minuta_id, $situacao_sicaf)
    {
        $acoes = '';
        $acoes .= '<a href="' . route('empenho.minuta.etapa.item', ['minuta_id' => $minuta_id, 'fornecedor_id' => $id]);
        $acoes .= '"Selecionar ';
        $acoes .= "class='btn btn-default btn-sm' ";
        $acoes .= 'title="Selecionar este fornecedor">';
        $acoes .= '<i class="fa fa-check-circle"></i></a>';
        $sem_acao = '<i class="glyphicon glyphicon-ban-circle"></i>';

        //$acoes = ($situacao_sicaf != 1) ? $sem_acao : $acoes;

        return $acoes;
    }

    /**
     * Monta $html com definições do Grid
     *
     * @return Builder
     */
    private function retornaGrid()
    {

        $html = $this->htmlBuilder
            ->addColumn([
                'data' => 'action',
                'name' => 'action',
                'title' => 'Ações',
                'orderable' => false,
                'searchable' => false
            ])
            ->addColumn([
                'data' => 'cpf_cnpj_idgener',
                'name' => 'cpf_cnpj_idgener',
                'title' => 'CNPJ - CPF - Número',
            ])
            ->addColumn([
                'data' => 'nome',
                'name' => 'nome',
                'title' => 'Fornecedor'
            ])
            ->addColumn([
                'data' => 'icone',
                'name' => 'icone',
                'title' => 'Situação SICAF',
                'orderable' => false,
                'searchable' => false
            ])
            ->parameters([
                'processing' => true,
                'serverSide' => true,
                'responsive' => true,
                'info' => true,
                'order' => [
                    0,
                    'desc'
                ],
                'searchDelay' => 3000,
                'autoWidth' => false,
                'bAutoWidth' => false,
                'paging' => true,
                'lengthChange' => true,
                'language' => [
                    'url' => asset('/json/pt_br.json')
                ]
            ]);

        return $html;
    }

    public function item(Request $request)
    {
        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
        $acoes = '<input type="checkbox" name="selectAll" id="selectAll" > Ações';

        $fornecedor_id = Route::current()->parameter('fornecedor_id');
        if (!is_null($modMinutaEmpenho)) {
            $modMinutaEmpenho->atualizaFornecedorCompra($fornecedor_id);
            session(['fornecedor_compra' => $fornecedor_id]);
        }
        $codigoitem = Codigoitem::find($modMinutaEmpenho->tipo_empenhopor_id);

        if ($codigoitem->descricao === 'Contrato') {
            $tipo = 'contrato_item_id';
            $update = ContratoItemMinutaEmpenho::where('minutaempenho_id', $minuta_id)->get()->isNotEmpty();
            $itens = Contrato::where('fornecedor_id', '=', $fornecedor_id)
                ->where('contratos.id', '=', $modMinutaEmpenho->contrato_id)
                ->whereNull('contratoitens.deleted_at')
                ->join('contratoitens', 'contratoitens.contrato_id', '=', 'contratos.id')
                ->join('codigoitens', 'codigoitens.id', '=', 'contratoitens.tipo_id')
                ->join('catmatseritens', 'catmatseritens.id', '=', 'contratoitens.catmatseritem_id')
                ->select([
                    'contratoitens.id',
                    'codigoitens.descricao',
                    'contratoitens.numero_item_compra as numero',
                    'catmatseritens.codigo_siasg',
                    'catmatseritens.descricao as catmatser_desc',
                    DB::raw("SUBSTRING(catmatseritens.descricao for 50) AS catmatser_desc_simplificado"),
                    'contratoitens.descricao_complementar as descricaodetalhada',
                    DB::raw("SUBSTRING(contratoitens.descricao_complementar for 50) AS descricaosimplificada"),
                    'contratoitens.quantidade as quantidade_saldo',
                    'contratoitens.valorunitario as valor_unitario',
                    'contratoitens.valortotal as valor_negociado'
//                    'minutaempenhos.numero_contrato'
                ])
                ->get()
                ->toArray();
//            ;dd($itens->getBindings(),$itens->toSql(),$itens->get());
        }

        if ($codigoitem->descricao === 'Compra') {
            $tipo = 'compra_item_id';
            $update = $update = CompraItemMinutaEmpenho::where('minutaempenho_id', $minuta_id)->get()->isNotEmpty();

            $itens = CompraItem::join('compras', 'compras.id', '=', 'compra_items.compra_id')
                ->join('compra_item_fornecedor', 'compra_item_fornecedor.compra_item_id', '=', 'compra_items.id')
                ->join('fornecedores', 'fornecedores.id', '=', 'compra_item_fornecedor.fornecedor_id')
                ->join('compra_item_unidade', 'compra_item_unidade.compra_item_id', '=', 'compra_items.id')
                ->join('unidades', 'unidades.id', '=', 'compra_item_unidade.unidade_id')
                ->join('codigoitens', 'codigoitens.id', '=', 'compra_items.tipo_item_id')
                ->join(
                    'catmatseritens',
                    'catmatseritens.id',
                    '=',
                    'compra_items.catmatseritem_id'
                )
                ->where('compra_item_unidade.quantidade_saldo', '>', 0)
                ->where('compra_item_unidade.unidade_id', session('user_ug_id'))
                ->where('compras.id', $modMinutaEmpenho->compra_id)
                ->where(function ($query) use ($fornecedor_id) {
                    $query->where('compra_item_unidade.fornecedor_id', $fornecedor_id)
                        ->orWhere('compra_item_fornecedor.fornecedor_id', $fornecedor_id);
                })
                ->select([
                    'compra_items.id',
                    'codigoitens.descricao',
                    'catmatseritens.codigo_siasg',
                    'catmatseritens.descricao as catmatser_desc',
                    DB::raw("SUBSTRING(catmatseritens.descricao for 50) AS catmatser_desc_simplificado"),
                    'compra_items.descricaodetalhada',
                    DB::raw("SUBSTRING(compra_items.descricaodetalhada for 50) AS descricaosimplificada"),
                    'compra_item_unidade.quantidade_saldo',
                    'compra_item_fornecedor.valor_unitario',
                    'compra_item_fornecedor.valor_negociado',
                    'compra_items.numero'
                ])
                ->get()
                ->toArray();
        }

        if ($codigoitem->descricao === 'Suprimento') {
            $acoes = 'Ações';
            DB::beginTransaction();
            try {
                $this->gravaCompraItemFornecedorSuprimento($modMinutaEmpenho, $fornecedor_id);
                DB::commit();
            } catch (Exception $exc) {
                DB::rollback();
            }

            $tipo = 'compra_item_id';
            $update = $update = CompraItemMinutaEmpenho::where('minutaempenho_id', $minuta_id)->get()->isNotEmpty();

            $itens = CompraItem::join('compras', 'compras.id', '=', 'compra_items.compra_id')
                ->join('compra_item_fornecedor', 'compra_item_fornecedor.compra_item_id', '=', 'compra_items.id')
                ->join('fornecedores', 'fornecedores.id', '=', 'compra_item_fornecedor.fornecedor_id')
                ->join('compra_item_unidade', 'compra_item_unidade.compra_item_id', '=', 'compra_items.id')
                ->join('unidades', 'unidades.id', '=', 'compra_item_unidade.unidade_id')
                ->join('codigoitens', 'codigoitens.id', '=', 'compra_items.tipo_item_id')
                ->join(
                    'catmatseritens',
                    'catmatseritens.id',
                    '=',
                    'compra_items.catmatseritem_id'
                )
                ->where('compra_item_unidade.quantidade_saldo', '>', 0)
                ->where('compra_item_unidade.unidade_id', session('user_ug_id'))
                ->where('compras.id', $modMinutaEmpenho->compra_id)
                ->where(function ($query) use ($fornecedor_id) {
                    $query->where('compra_item_unidade.fornecedor_id', $fornecedor_id)
                        ->orWhere('compra_item_fornecedor.fornecedor_id', $fornecedor_id);
                })
                ->select([
                    'compra_items.id',
                    'codigoitens.descricao',
                    'catmatseritens.codigo_siasg',
                    'catmatseritens.descricao as catmatser_desc',
                    DB::raw("SUBSTRING(catmatseritens.descricao for 50) AS catmatser_desc_simplificado"),
                    'compra_items.descricaodetalhada',
                    DB::raw("SUBSTRING(compra_items.descricaodetalhada for 50) AS descricaosimplificada"),
                    'compra_item_unidade.quantidade_saldo',
                    'compra_item_fornecedor.valor_unitario',
                    'compra_item_fornecedor.valor_negociado',
                    'compra_items.numero'
                ])
                ->get()
                ->toArray();
        }

        if ($request->ajax()) {
            return DataTables::of($itens)
                ->addColumn('action', function ($itens) use ($modMinutaEmpenho, $tipo) {
                    return $this->retornaRadioItens($itens['id'], $modMinutaEmpenho->id, $itens['descricao'], $tipo);
                })
                ->addColumn('descricaosimplificada', function ($itens) use ($modMinutaEmpenho) {
                    if ($itens['descricaosimplificada'] != null) {
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
                ->rawColumns(['descricaosimplificada', 'action'])
                ->make(true);
        }

        $html = $this->retornaGridItens($acoes);

        return view(
            'backpack::mod.empenho.Etapa3Itensdacompra',
            compact('html')
        )->with(['update' => $update]);
    }

    private function retornaRadioItens($id, $minuta_id, $descricao, $tipo)
    {
        $retorno = '';
        $retorno .= " <input  type='checkbox' id='$id' data-tipo='$descricao' "
            . "name='itens[][$tipo]' value='$id'  onclick=\"bloqueia('$descricao')\" > ";
        return $retorno;
    }

    private function retornaDescricaoDetalhada($descricao, $descricaocompleta)
    {
        $retorno = '';
        $retorno .= $descricao . ' <i class="fa fa-info-circle" title="' . $descricaocompleta . '"></i>';

        return $retorno;
    }

    /**
     * Monta $html com definições do Grid
     *
     * @return Builder
     */
    private function retornaGridItens(string $acoes)
    {

        $html = $this->htmlBuilder
            ->addColumn([
                'data' => 'action',
                'name' => 'action',
                'title' => $acoes,
                'orderable' => false,
                'searchable' => false
            ])
            ->addColumn([
                'data' => 'numero',
                'name' => 'numero',
                'title' => 'N. Item',
            ])
            ->addColumn([
                'data' => 'descricao',
                'name' => 'descricao',
                'title' => 'Tipo',
            ])
            ->addColumn([
                'data' => 'codigo_siasg',
                'name' => 'codigo_siasg',
                'title' => 'Codigo',
            ])
            ->addColumn([
                'data' => 'descricaosimplificada',
                'name' => 'descricaosimplificada',
                'title' => 'Descrição',
            ])
            ->addColumn([
                'data' => 'quantidade_saldo',
                'name' => 'quantidade_saldo',
                'title' => 'Qtd./Saldo',
            ])
            ->addColumn([
                'data' => 'valor_unitario',
                'name' => 'valor_unitario',
                'title' => 'Valor Unit.',
            ])
            ->addColumn([
                'data' => 'valor_negociado',
                'name' => 'valor_negociado',
                'title' => 'Valor Total.',
            ])
            ->parameters([
                'processing' => true,
                'serverSide' => true,
                'responsive' => true,
                'info' => true,
                'order' => [
                    0,
                    'desc'
                ],
                'autoWidth' => false,
                'bAutoWidth' => false,
                'paging' => true,
                'lengthChange' => true,
                'language' => [
                    'url' => asset('/json/pt_br.json')
                ]
            ]);

        return $html;
    }

    public function store(Request $request)
    {
        $minuta = MinutaEmpenho::find($request->minuta_id);

        $minuta_id = $request->minuta_id;
        $fornecedor_id = $request->fornecedor_id;
        $itens = $request->itens;

        if (!isset($itens)) {
            Alert::error('Escolha pelo menos 1 item.')->flash();
            return redirect()->route(
                'empenho.minuta.etapa.item',
                ['minuta_id' => $minuta_id, 'fornecedor_id' => $fornecedor_id]
            );
        }


        DB::beginTransaction();
        try {
            $situacao_andamento = Codigoitem::wherehas('codigo', function ($q) {
                $q->where('descricao', '=', 'Situações Minuta Empenho');
            })
                ->where('descricao', 'EM ANDAMENTO')
                ->first();

            $remessa = MinutaEmpenhoRemessa::create([
                'minutaempenho_id' => $minuta_id,
                'situacao_id' => $situacao_andamento->id,
                'remessa' => 0
            ]);

            $itens = array_map(
                function ($itens) use ($minuta_id, $remessa) {
                    $itens['minutaempenho_id'] = $minuta_id;
                    $itens['minutaempenhos_remessa_id'] = $remessa->id;
                    return $itens;
                },
                $itens
            );

//            dd($itens);

            $codigoitem = Codigoitem::find($minuta->tipo_empenhopor_id);

//            dump($codigoitem->descricao);
//            dump($itens);

            if ($codigoitem->descricao == 'Contrato') {
                ContratoItemMinutaEmpenho::insert($itens);
            } else {
                CompraItemMinutaEmpenho::insert($itens);
            }

            //dd($itens, $teste, CompraItemMinutaEmpenho::where('minutaempenhos_remessa_id',$remessa->id)->get());

            $minuta->etapa = 4;
            $minuta->save();

            DB::commit();

            return redirect()->route('empenho.minuta.gravar.saldocontabil', ['minuta_id' => $minuta_id]);
        } catch (Exception $exc) {
            DB::rollback();
            throw $exc;
        }
    }

    public function update(Request $request)
    {
        $minuta = MinutaEmpenho::find($request->minuta_id);

        $minuta_id = $request->minuta_id;
        $fornecedor_id = $request->fornecedor_id;
        $itens = $request->itens;

        if (!isset($itens)) {
            Alert::error('Escolha pelo menos 1 item.')->flash();
            return redirect()->route(
                'empenho.minuta.etapa.item',
                ['minuta_id' => $minuta_id, 'fornecedor_id' => $fornecedor_id]
            );
        }


        DB::beginTransaction();
        try {
            $codigoitem = Codigoitem::find($minuta->tipo_empenhopor_id);

            if ($codigoitem->descricao == 'Contrato') {
                $cime = ContratoItemMinutaEmpenho::where('minutaempenho_id', $minuta_id);
                $cime_deletar = $cime->get();
                $cime->delete();
                $remessa_id = $minuta->remessa[0]->id;
                $itens = array_map(
                    function ($itens) use ($minuta_id, $remessa_id) {
                        $itens['minutaempenho_id'] = $minuta_id;
                        $itens['minutaempenhos_remessa_id'] = $remessa_id;
                        return $itens;
                    },
                    $itens
                );

                ContratoItemMinutaEmpenho::insert($itens);
            } else {
                $cime = CompraItemMinutaEmpenho::where('minutaempenho_id', $minuta_id);
                $cime_deletar = $cime->get();
                $cime->delete();
                $remessa_id = $minuta->remessa[0]->id;

                foreach ($cime_deletar as $item) {
                    $compraItemUnidade = CompraItemUnidade::where('compra_item_id', $item->compra_item_id)
                        ->where('unidade_id', session('user_ug_id'))
                        ->first();

                    $compraItemUnidade->quantidade_saldo = $this->retornaSaldoAtualizado($item->compra_item_id)->saldo;
                    $compraItemUnidade->save();
                }

                $itens = array_map(
                    function ($itens) use ($minuta_id, $remessa_id) {
                        $itens['minutaempenho_id'] = $minuta_id;
                        $itens['minutaempenhos_remessa_id'] = $remessa_id;
                        return $itens;
                    },
                    $itens
                );

                CompraItemMinutaEmpenho::insert($itens);
            }

            $minuta->etapa = 4;
            $minuta->save();
            DB::commit();

            return redirect()->route('empenho.minuta.gravar.saldocontabil', ['minuta_id' => $minuta_id]);
        } catch (Exception $exc) {
            DB::rollback();
            throw $exc;
            return redirect()->back();
        }
    }

//    private function retornaItensAcoes($id, $minuta_id)
//    {
//        $acoes = '';
//        $acoes .= $this->retornaRadioItens($id);
//
//        return $acoes;
//    }
}
