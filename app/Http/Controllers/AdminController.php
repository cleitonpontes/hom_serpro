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

        $ug = backpack_user()->ugprimaria;
        session(['user_ug' => $ug]);


        $unidade = Unidade::where('codigo','=',session('user_ug'))->first();
        $titleFormat = "{month: 'MMMM yyyy'}";
        $events = [];
        $data = CalendarEvent::all();
        $data->where('unidade_id',$unidade->id);
        if($data->count()) {
            foreach ($data as $key => $value) {
                $events[] = \Calendar::event(
                    $value->title,
                    true,
                    new \DateTime($value->start_date),
                    new \DateTime($value->end_date.' +1 day'),
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
            'aspectRatio' => 2.5,
            ])->setCallbacks([]);

        return view('backpack::dashboard', compact('calendar'));
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