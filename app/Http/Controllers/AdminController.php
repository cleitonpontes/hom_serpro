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
use phpDocumentor\Reflection\File;

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
            ->where('contratos.unidade_id', session()->get('user_ug_id'))
            ->orderBy('codigoitens.id', 'asc')->pluck('descricao')->toArray();


        $cat = array_unique($categoria_contrato);

        $categorias = [];
        foreach ($cat as $c) {
            $categorias[] = $c;
        }

        $contrato = DB::table('contratos')
            ->select(DB::raw('categoria_id, count(categoria_id)'))
            ->where('situacao', '=', true)
            ->where('unidade_id', session()->get('user_ug_id'))
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
            ]);

        $dados_contratos = [];
        if (session()->get('user_ug_id')) {

            $unidade = Unidade::find(session()->get('user_ug_id'));

            if (isset($unidade->orgao->configuracao->padrao_processo_marcara)) {
                session(['numprocmask' => $unidade->orgao->configuracao->padrao_processo_marcara]);
            }
            $contratos = new Contrato();
            $dados_contratos['novos'] = $contratos->buscaContratosNovosPorUg(session()->get('user_ug_id'));
            $dados_contratos['atualizados'] = $contratos->buscaContratosAtualizadosPorUg(session()->get('user_ug_id'));
            $dados_contratos['vencidos'] = $contratos->buscaContratosVencidosPorUg(session()->get('user_ug_id'));
        } else {
            $dados_contratos['novos'] = '0';
            $dados_contratos['atualizados'] = '0';
            $dados_contratos['vencidos'] = '0';
        }


        return view('backpack::dashboard', [
            'calendar' => $calendar,
            'data' => $this->data,
            'chartjs' => $chartjs,
            'html' => $dados_contratos
        ]);
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

        return view('backpack::mensagens', ['mensagens' => $mensagens]);
    }

    public function lerMensagem($id)
    {
        $notificacao = backpack_user()->notifications()->find($id);
        $notificacao->update(['read_at' => now()]);
        return view('backpack::mensagem', ['notificacao' => $notificacao]);
    }

    public function phpInfo()
    {

//        phpinfo();

    }

    public function buscaDadosUrl($url)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 900);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 900);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);

        curl_close($ch);

        return json_decode($data, true);

    }

    public function buscaDadosUrlMigracao($url)
    {

        return json_decode(file_get_contents($url), true);

    }

    public function formataCnpjCpfTipo($dado, $tipo)
    {
        $retorno = $dado;

        if ($tipo == 'JURIDICA') {
            $d[0] = substr($dado, 0, 2);
            $d[1] = substr($dado, 2, 3);
            $d[2] = substr($dado, 5, 3);
            $d[3] = substr($dado, 8, 4);
            $d[4] = substr($dado, 12, 2);

            $retorno = $d[0] . '.' . $d[1] . '.' . $d[2] . '/' . $d[3] . '-' . $d[4];

        }

        if ($tipo == 'FISICA') {
            $d[0] = substr($dado, 0, 3);
            $d[1] = substr($dado, 3, 3);
            $d[2] = substr($dado, 6, 3);
            $d[3] = substr($dado, 9, 2);

            $retorno = $d[0] . '.' . $d[1] . '.' . $d[2] . '-' . $d[3];
        }

        return $retorno;
    }

    public function formataContrato($dado)
    {
        $d[0] = substr($dado, 0, 4);
        $d[1] = substr($dado, 4, 4);

        $retorno = $d[0] . '/' . $d[1];

        return $retorno;
    }

    public function formataProcesso($dado)
    {
        $d[0] = substr($dado, 0, 5);
        $d[1] = substr($dado, 5, 6);
        $d[2] = substr($dado, 11, 4);
        $d[3] = substr($dado, 15, 2);

        $retorno = $d[0] . '.' . $d[1] . '/' . $d[2] . '-' . $d[3];

        return $retorno;
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

    public function retornaDataMaisQtdTipo(string $qtd, string $tipo, string $data)
    {
        return date('Y-m-d', strtotime("+" . $qtd . " " . $tipo, strtotime($data)));
    }

}
