<?php

namespace App\Http\Controllers\Transparencia;

use App\Forms\FiltroRelatorioContratosForm;
use App\Forms\MeusdadosForm;
use App\Forms\MudarUgForm;
use App\Forms\TransparenciaIndexForm;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\BackpackUser;
use App\Models\CalendarEvent;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Fornecedor;
use App\Models\Orgao;
use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MaddHatter\LaravelFullcalendar\Calendar;
use function foo\func;

class IndexController extends Controller
{
    protected $data = []; // the information we send to the view
    const TIPO_NUMERO_CONTRATOS_TOTAL = 'TOTAL';
    const TIPO_NUMERO_CONTRATOS_30 = '30';
    const TIPO_NUMERO_CONTRATOS_3060 = '3060';
    const TIPO_NUMERO_CONTRATOS_6090 = '6090';
    const TIPO_NUMERO_CONTRATOS_90180 = '90180';
    const TIPO_NUMERO_CONTRATOS_180 = '180';
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->data['title'] = "Área Consulta Pública";//trans('backpack::base.dashboard'); // set the page title
        $filtro = [];

        $form = \FormBuilder::create(TransparenciaIndexForm::class,
            [
                'method' => 'GET',
                'model' => ($request->input()) ? $request->input() : '',
                'url' => route('transparencia.index'),
            ]
        );

        if ($request->query()) {
            $filtro = $request->input();
            $this->data['fields'] = $this->trataDadosView($filtro);
        }

        $this->data['totalcontratado_numero'] = $this->calculaTotalContratado($filtro);
        $graficoCategoriaContratos = $this->geraGraficoCategoriaContratos($filtro);

        $base = new AdminController();
        $dt30 = $base->retornaDataMaisQtdTipo('30','days',date('Y-m-d'));
        $dt60 = $base->retornaDataMaisQtdTipo('60','days',date('Y-m-d'));
        $dt90 = $base->retornaDataMaisQtdTipo('90','days',date('Y-m-d'));
        $dt180 = $base->retornaDataMaisQtdTipo('180','days',date('Y-m-d'));

        $this->data['contratos_total_numero'] = $this->buscaNumeroContratosPorPeriodoVencimento(self::TIPO_NUMERO_CONTRATOS_TOTAL,$filtro);
        $this->data['contratos_vencer30_numero'] = $this->buscaNumeroContratosPorPeriodoVencimento(self::TIPO_NUMERO_CONTRATOS_30,$filtro,[$dt30]);
        $this->data['contratos_vencer3060_numero'] = $this->buscaNumeroContratosPorPeriodoVencimento(self::TIPO_NUMERO_CONTRATOS_3060,$filtro,[$dt30,$dt60]);
        $this->data['contratos_vencer6090_numero'] = $this->buscaNumeroContratosPorPeriodoVencimento(self::TIPO_NUMERO_CONTRATOS_6090,$filtro,[$dt60,$dt90]);
        $this->data['contratos_vencer90180_numero'] = $this->buscaNumeroContratosPorPeriodoVencimento(self::TIPO_NUMERO_CONTRATOS_90180,$filtro,[$dt90,$dt180]);
        $this->data['contratos_vencer180_numero'] = $this->buscaNumeroContratosPorPeriodoVencimento(self::TIPO_NUMERO_CONTRATOS_180,$filtro,[$dt180]);
        $this->data['contratos_total_percentual'] = '100%';
        $this->data['contratos_vencer30_percentual'] = number_format($this->data['contratos_vencer30_numero'] / $this->data['contratos_total_numero']*100,0,',','') . '%';
        $this->data['contratos_vencer3060_percentual'] = number_format($this->data['contratos_vencer3060_numero'] / $this->data['contratos_total_numero']*100,0,',','') . '%';
        $this->data['contratos_vencer6090_percentual'] = number_format($this->data['contratos_vencer6090_numero'] / $this->data['contratos_total_numero']*100,0,',','') . '%';
        $this->data['contratos_vencer90180_percentual'] = number_format($this->data['contratos_vencer90180_numero'] / $this->data['contratos_total_numero']*100,0,',','') . '%';
        $this->data['contratos_vencer180_percentual'] = number_format($this->data['contratos_vencer180_numero'] / $this->data['contratos_total_numero']*100,0,',','') . '%';


