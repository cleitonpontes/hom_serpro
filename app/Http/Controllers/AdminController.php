<?php

namespace App\Http\Controllers;

use App\Models\BackpackUser;
use App\Models\CalendarEvent;
use App\Models\Unidade;
use Illuminate\Support\Facades\Auth;
use MaddHatter\LaravelFullcalendar\Calendar;

class AdminController extends Controller
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
        $this->data['title'] = "Início";//trans('backpack::base.dashboard'); // set the page title

        if (!session()->get('user_ug')) {
            $ug = backpack_user()->ugprimaria;
            if ($ug) {
                $unidade = backpack_user()->unidadeprimaria($ug);
                session(['user_ug' => $unidade->codigo]);
            } else {
                session(['user_ug' => null]);
            }
        }

        $events = [];
        $data = CalendarEvent::all();
        if (session()->get('user_ug')) {
            $ug2 = Unidade::where('codigo', session()->get('user_ug'))->first();
            $data->where('unidade_id', $ug2->id);
        }

        if ($data->count()) {
            foreach ($data as $key => $value) {
                $events[] = \Calendar::event(
                    $value->title,
                    true,
                    new \DateTime($value->start_date),
                    new \DateTime($value->end_date . ' +1 day'),
                    null,
                    // Add color and link on event
                    [
                        'color' => '#619aef',
                    ]
                );
            }
        }

        $calendar = \Calendar::addEvents($events)->setOptions([
            'first_day' => 1,
//            'aspectRatio' => 2.5,
        ])->setCallbacks([]);



        $chartjs = app()->chartjs
            ->name('pieChartTest')
            ->type('doughnut')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['Comuns', 'Locação de Imóveis', 'Outros'])
            ->datasets([
                [
                    'backgroundColor' => ['#31ABFF', '#FF4242', '#00B85F'],
//                    'backgroundColor' => $default_colors,
                    'data' => [33, 60, 7],
                ]
            ])
            ->options([
                'plugins' => [
                    'colorschemes' => [
                        'scheme' => 'brewer.PiYG6',
                    ]
                ]
            ]);


        return view('backpack::dashboard', ['calendar' => $calendar, 'data' => $this->data, 'chartjs' => $chartjs]);
    }

    /**
     * Redirect to the dashboard.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
        return redirect(backpack_url('inicio'));
    }
}