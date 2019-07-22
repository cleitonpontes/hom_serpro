<?php

namespace App\Http\Controllers\Painel;

use App\Forms\MeusdadosForm;
use App\Forms\MudarUgForm;
use App\Http\Controllers\Controller;
use App\Models\BackpackUser;
use App\Models\CalendarEvent;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Unidade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MaddHatter\LaravelFullcalendar\Calendar;

class FinanceiroController extends Controller
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
        $this->data['title'] = "Painel Financeiro";//trans('backpack::base.dashboard'); // set the page title

        $colors = [
            '#1f77b4',
            '#aec7e8',
            '#ff7f0e',
            '#ffbb78',
            '#2ca02c',
            '#98df8a',
            '#d62728',
            '#ff9896',
            '#9467bd',
            '#c5b0d5',
            '#8c564b',
            '#c49c94',
            '#e377c2',
            '#f7b6d2',
            '#7f7f7f',
            '#c7c7c7',
            '#bcbd22',
            '#dbdb8d',
            '#17becf',
            '#9edae5'
        ];

//        shuffle($colors);

        $categoria_contrato = Codigoitem::whereHas('codigo', function ($q) {
            $q->where('descricao', '=', 'Categoria Contrato');
        })
            ->join('contratos', function ($join) {
                $join->on('codigoitens.id', '=', 'contratos.categoria_id');
            })
            ->orderBy('codigo_id', 'asc')->pluck('descricao')->toArray();

        $cat = array_unique($categoria_contrato);

        $categorias = [];
        foreach ($cat as $c) {
            $categorias[] = $c;
        }

        $contrato = DB::table('contratos')
            ->select(DB::raw('categoria_id, count(categoria_id)'))
            ->where('situacao', '=', true)
            ->orderBy('categoria_id', 'asc')
            ->groupBy('categoria_id')
            ->pluck('count')->toArray();

        $chartjs = app()->chartjs
            ->name('pieChartTest')
            ->type('doughnut')
            ->size(['width' => 400, 'height' => 200])
            ->labels($categorias)
            ->datasets([
                [
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                    'data' => $contrato,
                ]
            ])
            ->options([
                'plugins' => [
                    'colorschemes' => [
                        'scheme' => 'brewer.PiYG6',
                    ]
                ]
            ]);


        return view('backpack::mod.paineis.painelorcamentario', ['data' => $this->data, 'chartjs' => $chartjs]);
    }

}