        return view('backpack::consultapublica', [
            'data' => $this->data,
            'form' => $form,
            'graficocategoriacontratos' => $graficoCategoriaContratos
        ]);
    }


    private function buscaNumeroContratosPorPeriodoVencimento(string $tipo, array $filtro = null, array $datas = null)
    {

        $contratos = DB::table('contratos');
        $contratos->join('unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $contratos->join('orgaos', 'orgaos.id', '=', 'unidades.orgao_id');
        $contratos->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $contratos->where('contratos.situacao', '=', true);
        if (isset($filtro['orgao'])) {
            $contratos->where('orgaos.codigo', $filtro['orgao']);
        }
        if (isset($filtro['unidade'])) {

            $contratos->where('unidades.codigo', $filtro['unidade']);
        }
        if (isset($filtro['fornecedor'])) {
            $contratos->where('fornecedores.cpf_cnpj_idgener', $filtro['fornecedor']);
        }
        if (isset($filtro['contrato'])) {
            $contratos->where('contratos.numero', $filtro['contrato']);
        }
        if($tipo == self::TIPO_NUMERO_CONTRATOS_TOTAL){
            $total = $contratos->count();
        }
        if($tipo == self::TIPO_NUMERO_CONTRATOS_30){
            $contratos->where('contratos.vigencia_fim','<', $datas[0]);
            $total = $contratos->count();
        }

        if($tipo == self::TIPO_NUMERO_CONTRATOS_3060 or $tipo == self::TIPO_NUMERO_CONTRATOS_6090 or $tipo == self::TIPO_NUMERO_CONTRATOS_90180){
            $contratos->where('contratos.vigencia_fim','>=', $datas[0]);
            $contratos->where('contratos.vigencia_fim','<', $datas[1]);
            $total = $contratos->count();
        }
        if($tipo == self::TIPO_NUMERO_CONTRATOS_180){
            $contratos->where('contratos.vigencia_fim','>=', $datas[0]);
            $total = $contratos->count();
        }

        return $total;
    }

    private function calculaTotalContratado(array $filtro = null)
    {
        $contratos = DB::table('contratos');
        $contratos->select(DB::raw('sum(valor_global)'));
        $contratos->join('unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $contratos->join('orgaos', 'orgaos.id', '=', 'unidades.orgao_id');
        $contratos->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $contratos->where('contratos.situacao', '=', true);
        if (isset($filtro['orgao'])) {
            $contratos->where('orgaos.codigo', $filtro['orgao']);
        }
        if (isset($filtro['unidade'])) {
            $contratos->where('unidades.codigo', $filtro['unidade']);
        }
        if (isset($filtro['fornecedor'])) {
            $contratos->where('fornecedores.cpf_cnpj_idgener', $filtro['fornecedor']);
        }
        if (isset($filtro['contrato'])) {
            $contratos->where('contratos.numero', $filtro['contrato']);
        }
        $data = $contratos->pluck('sum')->first();

        return $data;
    }

    private function geraGraficoCategoriaContratos(array $filtro = null)
    {
        $base = new AdminController();

        $categoria_contrato = Codigoitem::whereHas('codigo', function ($q) {
            $q->where('descricao', '=', 'Categoria Contrato');
        })
            ->join('contratos', function ($j) {
                $j->on('codigoitens.id', '=', 'contratos.categoria_id');
            })
            ->join('unidades', function ($j) {
                $j->on('unidades.id', '=', 'contratos.unidade_id');
            })
            ->join('orgaos', function ($j) {
                $j->on('orgaos.id', '=', 'unidades.orgao_id');
            })
            ->join('fornecedores', function ($j) {
                $j->on('fornecedores.id', '=', 'contratos.fornecedor_id');
            })
            ->orderBy('codigoitens.id', 'asc');

        if (isset($filtro['orgao'])) {
            $categoria_contrato->where('orgaos.codigo', $filtro['orgao']);
        }
        if (isset($filtro['unidade'])) {
            $categoria_contrato->where('unidades.codigo', $filtro['unidade']);
        }
        if (isset($filtro['fornecedor'])) {
            $categoria_contrato->where('fornecedores.cpf_cnpj_idgener', $filtro['fornecedor']);
        }
        if (isset($filtro['contrato'])) {
            $categoria_contrato->where('contratos.numero', $filtro['contrato']);
        }

        $cat = array_unique($categoria_contrato->pluck('descricao')->toArray());

        $categorias = [];
        foreach ($cat as $c) {
            $categorias[] = $c;
        }

        $contratos = DB::table('contratos');
        $contratos->select(DB::raw('categoria_id, count(categoria_id)'));
        $contratos->join('unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $contratos->join('orgaos', 'orgaos.id', '=', 'unidades.orgao_id');
        $contratos->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $contratos->where('contratos.situacao', '=', true);
        $contratos->orderBy('categoria_id', 'asc');
        $contratos->groupBy('categoria_id');
        if (isset($filtro['orgao'])) {
            $contratos->where('orgaos.codigo', $filtro['orgao']);
        }
        if (isset($filtro['unidade'])) {
            $contratos->where('unidades.codigo', $filtro['unidade']);
        }
        if (isset($filtro['fornecedor'])) {
            $contratos->where('fornecedores.cpf_cnpj_idgener', $filtro['fornecedor']);
        }
        if (isset($filtro['contrato'])) {
            $contratos->where('contratos.numero', $filtro['contrato']);
        }
        $data = $contratos->pluck('count')->toArray();

        $colors = $base->colors(count($data));

        $chartjs = app()->chartjs
            ->name('pieChartTest')
            ->type('doughnut')
            ->size(['width' => 400, 'height' => 200])
            ->labels($categorias)
            ->datasets([
                [
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                    'data' => $data,
                ]
            ]);

        return $chartjs;

    }


    private function trataDadosView(array $campos)
    {
        $orgao = [];
        $unidade = [];
        $fornecedor = [];
        $contrato = [];

        foreach ($campos as $key => $value) {
            if ($key == 'orgao') {
                $orgao = Orgao::select(DB::raw("CONCAT(codigo,' - ',nome) AS nome"), 'codigo')
                    ->where('codigo', $value)
                    ->pluck('nome', 'codigo')
                    ->toArray();
            }

            if ($key == 'unidade') {
                $unidade = Unidade::select(DB::raw("CONCAT(codigo,' - ',nomeresumido) AS nome"), 'codigo')
                    ->where('codigo', $value)
                    ->pluck('nome', 'codigo')
                    ->toArray();
            }

            if ($key == 'fornecedor') {
                $fornecedor = Fornecedor::select(DB::raw("CONCAT(cpf_cnpj_idgener,' - ',nome) AS nome"), 'cpf_cnpj_idgener')
                    ->where('cpf_cnpj_idgener', $value)
                    ->pluck('nome', 'cpf_cnpj_idgener')
                    ->toArray();
            }

            if ($key == 'contrato') {
                $contrato = Contrato::select('numero')
                    ->where('numero', $value)
                    ->pluck('numero', 'numero')
                    ->toArray();
            }
        }

        return [
            'orgao' => $orgao,
            'unidade' => $unidade,
            'fornecedor' => $fornecedor,
            'contrato' => $contrato
        ];

    }

}
