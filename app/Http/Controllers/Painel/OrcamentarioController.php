<?php

namespace App\Http\Controllers\Painel;

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
    public function index()
    {
        $this->data['title'] = "Painel Orçamentário";//trans('backpack::base.dashboard'); // set the page title

        $dadosEmpenhosOutrasDespesasCorrentes = $this->retornaDadosEmpenhosOutrasDespesasCorrentes();
        $graficoEmpenhosOutrasDespesasCorrentes = $this->graficoEmpenhosOutrasDespesasCorrentes($dadosEmpenhosOutrasDespesasCorrentes);


        return view('backpack::mod.paineis.painelorcamentario',
            [
                'data' => $this->data,
                'graficoEmpenhosOutrasDespesasCorrentes' => $graficoEmpenhosOutrasDespesasCorrentes,
                'dadosEmpenhosOutrasDespesasCorrentes' => $dadosEmpenhosOutrasDespesasCorrentes
            ]);
    }

    private function retornaDadosEmpenhosOutrasDespesasCorrentes()
    {

        $valores_empenhos = Empenho::whereHas('unidade', function ($q) {
            $q->where('situacao', '=', true);
        });
        $valores_empenhos->whereHas('naturezadespesa', function ($q) {
            $q->where('codigo', 'LIKE', '33%');
        });
        $valores_empenhos->leftjoin('unidades', 'empenhos.unidade_id', '=', 'unidades.id');
        $valores_empenhos->orderBy('nome');
        $valores_empenhos->groupBy('unidades.codigo');
        $valores_empenhos->groupBy('unidades.nomeresumido');
        $valores_empenhos->select([
            DB::raw("unidades.codigo ||' - '||unidades.nomeresumido as nome"),
            DB::raw('sum(empenhos.empenhado) as empenhado'),
            DB::raw("sum(empenhos.aliquidar) as aliquidar"),
            DB::raw("sum(empenhos.liquidado) as liquidado"),
            DB::raw("sum(empenhos.pago) as pago")
        ]);

        return $valores_empenhos->get()->toArray();

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

        $colors = $this->colors(4);

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

    public function colors(int $quantidade)
    {
        $colors = [];
        for ($i = 0; $i < $quantidade; $i++) {
            $r = number_format(rand(0, 255), 0, '', '');
            $g = number_format(rand(0, 255), 0, '', '');
            $b = number_format(rand(0, 255), 0, '', '');

            $colors[] = "rgba(" . $r . "," . $g . "," . $b . ", 0.5)";
        }

        return $colors;
    }

}
