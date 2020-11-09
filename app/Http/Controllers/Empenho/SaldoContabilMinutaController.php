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
use FormBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Route;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use App\STA\ConsultaApiSta;
use App\Http\Traits\Users;
use App\Http\Traits\Formatador;

class SaldoContabilMinutaController extends BaseControllerEmpenho
{
    use Users;
    use Formatador;

    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index(Request $request)
    {

        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinuta = MinutaEmpenho::find($minuta_id);

        $unidade_id = session('user_ug_id');

        if (!empty(session('unidade_ajax_id'))) {
            $unidade_id = session('unidade_ajax_id');
        }

        $modUnidade = Unidade::find($unidade_id);

        $saldosContabeis = SaldoContabil::retornaSaldos($unidade_id);
        $saldos = $this->retornaSaldosComMascara($saldosContabeis);

        if ($request->ajax()) {
            return DataTables::of($saldos)
                ->addColumn(
                    'action',
                    function ($saldos){
                        return $this->retornaBtnAtualizar($saldos['id']);
                    }
                )
                ->addColumn(
                    'btn_selecionar',
                    function ($saldos){
                        return $this->retornaBtSelecionar($saldos['id']);
                    }
                )
                ->rawColumns(['action', 'btn_selecionar'])
                ->make(true);
        }

        $html = $this->retornaGrid();

        $form = $this->retonaFormModal($modUnidade->id, $minuta_id);

        return view('backpack::mod.empenho.Etapa4SaldoContabil', compact(['html', 'form']))
            ->with('minuta_id', $modMinuta->id)
            ->with('fornecedor_id', $modUnidade->fornecedor_compra_id)
            ->with('unidades', $this->buscaUg())
            ->with('modUnidade', $modUnidade);
    }

    public function retornaSaldosComMascara($saldos)
    {
        $saldosPtBr = array_map(function ($saldos) {
            if ($saldos['saldo']) {
                $saldos['saldo'] = str_replace(',', '.', $saldos['saldo']);
                $saldos['saldo'] = str_replace_last('.', ',', $saldos['saldo']);
            }
            return $saldos;
        }, $saldos);
        return $saldosPtBr;
    }


    public function consultaApiSta($ano, $ug, $gestao, $contacontabil)
    {
        $apiSta = new ConsultaApiSta();
        $saldoContabil = $apiSta->saldocontabilAnoUgGestaoContacontabil($ano, $ug, $gestao, $contacontabil);
        return $saldoContabil;
    }


    public function store()
    {

        $minuta_id = Route::current()->parameter('minuta_id');

        $unidade = Unidade::where('codigo', session('user_ug'))->first();
        $ano = date('Y');
        $ug = $unidade->codigo;
        $gestao = $unidade->gestao;
        $contacontabil = config('app.conta_contabil_credito_disponivel');

        $saldosApiSta = $this->consultaApiSta($ano, $ug, $gestao, $contacontabil);
        
        if (count($saldosApiSta) > 0){
            $saldosContabeis = json_encode($saldosApiSta);
            DB::beginTransaction();
            try {
                foreach (json_decode($saldosContabeis) as $key => $saldo) {
                    $modSaldoContabil = new SaldoContabil();
                    $modSaldoContabil->existeSaldoContabil($ano, $saldo, $unidade->id, $contacontabil);
                }
                DB::commit();
            } catch (\Exception $exc) {
                DB::rollback();
            }
        }
        return redirect()->route(
            'empenho.minuta.etapa.saldocontabil',
            [
                'minuta_id' => $minuta_id
            ]
        );
    }

    public function atualizaMinuta(Request $request)
    {

        if (!$request->get('saldo')) {
            \Alert::error('Selecione o Saldo Contábil.')->flash();
            return redirect()->back();
        }

        $minuta_id = $request->get('minuta_id');
        $saldo_contabil_id = $request->get('saldo');

        $modMinuta = MinutaEmpenho::find($minuta_id);
        $modMinuta->etapa = 5;
        $modMinuta->saldo_contabil_id = $saldo_contabil_id;
        $modMinuta->save();

        return redirect()->route('empenho.minuta.etapa.subelemento', [ 'minuta_id' => $minuta_id]);
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
        $modSaldo->saldo = $this->retornaFormatoAmericano($saldo);
        $modSaldo->save();

        return redirect()->route(
            'empenho.minuta.etapa.saldocontabil',
            [
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


    /**
     * Monta $html com definições do Grid
     *
     * @return Builder
     */
    private function retornaGrid()
    {

        $html = $this->htmlBuilder
            ->addColumn([
                'data' => 'btn_selecionar',
                'name' => 'btn_selecionar',
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


    private function retornaBtnAtualizar($id)
    {
        $btnUpdate = '';
        $btnUpdate .= '<button type="button" class="btn btn-primary btn-sm" id='.$id.' name=atualiza_saldo_acao_'.$id.">";
        $btnUpdate .= '<i class="fa fa-refresh"></i></button>';

//        $btnUpdate = '';
//        $btnUpdate .= '<button type="button" class="btn btn-primary btn-sm" id='.$id.'" name="atualiza_saldo_acao">';
//        $btnUpdate .= "<a href='/api/atualizasaldos/linha/$id'";
//        $btnUpdate .= "title='Atualizar Saldo'>";
////        $btnUpdate .= "name='atualiza_'".$id.">";
////        $btnUpdate .= "id='$id>";
//        $btnUpdate .= "<i class='fa fa-refresh'></i></a></button>";

        return $btnUpdate;

    }


    private function retornaBtSelecionar($id)
    {
        $btn = '';
        $btn .= "<input type='radio' class='custom-control-input' id=saldo_" . $id . " name='saldo' value=" . $id . ">";
        return $btn;
    }


    private function retornaAcoes($id, $minuta_id)
    {
        $selecionar = $this->retornaBtnAtualizar($id, $minuta_id);
        $botoes = $selecionar;

        $acoes = '';
        $acoes .= '<div class="btn-group">';
        $acoes .= $botoes;
        $acoes .= '</div>';

        return $acoes;
    }

    public function retonaFormModal($unidade_id, $minuta_id)
    {
        return FormBuilder::create(InserirCelulaOrcamentariaForm::class, [
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
