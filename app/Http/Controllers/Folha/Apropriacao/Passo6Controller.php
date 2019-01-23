<?php
namespace App\Http\Controllers\Folha\Apropriacao;

use App\Models\Apropriacaoimportacao;
use App\Models\Apropriacaonotasempenho;
use App\Models\Empenhos;
use Illuminate\Http\Request;

class Passo6Controller extends BaseController
{

    /**
     * @var Builder
     */
    var $htmlBuilder;

    public function __construct(Builder $htmlBuilder)
    {
        $this->htmlBuilder = $htmlBuilder;
    }

    /**
     * @param Request $request
     * @param $apid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Request $request, $apid)
    {
//        $params = $this->getDataEmpenho($apid);
//        session(['identificacao.empenho.validar_saldo' => $params]);
//
//        if ($request->ajax()) {
//            $grid = DataTables::of($params);
//            $grid->editColumn('saldo_necessario', '{!! number_format(floatval($saldo_necessario), 2, ",", ".") !!}');
//            $grid->editColumn('saldo_atual', '{!! number_format(floatval($saldo_atual), 2, ",", ".") !!}');
//            $grid->addColumn('utilizacao', function ($params) {
//                if ($params->utilizacao == 'Saldo suficiente') {
//                    return '<span>'. $params->utilizacao . '</span>';
//                }
//                return '<span style="color: red">'. $params->utilizacao . '</span>';
//            });
//            $grid->rawColumns(['utilizacao']);
//
//            return $grid->make(true);
//        }
//
//        $html = $this->htmlBuilder
//            ->addColumn(['data' => 'empenho', 'name' => 'empenho', 'title' => 'Nº do Empenho'])
//            ->addColumn(['data' => 'subitem', 'name' => 'subitem', 'title' => 'Sub Item'])
//            ->addColumn(['data' => 'fonte', 'name' => 'fonte', 'title' => 'Fonte'])
//            ->addColumn(['data' => 'saldo_necessario', 'name' => 'saldo_necessario', 'title' => 'Saldo Necessário'])
//            ->addColumn(['data' => 'saldo_atual', 'name' => 'saldo_atual', 'title' => 'Saldo Atual'])
//            ->addColumn(['data' => 'utilizacao', 'name' => 'utilizacao', 'title' => 'Utilização'])
//            ->parameters(['processing' => true,
//                'serverSide' => true,
//                'responsive' => true,
//                'info' => true,
//                'autoWidth' => true,
//                'paging' => true,
//                'lengthChange' => true,
//                'language' => [
//                    'url' => "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
//                ]
//            ]);
//
//        return view('adminlte::mod.folha.apropriacao.passo4', compact('html'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getDataEmpenho($id)
    {
        $modelo = new Apropriacaonotasempenho();
        $importacoes = $modelo->retornaListagemPasso4($id);
        $params = $this->validaSaldo($importacoes);

        return $params;
    }
    
    /**
     * Verifica se pode ou não avançar ao próximo passo
     *
     * @param Request $request
     * @return boolean
     */
    public function verificaPodeAvancar(Request $request)
    {
        $valid = session()->get('avanca');

        if ($valid == 'true') {
            return true;
        }

        return false;
    }
    
    /**
     * Retorna mensagem no caso de erro ao avançar
     *
     * @return string
     */
    protected function retornaMensagemErroAvanco()
    {
        return config('mensagens.apropriacao-empenho-pendencias');
    }

