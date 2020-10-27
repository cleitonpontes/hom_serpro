<?php
/**
 * Controller com métodos para Saldo Contábil da Minuta de Empenho
 *
 * @author Data-Info
 * @author Franklin Justiniano <frnaklin.linux@gmail.com>
 */

namespace App\Http\Controllers\Empenho;


use App\Forms\InserirCelulaOrcamentariaForm;
use App\Http\Controllers\Empenho\Minuta\BaseControllerEmpenho;
use App\Models\SaldoContabil;
use App\Models\Unidade;
use App\Models\MinutaEmpenho;
use Illuminate\Http\Request;
use Route;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use App\STA\ConsultaApiSta;
use App\Http\Traits\Users;



class SaldoContabilMinutaController extends BaseControllerEmpenho
{
    use Users;

    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index(Request $request)
    {

        $minuta_id = Route::current()->parameter('minuta_id');
        $etapa_id = Route::current()->parameter('etapa_id');

        $unidade_id = session('user_ug_id');
        if((session('unidade_ajax_id') !== null)){
            $unidade_id = session('unidade_ajax_id');
        }
        $modUnidade = Unidade::find($unidade_id);

        $saldosContabeis = SaldoContabil::retornaSaldos($unidade_id);

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

        $form = $this->retonaFormModal($modUnidade->id,$minuta_id,$etapa_id);

        return view('backpack::mod.empenho.Etapa4SaldoContabil', compact(['html','form']))
            ->with('minuta_id', $minuta_id)
            ->with('etapa_id',$etapa_id)
            ->with('unidades',$this->buscaUg())
            ->with('modUnidade',$modUnidade);
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

        return redirect()->route(
            'empenho.minuta.listagem.saldocontabil',
                [
                'etapa_id' => ($etapa_id + 1),
                'minuta_id' => $minuta_id
                ]
        );

    }

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

    public function inserirCelulaOrcamentaria(Request $request)
    {
        $conta_corrente = $this->retornaContaCorrente($request);
        $saldo = $request->get('valor');
        $unidade_id = $request->get('unidade_id');
        $ano = date('Y');
        $contacontabil = config('app.conta_contabil_credito_disponivel');

        $modSaldo = new SaldoContabil();
        $modSaldo->unidade_id = $unidade_id;
        $modSaldo->ano = $ano;
        $modSaldo->conta_contabil = $contacontabil;
        $modSaldo->conta_corrente = $conta_corrente;
        $modSaldo->saldo = doubleval($saldo);
        $modSaldo->save();

        return redirect()->route(
            'empenho.minuta.listagem.saldocontabil',
            [
                'etapa_id' => ($request->get('etapa_id') + 1),
                'minuta_id' => $request->get('minuta_id')
            ]
        );


    }

    public function retornaContaCorrente(Request $request)
    {
        $conta_corrente = '';
        $conta_corrente .= $request->get('esfera');
        $conta_corrente .= $request->get('ptrs');
        $conta_corrente .= $request->get('fonte');
        $conta_corrente .= $request->get('natureza_despesa');
        $conta_corrente .= (!empty($request->get('ugr'))) ? $request->get('ugr') : '        ';
        $conta_corrente .= $request->get('plano_interno');

        return $conta_corrente;
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

    public function retonaFormModal($unidade_id,$minuta_id,$etapa_id)
    {
        return $form = \FormBuilder::create(InserirCelulaOrcamentariaForm::class, [
            'url' => route('empenho.saldo.inserir.modal'),
            'method' => 'POST',
             'id' => 'form_modal'

        ])->add('unidade_id', 'hidden',[
            'value' => $unidade_id,
            'attr' => [
                'id'=>'unidade_id'
            ]
        ])->add('minuta_id', 'hidden',[
            'value' => $minuta_id,
            'attr' => [
                'id'=>'minuta_id'
            ]
        ])->add('etapa_id', 'hidden',[
            'value' => $etapa_id,
            'attr' => [
                'id'=>'etapa_id'
            ]
        ]);
    }

}
