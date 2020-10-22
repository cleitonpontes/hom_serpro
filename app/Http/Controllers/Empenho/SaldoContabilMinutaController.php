<?php
/**
 * Controller com métodos e funções da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */

namespace App\Http\Controllers\Empenho;


use App\Http\Controllers\Empenho\Minuta\BaseControllerEmpenho;
use App\Models\SaldoContabil;
use App\Models\Unidade;
use App\Models\MinutaEmpenho;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Route;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use App\STA\ConsultaApiSta;



class SaldoContabilMinutaController extends BaseControllerEmpenho
{
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index(Request $request)
    {

        $minuta_id = Route::current()->parameter('minuta_id');

        $saldosContabeis = SaldoContabil::join('unidades', 'unidades.id', '=', 'saldo_contabil.unidade_id')
                        ->select([
                                    'saldo_contabil.id',
                                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,1,1) AS esfera"),
                                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,2,6) AS ptrs"),
                                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,8,10) AS fonte"),
                                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6) AS nd"),
                                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,24,8) AS ugr"),
                                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,32,11) AS plano_interno"),
                                    'saldo_contabil.saldo',
                        ])
                        ->where(DB::raw("SUBSTRING(saldo_contabil.conta_corrente,22,2)"),'<>','00')
                        ->get()
                        ->toArray();

        if ($request->ajax()) {
            return DataTables::of($saldosContabeis)
                ->addColumn('intro', function ($saldosContabeis) use ($minuta_id) {
                    $acoes = $this->retornaAcoes($saldosContabeis['id'], $minuta_id);
                    return $acoes;
                })
                ->addColumn('action', function ($saldosContabeis) use ($minuta_id) {
                    $btn = $this->retornaBtAtualizar($saldosContabeis['id'], $minuta_id);
                    return $btn;
                })

                ->make(true);
        }

        $html = $this->retornaGrid();

        return view('backpack::mod.empenho.Etapa4SaldoContabil', compact('html'));
    }


    public function consultaApiSta($ano,$ug,$gestao,$contacontabil)
    {
        $apiSta = new ConsultaApiSta();
        $saldoContabil = $apiSta->saldocontabilAnoUgGestaoContacontabil($ano,$ug,$gestao,$contacontabil);
        return $saldoContabil;
    }

    public function store(){
        $unidade = Unidade::where('codigo',session('user_ug'))->first();
        $ano = date('Y');
        $ug = $unidade->codigo;
        $gestao = $unidade->gestao;
        $contacontabil = config('app.conta_contabil_credito_disponivel');

        $saldosContabeis = json_encode($this->consultaApiSta($ano,$ug,$gestao,$contacontabil));

        foreach (json_decode($saldosContabeis) as $key => $saldo){
            $this->gravaSaldoContabil($ano,$unidade->id,$saldo->contacorrente,$contacontabil,$saldo->saldo);
        }

        return redirect()->route('empenho.minuta.listagem.saldocontabil');

    }


    public function gravaSaldoContabil($ano,$unidade_id,$contacorrente,$contacontabil,$saldo)
    {
        $saldoContabil = SaldoContabil::updateOrCreate(
            ['ano'=> $ano,'unidade_id' => $unidade_id,'conta_corrente' => $contacorrente,'conta_contabil' => $contacontabil],
            ['saldo' => $saldo]
        );
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
                'name' => 'intro',
                'title' => 'Selecionar',
                'orderable' => false,
                'searchable' => false
            ])
            ->addColumn([
                'data' => 'esfera',
                'name' => 'esfera',
                'title' => 'Esfera',
            ])
            ->addColumn([
                'data' => 'ptrs',
                'name' => 'ptrs',
                'title' => 'PTRS'
            ])
            ->addColumn([
                'data' => 'fonte',
                'name' => 'fonte',
                'title' => 'Fonte'
            ])
            ->addColumn([
                'data' => 'nd',
                'name' => 'nd',
                'title' => 'Natureza da Despesa'
            ])
            ->addColumn([
                'data' => 'ugr',
                'name' => 'ugr',
                'title' => 'UGR'
            ])
            ->addColumn([
                'data' => 'plano_interno',
                'name' => 'plano_interno',
                'title' => 'Plano Interno'
            ])
            ->addColumn([
                'data' => 'saldo',
                'name' => 'saldo',
                'title' => 'Valor'
            ])
            ->addColumn([
                'data' => 'action',
                'name' => 'action2',
                'title' => 'Ações',
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


    /**
     * Retorna html das ações disponíveis
     *
     * @param number $id
     * @return string
     */
    private function retornaAcoes($id, $minuta_id)
    {
        $acoes = '';
        $acoes .= '<a href="" ';
        $acoes .= "class='btn btn-default btn-sm' ";
        $acoes .= 'title="Selecionar este fornecedor">';
        $acoes .= '<i class="fa fa-check-circle"></i></a>';

        return $acoes;
    }

    /**
     * Retorna html das ações disponíveis
     *
     * @param number $id
     * @return string
     */
    private function retornaBtAtualizar($id, $minuta_id)
    {
        $btn = '';
        $btn .= '<a href="#"';
        $btn .= '"Selecionar ';
        $btn .= "class='btn btn-default btn-sm' ";
        $btn .= 'title="Atualizar Saldo">';
        $btn .= '<i class="fa fa-refresh"></i></a>';

        return $btn;
    }




}
