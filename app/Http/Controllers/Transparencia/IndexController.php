<?php

namespace App\Http\Controllers\Transparencia;

use App\Forms\FiltroRelatorioContratosForm;
use App\Forms\MeusdadosForm;
use App\Forms\MudarUgForm;
use App\Forms\TransparenciaIndexForm;
use App\Http\Controllers\Controller;
use App\Models\BackpackUser;
use App\Models\CalendarEvent;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Orgao;
use App\Models\Unidade;
use Illuminate\Http\Request;
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
    public function index(Request $request)
    {
        $this->data['title'] = "Área Consulta Pública";//trans('backpack::base.dashboard'); // set the page title

        if ($request->query()) {
            $filtro = $request->input();
        }

        $form = \FormBuilder::create(TransparenciaIndexForm::class,
            [
                'method' => 'GET',
                'model' => ($request->input()) ? $request->input() : '',
                'url' => route('transparencia.index'),
            ]
        );

        if ($request->input()) {
            $data = $form->getFieldValues();
        }

        if ($request->query()) {
            $filtro = $request->input();
        }

        return view('backpack::consultapublica',[
            'data' => $this->data,
            'form' => $form,
        ]);
    }

}
