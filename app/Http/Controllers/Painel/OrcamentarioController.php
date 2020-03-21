<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\BackpackUser;
use App\Models\Empenho;
use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\DataTables;

class OrcamentarioController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct(Builder $htmlBuilder)
    {
        $this->htmlBuilder = $htmlBuilder;
        $this->middleware(backpack_middleware());
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = "Painel Orçamentário";//trans('backpack::base.dashboard'); // set the page title

        $empenhos = new Empenho();
        $dadosEmpenhosOutrasDespesasCorrentes = $empenhos->retornaDadosEmpenhosGroupUgArray();
        $graficoEmpenhosOutrasDespesasCorrentes = $this->graficoEmpenhosOutrasDespesasCorrentes($dadosEmpenhosOutrasDespesasCorrentes);


        $totais_linha = [];
        $t_empenhado = 0;
        $t_aliquidar = 0;
        $t_liquidado = 0;
        $t_pago = 0;

        foreach ($dadosEmpenhosOutrasDespesasCorrentes as $dadosEmpenhosOutrasDespesasCorrente) {
            $t_empenhado += $dadosEmpenhosOutrasDespesasCorrente['empenhado'];
            $t_aliquidar += $dadosEmpenhosOutrasDespesasCorrente['aliquidar'];
            $t_liquidado += $dadosEmpenhosOutrasDespesasCorrente['liquidado'];
            $t_pago += $dadosEmpenhosOutrasDespesasCorrente['pago'];
        }

        $totais_linha[] = [
            'nome' => 'Total',
            'empenhado' => $t_empenhado,
            'aliquidar' => $t_aliquidar,
            'liquidado' => $t_liquidado,
            'pago' => $t_pago,
        ];


        $dadosEmpenhosOutrasDespesasCorrentes = array_merge($dadosEmpenhosOutrasDespesasCorrentes,$totais_linha);


        //datatables
        if ($request->ajax()) {
            $grid = DataTables::of($dadosEmpenhosOutrasDespesasCorrentes);
            $grid->editColumn('empenhado', 'R$ {!! number_format(floatval($empenhado), 2, ",", ".") !!}');
            $grid->editColumn('aliquidar', 'R$ {!! number_format(floatval($aliquidar), 2, ",", ".") !!}');
            $grid->editColumn('liquidado', 'R$ {!! number_format(floatval($liquidado), 2, ",", ".") !!}');
            $grid->editColumn('pago', 'R$ {!! number_format(floatval($pago), 2, ",", ".") !!}');

            return $grid->make(true);
        }

        $html = $this->retornaGrid();

        return view('backpack::mod.paineis.painelorcamentario',
            [
                'data' => $this->data,
                'graficoEmpenhosOutrasDespesasCorrentes' => $graficoEmpenhosOutrasDespesasCorrentes,
                'dadosEmpenhosOutrasDespesasCorrentes' => $dadosEmpenhosOutrasDespesasCorrentes,
                'dataTable' => $html
            ]);
    }

    private function retornaGrid()
    {
        $html = $this->htmlBuilder;

        $html->addColumn([
            'data' => 'nome',
            'name' => 'nome',
            'title' => 'Unidade Gestora',
        ]);
        $html->addColumn([
            'data' => 'empenhado',
            'name' => 'empenhado',
            'title' => 'Empenhado',
            'class' => 'text-right'
        ]);
        $html->addColumn([
            'data' => 'aliquidar',
            'name' => 'aliquidar',
            'title' => 'A Liquidar',
            'class' => 'text-right'
        ]);
        $html->addColumn([
            'data' => 'liquidado',
            'name' => 'liquidado',
            'title' => 'Liquidado',
            'class' => 'text-right'
        ]);
        $html->addColumn([
            'data' => 'pago',
            'name' => 'pago',
            'title' => 'Pago',
            'class' => 'text-right'
        ]);

        $html->parameters([
            'processing' => true,
            'serverSide' => true,
            'responsive' => true,
            'info' => true,
            'autoWidth' => false,
            'bAutoWidth' => false,
            'paging' => true,
            'lengthChange' => true,
            'language' => [
                'url' => asset('/json/pt_br.json')
            ],

        ]);

        return $html;
    }

    private function graficoEmpenhosOutrasDespesasCorrentes(array $valores_empenhos)
    {

        $unidades = [];
        $empenhado = [];
        $aliquidar = [];
        $liquidado = [];
        $pago = [];

        foreach ($valores_empenhos as $v) {
            $unidades[] = $v['nome'];
            $empenhado[] = $v['empenhado'];
            $aliquidar[] = $v['aliquidar'];
            $liquidado[] = $v['liquidado'];
            $pago[] = $v['pago'];
        }

        $base = new AdminController();
        $colors = $base->colors(4);

        $chartjs = app()->chartjs
            ->name('pieChartTest')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($unidades)
            ->datasets([
                [
                    'label' => 'Empenhado',
                    'backgroundColor' => $colors[0],
                    'data' => $empenhado,
                ],
                [
                    'label' => 'A Liquidar',
                    'backgroundColor' => $colors[1],
                    'data' => $aliquidar,
                ],
                [
                    'label' => 'Liquidado',
                    'backgroundColor' => $colors[2],
                    'data' => $liquidado,
                ],
                [
                    'label' => 'Pago',
                    'backgroundColor' => $colors[3],
                    'data' => $pago,
                ],
            ])
            ->optionsRaw("{
            legend: {
                display:true
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display:true
                    }
                }]
            },
            tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                    label: function(tooltipItem, data) {
                    var label = data.datasets[tooltipItem.datasetIndex].label || '';

                    if (label) {
                        label += ': ';
                    }
                    label += new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(Math.round(tooltipItem.yLabel * 100) / 100);
                    return label;
                }
                }
            }
        }");


        return $chartjs;
    }


}
