<?php

namespace App\Http\Controllers;

use App\Forms\MeusdadosForm;
use App\Forms\MudarUgForm;
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

        $events = $this->getEvents();

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


        return view('backpack::dashboard', ['calendar' => $calendar, 'data' => $this->data, 'chartjs' => $chartjs]);
    }


    protected function getEvents()
    {

        $events = [];
        $eventsCollections = $this->getCalendarEvents();
        if ($eventsCollections->count()) {
            foreach ($eventsCollections as $key => $value) {
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
        return $events;
    }

    protected function getCalendarEvents()
    {

        $eventsCollections = CalendarEvent::all();
        if (session()->get('user_ug_id')) {
            $eventsCollections = $eventsCollections->where('unidade_id', session()->get('user_ug_id'));
        }
        return $eventsCollections;
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

        $ug = [];

        if ($user->ugprimaria) {
            $ug = Unidade::find($user->ugprimaria)->pluck('codigo', 'id')->toArray();
        }


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


    public function mudarUg()
    {
        $ug = $this->buscaUg();

        $form = \FormBuilder::create(MudarUgForm::class, [
            'url' => route('inicio.mudaug'),
            'data' => ['ugs' => $ug],
            'method' => 'PUT',
//            'model' => $user,
        ]);


        return view('backpack::base.auth.account.mudarug', compact('form'));

    }

    public function buscaUg()
    {

        $ug = [];

        $ugprimaria = Unidade::select(DB::raw("CONCAT(codigo,' - ',nomeresumido) AS nome"), 'id')
            ->where('id', '=', backpack_user()->ugprimaria)
            ->where('tipo', '=', 'E')
            ->pluck('nome', 'id')
            ->toArray();

        $ugsecundaria = Unidade::select(DB::raw("CONCAT(codigo,' - ',nomeresumido) AS nome"), 'id')
            ->whereHas('users', function ($query) {
                $query->where('user_id', '=', backpack_user()->id);
            })
            ->where('tipo', '=', 'E')
            ->pluck('nome', 'id')
            ->toArray();

        $ug = $ugprimaria + $ugsecundaria;

        asort($ug);

        return $ug;

    }

    public function mudaUg()
    {
        $ug = $this->buscaUg();

        $form = \FormBuilder::create(MudarUgForm::class, [
            'data' => ['ug' => $ug]
        ]);

        if (!$form->isValid()) {
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        $data = $form->getFieldValues();

        if (!$data['ug'] == '') {
            $unidade = Unidade::find($data['ug']);
            session(['user_ug' => $unidade->codigo]);
            session(['user_ug_id' => $unidade->id]);
        } else {
            session(['user_ug' => null]);
            session(['user_ug_id' => null]);
        }


        \Alert::success('Unidade alterada com sucesso!')->flash();

        return redirect()->to('/inicio');

    }

    public function listaMensagens()
    {
        $mensagens = backpack_user()->notifications()->paginate(10);

        return view('backpack::mensagens',['mensagens' => $mensagens]);
    }

    public function lerMensagem($id)
    {
        $notificacao = backpack_user()->notifications()->find($id);
        $notificacao->update(['read_at' => now()]);
        return view('backpack::mensagem',['notificacao' => $notificacao]);
    }
}
