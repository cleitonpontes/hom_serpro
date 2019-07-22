<?php

namespace App\Http\Controllers\Painel;

use App\Forms\MeusdadosForm;
use App\Forms\MudarUgForm;
use App\Http\Controllers\Controller;
use App\Models\BackpackUser;
use App\Models\CalendarEvent;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Empenho;
use App\Models\Unidade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MaddHatter\LaravelFullcalendar\Calendar;

class OrcamentarioController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
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

        $graficoempenhos = $this->graficoEmpenhos();


        return view('backpack::mod.paineis.painelorcamentario',
            ['data' => $this->data, 'graficoempenhos' => $graficoempenhos]);
    }

    private function graficoEmpenhos()
    {

        $unidades = Unidade::select(DB::raw("codigo ||' - '|| nomeresumido as nome"))
            ->where('tipo', 'E')
            ->where('situacao', true)
            ->orderBy('id', 'asc')
            ->pluck('nome')->toArray();

        $valores_empenhos = Empenho::whereHas('unidade', function ($q) {
            $q->where('situacao', '=', true);
        });
        $valores_empenhos->leftjoin('unidades', 'empenhos.unidade_id', '=', 'unidades.id');
        $valores_empenhos->orderBy('unidades.id');
        $valores_empenhos->groupBy('unidades.id');
        $valores_empenhos->select([
            "unidades.id",
            DB::raw('sum(empenhos.empenhado) as empenhado'),
            DB::raw("sum(empenhos.aliquidar) as aliquidar"),
            DB::raw("sum(empenhos.liquidado) as liquidado"),
            DB::raw("sum(empenhos.pago) as pago")
        ]);

        $empenhado = [];
        $aliquidar = [];
        $liquidado = [];
        $pago = [];

        foreach ($valores_empenhos->get()->toArray() as $v) {
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
            ->options([]);

        return $chartjs;
    }

    public function colors(int $quantidade)
    {
        $colors = [];
        for ($i = 0; $i < $quantidade; $i++) {
            $r = number_format(rand(0,255),0,'','');
            $g = number_format(rand(0,255),0,'','');
            $b = number_format(rand(0,255),0,'','');

            $colors[] = "rgba(".$r.",".$g.",".$b.", 0.5)";
        }

        return $colors;
    }

}
