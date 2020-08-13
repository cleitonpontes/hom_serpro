<?php

namespace App\Http\Controllers;

use App\Forms\MeusdadosForm;
use App\Forms\MudarUgForm;
use App\Models\BackpackUser;
use App\Models\CalendarEvent;
use App\Models\Contrato;
use App\Models\Siasgcontrato;
use App\Models\Unidade;
use App\Repositories\Empenho;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MaddHatter\LaravelFullcalendar\Calendar;
use phpDocumentor\Reflection\File;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class AdminController extends Controller
{
    protected $data = []; // the information we send to the view
    protected $htmlBuilder = null;

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
    public function index(Request $request, Builder $htmlBuilder)
    {
        // ************************************************************
        // Configurações iniciais
        // ************************************************************
        $this->htmlBuilder = $htmlBuilder;
        $this->data['title'] = "Início"; //trans('backpack::base.dashboard'); // set the page title
        $ug = session('user_ug');

        // ************************************************************
        // Calendário
        // ************************************************************
        $events = $this->getEvents();

        $calendar = \Calendar::addEvents($events)->setOptions([
            'first_day' => 1,
            // 'aspectRatio' => 2.5,
        ])->setCallbacks([]);

        // ************************************************************
        // Gráfico Contratos por Categoria
        // ************************************************************
        $colors = $this->getColors();
        $categorias = $this->retornaCategorias();
        $contrato = $this->retornaContrato();

        $chartjs = $this->retornaGrafico($colors, $categorias, $contrato);

        // ************************************************************
        // Empenhos sem Contrato
        // ************************************************************
        $dadosContratos = $this->retornaDadosContratos();

        // ************************************************************
        // Monta GRID Empenhos sem Contrato
        // ************************************************************
        if ($request->ajax()) {
            $dt = DataTables::of($this->retornaDadosEmpenhosSemContratos());

            $dt->addColumn('contratos', function ($registro) use ($ug) {
                $idEmpenho = $registro['id'];
                $idFornecedor = $registro['fornecedor_id'];
                $opcoes = $this->retornaContratosPorFornecedor($ug, $idFornecedor);

                $podeVincularContrato = backpack_user()->hasPermissionTo('contratoempenho_inserir');

                $botaoConfirma = '';
                $campoSelect = $this->retornaHtmlCampoSelect($idEmpenho, $opcoes);

                if ($podeVincularContrato) {
                    $botaoConfirma = $this->retornaHtmlBotaoConfirma($idEmpenho, $idFornecedor);
                }

                return $campoSelect . ' ' . $botaoConfirma;
            });
            $dt->editColumn('valor', '{!! number_format(floatval($valor), 2, ",", ".") !!}');

            $dt->rawColumns(['contratos']);
            $dt->setRowId('linha_id');

            return $dt->make(true);
        }
        $gridEmpenhos = $this->montaGridCampos();

        $dataHoraAtualizacao = $this->retornaDataHoraUltimaAtualizacao();

        return view('backpack::dashboard', [
            'calendar' => $calendar,
            'data' => $this->data,
            'chartjs' => $chartjs,
            'chartjsTotal' => array_sum($contrato),
            'html' => $dadosContratos,
            'ug' => $ug,
            'gridEmpenhos' => $gridEmpenhos,
            'dataHoraAtualizacao' => $dataHoraAtualizacao
        ]);
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

    protected function getEvents()
    {

        $events = [];
        $eventsCollections = $this->getCalendarEvents();

        if ($eventsCollections->count() > 0) {
            dd($eventsCollections->count());
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

    private function getColors($shuffle = false)
    {
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

        if ($shuffle) {
            $colors = shuffle($colors);
        }

        return $colors;
    }

    private function retornaCategorias()
    {
        $categoriasContrato = $this->retornaCategoriasContrato();

        //dd($categoriasContrato);
        $cats = array_unique($categoriasContrato);

        $categorias = [];
        foreach ($cats as $c) {
            $categorias[] = $c;
        }

        return $categorias;
    }

    private function retornaCategoriasContrato()
    {
        return $this->retornaContratosParaGrafico('descricao');
    }

    private function retornaContrato()
    {
        return $this->retornaContratosParaGrafico('qtde');
    }

    private function retornaContratosParaGrafico($campo)
    {
        $dados = Contrato::join('codigoitens as cat', 'cat.id', '=', 'categoria_id');
        $dados->where('unidade_id', session()->get('user_ug_id'));
        $dados->where('situacao', true);
        $dados->selectRaw(
            "concat(cat.descricao, ' (', count(cat.descricao), ')') as descricao,
            count(cat.descricao) qtde"
        );
        $dados->groupBy('cat.descricao');
        $dados->orderBy('cat.descricao');

        return $dados->pluck($campo)->toArray();
    }

    private function retornaGrafico($colors, $categorias, $contrato)
    {
        return app()->chartjs
            ->name('pieChartTest')
            ->type('doughnut')
            ->size(['width' => 400, 'height' => 200])
            ->labels($categorias)
            ->datasets([
                [
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                    'data' => $contrato
                ]
            ]);
    }

    private function retornaDadosContratos()
    {
        $dadosContratos['novos'] = '0';
        $dadosContratos['atualizados'] = '0';
        $dadosContratos['vencidos'] = '0';

        if (session()->get('user_ug_id')) {
            $unidade = Unidade::find(session()->get('user_ug_id'));

            if (isset($unidade->orgao->configuracao->padrao_processo_marcara)) {
                session(['numprocmask' => $unidade->orgao->configuracao->padrao_processo_marcara]);
            }

            $contratos = new Contrato();

            $dadosContratos['novos'] = $contratos->buscaContratosNovosPorUg(session()->get('user_ug_id'));
            $dadosContratos['atualizados'] = $contratos->buscaContratosAtualizadosPorUg(session()->get('user_ug_id'));
            $dadosContratos['vencidos'] = $contratos->buscaContratosVencidosPorUg(session()->get('user_ug_id'));
        }

        return $dadosContratos;
    }

    private function retornaContratosPorFornecedor($ug, $idFornecedor)
    {
        $contratos = Contrato::join('unidades as U', 'U.id', '=', 'contratos.unidade_id');
        $contratos->select(
            'contratos.id',
            DB::raw("CONCAT(contratos.numero, ' - ', LEFT(contratos.objeto, 150)) as desc")
        );
        $contratos->where('U.codigo', $ug);
        $contratos->where('contratos.fornecedor_id', $idFornecedor);
        $contratos->where('contratos.situacao', true);

        return $contratos->pluck('desc', 'id')->toArray();
    }


    protected function getCalendarEvents()
    {
        $eventsCollections = new CalendarEvent();
        dd(session()->get('user_ug_id'));
        if (session()->get('user_ug_id')) {
            $eventsCollections = $eventsCollections->where('unidade_id', session()->get('user_ug_id'));
        }
        return $eventsCollections;
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

    public function retornaDataMaisOuMenosQtdTipoFormato(string $formato, string $sinal, string $qtd, string $tipo, string $data)
    {
        return date($formato, strtotime($sinal . $qtd . " " . $tipo, strtotime($data)));
    }

    private function retornaDadosEmpenhosSemContratos()
    {
        $repo = new Empenho();
        return $repo->retornaEmpenhosSemContratoAnoAtual();
    }

    private function retornaCampos($dados)
    {
        $dado = [];

        if (is_array($dados)) {
            if (isset($dados[0])) {
                $dado = $dados[0];
            }
        }

        return array_keys($dado);
    }

    /**
     * Monta $html com definições do Grid
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    private function montaGridCampos()
    {
        $html = ($this->htmlBuilder === null) ? Builder::class : $this->htmlBuilder;

        $html->setTableId('empenhosSemContratosTable');
        $html->addTableClass('box table-striped table-hover');
        $html->addTableClass('display responsive');
        $html->addTableClass('nowrap m-t-0 collapsed has-hidden-columns');
        $html->parameters(['order' => [0, 'desc']]);

        $html->addColumn([
            'data' => 'criacao',
            'name' => 'criacao',
            'title' => 'Criação',
            'orderable' => true,
            'visible' => false
        ]);
        $html->addColumn([
            'data' => 'empenho',
            'name' => 'empenho',
            'title' => 'Empenho',
            'orderable' => true
        ]);
        $html->addColumn([
            'data' => 'fornecedor',
            'name' => 'fornecedor',
            'title' => 'Fornecedor',
            'orderable' => true
        ]);
        $html->addColumn([
            'data' => 'valor',
            'name' => 'valor',
            'title' => 'Valor (R$)',
            'class' => 'text-right',
            'orderable' => true
        ]);
        $html->addColumn([
            'data' => 'contratos',
            'name' => 'contratos',
            'title' => 'Contratos',
            'class' => 'text-right',
            'orderable' => false
        ]);

        $html->parameters([
            'processing' => true,
            'serverSide' => true,
            'responsive' => true,
            'info' => true,
            'autoWidth' => false,
            'bAutoWidth' => false,
            'paging' => true,
            'lengthChange' => true,
            'language' => [
                'url' => asset('/json/pt_br.json')
            ]
        ]);

        return $html;
    }

    private function retornaHtmlCampoSelect($idEmpenho, $opcoes)
    {
        $campoSelect = "";
        $campoSelect .= "<select ";
        $campoSelect .= "id='$idEmpenho' ";
        $campoSelect .= "style='width: 155px;' ";
        $campoSelect .= ">";
        $campoSelect .= "<option value=''>Selecione o contrato</option>";

        foreach ($opcoes as $idContrato => $desc) {
            $campoSelect .= "<option value='$idContrato'>$desc</option>";
        }

        $campoSelect .= "</select>";

        return $campoSelect;
    }

    private function retornaHtmlBotaoConfirma($idEmpenho, $idFornecedor)
    {
        $botaoConfirma = "";
        $botaoConfirma .= "<a ";
        $botaoConfirma .= "class='contrato btn btn-xs btn-default' ";
        $botaoConfirma .= "style='margin-left: 5px;' ";
        $botaoConfirma .= "data-ne='$idEmpenho' ";
        $botaoConfirma .= "data-fornecedor='$idFornecedor' ";
        $botaoConfirma .= "title='Vincular contrato ao empenho' ";
        $botaoConfirma .= ">";
        $botaoConfirma .= "<i class='fa fa-tags'></i>";
        $botaoConfirma .= "</a>";

        return $botaoConfirma;
    }

    private function retornaDataHoraUltimaAtualizacao()
    {
        $dataFormatada = '';

        $campo = Siasgcontrato::max('updated_at');

        if ($campo) {
            $dataFormatada = Carbon::make($campo)->format('d/m/Y H:i:s');
        }

        return $dataFormatada;
    }

}
