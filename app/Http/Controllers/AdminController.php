<?php

namespace App\Http\Controllers;

use App\Forms\MeusdadosForm;
use App\Models\BackpackUser;
use App\Models\CalendarEvent;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Unidade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        if (!session()->get('user_ug') AND !session()->get('user_ug_id')) {
            $ug = backpack_user()->ugprimaria;
            if ($ug) {
                $unidade = backpack_user()->unidadeprimaria($ug);
                session(['user_ug' => $unidade->codigo]);
                session(['user_ug_id' => $ug]);
            } else {
                session(['user_ug' => null]);
                session(['user_ug_id' => null]);
            }
        }

        $events = [];
        $data = CalendarEvent::all();
        if (session()->get('user_ug_id')) {
            $data->where('unidade_id', session()->get('user_ug_id'));
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

        $categoria_contrato = Codigoitem::whereHas('codigo', function ($q){
            $q->where('descricao', '=', 'Categoria Contrato');
        })->orderBy('codigo_id', 'asc')->pluck('descricao')->toArray();

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
            ->labels($categoria_contrato)
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

    public function meusdados()
    {
        $user = BackpackUser::find(backpack_user()->id);

        $ug = Unidade::find($user->ugprimaria)->pluck('codigo', 'id')->toArray();;

        $form = \FormBuilder::create(MeusdadosForm::class, [
            'url' => route('inicio.meusdados.atualiza'),
            'method' => 'PUT',
            'model' => $user,
            'data' => [
                'senhasiafi' => $user->senhasiafi,
                'ugprimaria' => $ug,
            ]
        ]);

        return view('backpack::base.auth.account.meusdados', compact('form'));

    }

    public function meusdadosatualiza()
    {
        $user = BackpackUser::find(backpack_user()->id);

        $form = \FormBuilder::create(MeusdadosForm::class, [
            'data' => ['users' => $user->id]
        ]);

        if (!$form->isValid()) {
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        $data = $form->getFieldValues();
        $data['cpf'] = $user->cpf;
        $data['ugprimaria'] = $user->ugprimaria;
        $data['senhasiafi'] = base64_encode($data['senhasiafi']);
        $user->update($data);

        \Alert::success('Seus Dados foram atualizados!')->flash();
//        \toast()->success('Seus Dados foram atualizados!', 'Sucesso');

        return redirect()->route('inicio.meusdados');

    }
}
