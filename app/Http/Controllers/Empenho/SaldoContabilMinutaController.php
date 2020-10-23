<?php
/**
 * Controller com métodos e funções da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */

namespace App\Http\Controllers\Empenho;


use App\Forms\MudarUgForm;
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
            return DataTables::of($saldosContabeis)->addColumn('action', function ($saldosContabeis) use ($minuta_id) {
                    $acoes = $this->retornaAcoes($saldosContabeis['id'], $minuta_id);
                    return $acoes;
                })
                ->make(true);
        }

        $html = $this->retornaGrid();
//        dd(session()->all());
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

    public function mudarUg()
    {
        $ug = $this->buscaUg();

        $form = \FormBuilder::create(MudarUgForm::class, [
            'url' => route('#'),
            'data' => ['ugs' => $ug],
            'method' => 'PUT',
//            'model' => $user,
        ]);

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
                'data' => 'esfera',
                'name' => 'esfera',
                'title' => 'Esfera',
                'class' => 'text-center'
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
                'name' => 'action',
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


    private function retornaBtnSelecao($id, $minuta_id)
    {
        $btnsel = '';
        $btnsel .= '<a href="empenho/minuta/subelemento/'.$id.'"';
        $btnsel .= "class='btn btn-default btn-sm' ";
        $btnsel .= 'title="Selecionar este fornecedor">';
        $btnsel .= '<i class="fa fa-check-circle"></i></a>';

        return $btnsel;
    }


    private function retornaBtAtualizar($id, $minuta_id)
    {
        $btn = '';
        $btn .= '<a href="empenho/atualiza/saldo/'.$id.'"';
        $btn .= "class='btn btn-default btn-sm' ";
        $btn .= 'title="Atualizar Saldo">';
        $btn .= '<i class="fa fa-refresh"></i></a>';

        return $btn;
    }


    private function retornaAcoes($id, $minuta_id)
    {
        $selecionar = $this->retornaBtnSelecao($id, $minuta_id);
        $atualizar = $this->retornaBtAtualizar($id, $minuta_id);

        $botoes = $selecionar .'  '.$atualizar;

        $acoes = '';
        $acoes .= '<div class="btn-group">';
        $acoes .= $botoes;
        $acoes .= '</div>';

        return $acoes;
    }


}
