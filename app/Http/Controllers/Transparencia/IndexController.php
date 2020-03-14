<?php

namespace App\Http\Controllers\Transparencia;

use App\Forms\MeusdadosForm;
use App\Forms\MudarUgForm;
use App\Http\Controllers\Controller;
use App\Models\BackpackUser;
use App\Models\CalendarEvent;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Orgao;
use App\Models\Unidade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MaddHatter\LaravelFullcalendar\Calendar;

class IndexController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->data['title'] = "Área Consulta Pública";//trans('backpack::base.dashboard'); // set the page title

        $orgaos_array = Orgao::select(DB::raw("CONCAT(codigo,' - ',nome) AS nome"), 'codigo')
            ->where('situacao',true)
            ->whereHas('unidades', function ($u){
                $u->whereHas('contratos', function ($c){
                    $c->where('situacao',true);
                });
            })
            ->orderBy('codigo', 'asc')
            ->pluck('nome', 'codigo')
            ->toArray();

        $unidades_array = Unidade::select(DB::raw("CONCAT(codigo,' - ',nomeresumido) AS nome"), 'codigo')
            ->where('situacao',true)
            ->whereHas('contratos', function ($c){
                $c->where('situacao',true);
            })
            ->orderBy('codigo', 'asc')
            ->pluck('nome', 'codigo')
            ->toArray();

        $contratos = Contrato::where('situacao',true)->get();


        return view('backpack::consultapublica',[
            'data' => $this->data,
        ]);
    }

}
