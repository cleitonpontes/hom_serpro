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
        $etapa_id = Route::current()->parameter('etapa_id');
        $saldosContabeis = SaldoContabil::retornaSaldos();

        if ($request->ajax()) {
            return DataTables::of($saldosContabeis)
                ->addColumn(
                    'action',
                    function ($saldosContabeis) use ($minuta_id)
                    {
                        return $this->retornaBtnSelecao($saldosContabeis['id'], $minuta_id);
                    }
                )
                ->addColumn(
                    'btn_atualizar',
                    function ($saldosContabeis) use ($minuta_id)
                    {
                        return $this->retornaBtAtualizar($saldosContabeis['id'], $minuta_id);
                    }
                )
                ->rawColumns(['action','btn_atualizar'])
                ->make(true);
        }

        $html = $this->retornaGrid();

        return view('backpack::mod.empenho.Etapa4SaldoContabil', compact('html'))
            ->with('minuta_id', $minuta_id)
            ->with('etapa_id',$etapa_id);
    }


    public function consultaApiSta($ano,$ug,$gestao,$contacontabil)
    {
        $apiSta = new ConsultaApiSta();
        $saldoContabil = $apiSta->saldocontabilAnoUgGestaoContacontabil($ano,$ug,$gestao,$contacontabil);
        return $saldoContabil;
    }

    public function store(){

        $minuta_id = Route::current()->parameter('minuta_id');
        $etapa_id = Route::current()->parameter('etapa_id');

        $unidade = Unidade::where('codigo',session('user_ug'))->first();
        $ano = date('Y');
        $ug = $unidade->codigo;
        $gestao = $unidade->gestao;
        $contacontabil = config('app.conta_contabil_credito_disponivel');

        $saldosContabeis = json_encode($this->consultaApiSta($ano,$ug,$gestao,$contacontabil));

        foreach (json_decode($saldosContabeis) as $key => $saldo){
            $saldocontabil = new SaldoContabil();
            $saldocontabil->gravaSaldoContabil($ano,$unidade->id,$saldo->contacorrente,$contacontabil,$saldo->saldo);
        }

        return redirect()->route('empenho.minuta.listagem.saldocontabil',['etapa_id' => ($etapa_id + 1), 'minuta_id' => $minuta_id]);

    }


//    public function gravaSaldoContabil($ano,$unidade_id,$contacorrente,$contacontabil,$saldo)
//    {
//        $saldoContabil = SaldoContabil::updateOrCreate(
//            ['ano'=> $ano,'unidade_id' => $unidade_id,'conta_corrente' => $contacorrente,'conta_contabil' => $contacontabil],
//            ['saldo' => $saldo]
//        );
//    }


    public function atualizaMinuta(Request $request)
    {

        $minuta_id = $request->get('minuta_id');
        $etapa_id = $request->get('etapa_id');
        $saldo_contabil_id = $request->get('saldo');

        $modMinuta = MinutaEmpenho::find($minuta_id);
        $modMinuta->etapa = $etapa_id;
        $modMinuta->saldo_contabil_id = $saldo_contabil_id;
        $modMinuta->save();
        return redirect()->route('empenho.minuta.etapa.subelemento',['etapa_id' => ($etapa_id + 1), 'minuta_id' => $minuta_id]);

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
                'data' => 'btn_atualizar',
                'name' => 'btn_atualizar',
                'title' => 'Selecione'
            ])
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
                    7,
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
        $btn .= "<input type='radio' class='custom-control-input' id=saldo_".$id." name='saldo' value=".$id.">";
        return $btn;
    }


    private function retornaAcoes($id, $minuta_id)
    {
        $selecionar = $this->retornaBtnSelecao($id, $minuta_id);
        $botoes = $selecionar;

        $acoes = '';
        $acoes .= '<div class="btn-group">';
        $acoes .= $botoes;
        $acoes .= '</div>';

        return $acoes;
    }


}