    /**
     * @param $params
     * @return mixed
     */
    protected function validaSaldo($params)
    {
        // Valores fixos
        $amb = 'PROD';
        $contacontabil1 = config('app.conta_contabil');
        $count = 0;
        $meses = array('', 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ');
        session()->put('avanca', 'true');

        foreach ($params as $item) {
            $mes = $meses[(int)$item->mes];
            $contacorrente = 'N' . $item->empenho . str_pad($item->subitem, 2, '0', STR_PAD_LEFT);
            $execsiafi = new Execsiafi();
            $retorno = $execsiafi->conrazao($item->ug, $amb, $item->ano, $item->ug, $contacontabil1, $contacorrente, $mes);
            $saldoAtual = $retorno->resultado[4] == '' ? '0,00' : $retorno->resultado[4];
            $params[$count]->saldo_atual = $saldoAtual;

            if ($item->saldo_necessario > $saldoAtual) {
                $params[$count]->utilizacao = 'Saldo insuficiente';
                session()->put('avanca', 'false');
            } else {
                $params[$count]->utilizacao = 'Saldo suficiente';
            }
            $count++;
        }

        return $params;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Verifica se pode ou não avançar ao próximo passo
     *
     * @param Request $request
     * @return boolean
     */
    public function verificaPodeAvancar(Request $request)
    {
        $apropriacao = new Apropriacao();
        $podeAvancar = $apropriacao->validarPasso5($request->apid);
        
        return $podeAvancar;
        
        /*
        
        if ($valid) {
        $this->salvarPasso6($request->apid);
        
        
        return true;
        }
        
        return false;
        
        echo '<pre>';var_dump($valid);die;
        $valid = session()->get('avanca');
        
        if ($valid == 'true') {
        return true;
        }
        
        return false;
        */
    }
    
    public function salvarPasso6 ($id)
    {
        $params = [];
        $notasEmpenho = new Apropriacaonotasempenho();
        $dadosBasicos = $notasEmpenho->getDadosBasicos($id);
        $total = 0;
        
        foreach ($dadosBasicos as $item) {
            $total += $item->saldo_necessario;
        }
        
        $date = new \DateTime('last day of this month');
        $week = (int) $date->format('N');
        if ($week > 5) {
            $week = $week % 5;
            $date->modify("-$week days");
        }
        
        $ultimoDiaUtil = $date->format('Y-m-d');
        
        $params['dados'] = $dadosBasicos;
        $params['vr_total'] = $total;
        $params['ultimo_dia_util'] = $ultimoDiaUtil;
        
        $sfPadraoId = $this->salvarSfPadrao($params['dados'], $id);
        $sfDadosBasicosId = $this->salvarSfDadosBasicos($params, $sfPadraoId);
        $sfDocOrigemId = $this->salvarSfDocOrigem($params, $sfDadosBasicosId);
        $sfPco = $this->salvarPco($id, $sfPadraoId, $params);
        $sfPcoItem = $this->salvarPcoItem();
        
        var_dump($sfPco);die;
        //        $this->salvarSfDadosBasicos($id);
        
    }
    
    public function salvarPco ($idEmpenho, $sfPadraoId, $params)
    {
        $notasEmpenho = new Apropriacaonotasempenho();
        $empenhos = $notasEmpenho->getEmpenhoPco($idEmpenho);
        $numSeq = 1;
        
        foreach ($empenhos as $item) {
            $sfPco = new SfPco();
            $sfPco->sfpadrao_id = $sfPadraoId;
            $sfPco->numseqitem = $numSeq;
            $sfPco->codsit = $item->situacao;
            $sfPco->codugempe = $params['dados'][0]->ug;
            $sfPco->indrtemcontrato = 0;
            $sfPco->save();
            $this->salvarPcoItem($sfPco->id, $idEmpenho, $item->situacao);
            $numSeq++;
        }
    }
    
    public function salvarPcoItem ($idPco, $idEmpenho, $situacao)
    {
        $notasEmpenho = new Apropriacaonotasempenho();
        $empenhosPco = $notasEmpenho->getEmpenhoPcoItem($idEmpenho, $situacao);
        
        foreach ($empenhosPco as $itemPco) {
            dd($itemPco);
        }
        dd($empenhosPco);
        
        $sfPcoItem = new SfPcoItem();
        $sfPcoItem->sfpco_id = $idPco;
        $sfPcoItem->numseqitem = $idPco;
        $sfPcoItem->codsubitemempe = $idPco;
        $sfPcoItem->indrliquidado = $idPco;
        $sfPcoItem->vlr = $idPco;
        $sfPcoItem->txtinscra = $idPco;
        $sfPcoItem->numclassa = $idPco;
        $sfPcoItem->txtinscrb = $idPco;
        $sfPcoItem->numclassb = $idPco;
        $sfPcoItem->txtinscrc = $idPco;
        $sfPcoItem->numclassc = $idPco;
        $sfPcoItem->save();
    }
    
    /**
     * @param $params
     * @param $dadosBasicosId
     * @return mixed
     */
    public function salvarSfDocOrigem ($params, $dadosBasicosId)
    {
        $sfDocOrigem = new SfDocOrigem();
        $sfDocOrigem->sfdadosbasicos_id = $dadosBasicosId;
        $sfDocOrigem->codidentemit = $params['dados'][0]->ug;
        $sfDocOrigem->dtemis = now();
        $sfDocOrigem->numdocorigem = $params['dados'][0]->doc_origem;
        $sfDocOrigem->vlr = $params['vr_total'];
        $sfDocOrigem->save();
        
        return $sfDocOrigem->id;
    }
    
    /**
     * @param $params
     * @param $sfPadraoId
     * @return mixed
     */
    public function salvarSfDadosBasicos ($params, $sfPadraoId)
    {
        $dadosBasicos = new SfDadosBasicos();
        $dadosBasicos->sfpadrao_id = $sfPadraoId;
        $dadosBasicos->dtemis = now();
        $dadosBasicos->dtvenc = $params['ultimo_dia_util'];
        $dadosBasicos->codugpgto = $params['dados'][0]->ug;
        $dadosBasicos->vlr = $params['vr_total'];
        $dadosBasicos->txtobser = $params['dados'][0]->observacoes;
        $dadosBasicos->vlrtaxacambio = '0.0';
        $dadosBasicos->txtprocesso = $params['dados'][0]->nup;
        $dadosBasicos->dtateste = $params['dados'][0]->ateste;
        $dadosBasicos->codcredordevedor = $params['dados'][0]->ug;
        $dadosBasicos->dtpgtoreceb = $params['dados'][0]->ateste;
        $dadosBasicos->save();
        
        return $dadosBasicos->id;
    }
    
    /**
     * @param $params
     * @param $id
     * @return mixed
     */
    public function salvarSfPadrao ($params, $id)
    {
        $sfPadrao = new SfPadrao();
        $sfPadrao->fk = $id;
        $sfPadrao->categoriapadrao = 'EXECFOLHA';
        $sfPadrao->decricaopadrao = Auth::user()->getAuthIdentifier();
        $sfPadrao->codugemit = $params[0]->ug;
        $sfPadrao->anodh = $params[0]->ano;
        $sfPadrao->codtipodh = 'FL';
        $sfPadrao->numdh = null;
        $sfPadrao->dtemis = now();
        $sfPadrao->txtmotivo = null;
        $sfPadrao->msgretorno = null;
        $sfPadrao->tipo = 'F';
        $sfPadrao->situacao = 'P';
        $sfPadrao->save();
        
        return $sfPadrao->id;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

}
