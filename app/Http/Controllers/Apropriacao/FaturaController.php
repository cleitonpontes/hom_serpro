<?php
/**
 * Controller com métodos e funções da Apropriação da Fatura
 *
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */

namespace App\Http\Controllers\Apropriacao;

use App\Http\Controllers\Folha\Apropriacao\BaseController;

use App\Http\Traits\Formatador;
use App\Models\ApropriacaoContratoFaturas;
use App\Models\ApropriacaoFaturas;
use App\Models\Contrato;
use App\Models\Contratofatura;
use App\Models\SfDadosBasicos;
use App\Models\SfPadrao;
use App\Models\SfPco;
use App\Models\SfPcoItem;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

/**
 * Disponibiliza as funcionalidades básicas para controllers
 *
 * @category Conta
 * @package Conta_Folha_Apropriacao_Fatura
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 * @copyright AGU - Advocacia Geral da União ©2018 <http://www.agu.gov.br>
 * @license GNU General Public License v2.0. <https://choosealicense.com/licenses/gpl-2.0/>
 */
class FaturaController extends BaseController
{
    use Formatador;

    private $htmlBuilder = '';
    private $apropriacaoId = null;
    private $apropriacaoValor = 0;
    private $pcoId = null;

    private $msgErroFaturaDoContrato = 'Fatura não pertence ao contrato informado.';
    private $msgErroFaturaEmApropriacao = 'Fatura já apropriada.';
    private $msgErroFaturasEmApropriacao = 'Uma ou mais faturas já apropriadas.';
    private $msgErroFaturaInexistente = 'Nenhuma fatura válida foi encontrada para apropriação.';

    /**
     * Método construtor
     *
     * @param Builder $htmlBuilder
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    public function __construct(Builder $htmlBuilder)
    {
        backpack_auth()->check();
        $this->htmlBuilder = $htmlBuilder;
    }

    /**
     * Apresenta o grid com a listagem das apropriações da fatura
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     * @throws \Exception
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function index(Request $request)
    {
        $dados = ApropriacaoFaturas::retornaDadosListagem()->get();

        if ($request->ajax()) {
            return DataTables::of($dados)->addColumn('action', function ($registro) {
                return $this->retornaAcoes($registro);
            })
                ->editColumn('ateste', '{!!
                    !is_null($ateste) ? date_format(date_create($ateste), "d/m/Y") : ""
                !!}')
                ->editColumn('vencimento', '{!!
                    !is_null($vencimento) ? date_format(date_create($vencimento), "d/m/Y") : ""
                !!}')
                ->editColumn('total', '{!! number_format(floatval($total), 2, ",", ".") !!}')
                ->editColumn('valor', '{!! number_format(floatval($valor), 2, ",", ".") !!}')
                ->setRowId('registro_id')
                ->make(true);
        }

        $html = $this->retornaHtmlGrid();
        return view('backpack::mod.apropriacao.fatura', compact('html'));
    }

    /**
     * Método para criação de registro de apropriação com única fatura
     *
     * @param Contrato $contrato
     * @param Contratofatura $fatura
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function create(Contrato $contrato, Contratofatura $fatura)
    {
        $faturaIds = (array)$fatura->id;

        if ($this->validaNaoApropriacaoDeFaturas($faturaIds)) {
            \Alert::warning($this->msgErroFaturaEmApropriacao)->flash();
            return redirect('/gescon/consulta/faturas');
        }

        if ($this->validaExistenciaFaturas($faturaIds)) {
            \Alert::warning($this->msgErroFaturaInexistente)->flash();
            return redirect('/gescon/consulta/faturas');
        }

        $this->gerarApropriacaoFaturas($faturaIds);
        $this->executaApropriacaoFaturas();

        \Alert::success('Fatura(s) incluída(s) na apropriação')->flash();
        return redirect('/gescon/consulta/faturas');
    }

    /**
     * Método para criação de registro de apropriação com uma ou mais faturas
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function createMany()
    {
        $retorno['tipo'] = 'warning';
        $retorno['mensagem'] = '';

        $faturaIds = request()->entries;

        if ($this->validaFaturaDeDiferentesContratos($faturaIds)) {
            $retorno['mensagem'] = $this->msgErroFaturaDoContrato;

            return json_encode($retorno);
        }

        if ($this->validaNaoApropriacaoDeFaturas($faturaIds)) {
            $retorno['mensagem'] = $this->msgErroFaturasEmApropriacao;

            return json_encode($retorno);
        }

        if ($this->validaExistenciaFaturas($faturaIds)) {
            $retorno['mensagem'] = $this->msgErroFaturaInexistente;

            return json_encode($retorno);
        }

        $this->gerarApropriacaoFaturas($faturaIds);
        $this->executaApropriacaoFaturas();

        $retorno['tipo'] = 'success';
        return json_encode($retorno);
    }

    /**
     * Exclui a apropriação da fatura informada
     *
     * @param ApropriacaoFaturas $apropriacaoFatura
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function destroy(ApropriacaoFaturas $apropriacaoFatura)
    {
        $msg = config('mensagens.apropriacao-exclusao-alerta');
        $status = 'Alerta';

        if ($apropriacaoFatura->fase_id != ApropriacaoFaturas::FASE_CONCLUIDA) {
            $apropriacaoFatura->delete();

            $msg = config('mensagens.apropriacao-exclusao');
            $status = 'Sucesso';
        }

        $this->exibeMensagem($msg, $status);

        return redirect('/apropriacao/fatura')->withInput();
    }

    /**
     * Exibe relatório da apropriação
     *
     * @param $id
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    public function show($id)
    {
        $modelApropriacao = new ApropriacaoFaturas();
        $identificacao = $modelApropriacao->retornaDadosIdentificacao($id)->first()->toArray();

        $modelPco = new SfPco();
        $pcos = $modelPco->retornaPcosProApropriacaoDaFatura($id)->get()->toArray();

        return view('backpack::mod.apropriacao.relatorio', compact('identificacao', 'pcos'));
    }

    /**
     * Exibe o documento hábil da apropriação
     *
     * @param $apropriacaoFatura
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    public function documentoHabil($apropriacaoFatura)
    {
        $dados = [];
        $modelSfPadrao = new SfPadrao();

        $sfPadrao = $modelSfPadrao->retornaExecucaoDaFatura($apropriacaoFatura);
        $sfPco = SfPco::where('sfpadrao_id', $sfPadrao->id)->first();
        $sfPcoItem = SfPcoItem::where('sfpco_id', $sfPco->id)->first();

        $dados[] = [
            'DH Principal',
            $sfPadrao->dtemis,
            Carbon::createFromFormat('Y-m-d', $sfPadrao->dtemis)->format('d/m/Y'),
            $sfPadrao->anodh . $sfPadrao->codtipodh . str_pad($sfPadrao->numdh, 6, 0, STR_PAD_LEFT),
            $sfPco->codsit,
            $sfPcoItem->numempe,
            str_pad($sfPcoItem->codsubitemempe, 2, 0, STR_PAD_LEFT),
            $this->retornaCampoFormatadoComoNumero($sfPcoItem->vlr),
            $sfPadrao->msgretorno
        ];

        return view('backpack::mod.apropriacao.dochabil', ['dados' => $dados]);
    }

    /**
     * Valida se fatura pertence ao contrato informado
     *
     * @param integer $faturaContratoId
     * @return bool
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function validaFaturaDeDiferentesContratos($faturaContratoId)
    {
        return Contratofatura::whereIn('id', $faturaContratoId)->pluck('contrato_id')->unique()->count() != 1;
    }

    /**
     * Valida se fatura está presente em alguma apropriação que não cancelada
     *
     * @param array $faturaIds
     * @return bool
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function validaNaoApropriacaoDeFaturas($faturaIds)
    {
        return ApropriacaoContratoFaturas::existeFatura($faturaIds);
    }

    /**
     * Valida se fatura existe
     *
     * @param array $faturaIds
     * @return bool
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function validaExistenciaFaturas($faturaIds)
    {
        return Contratofatura::whereIn('id', $faturaIds)->doesntExist();
    }

    /**
     * Cria registro da apropriação
     *
     * @param array $faturaIds
     * @return bool
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function gerarApropriacaoFaturas($faturaIds)
    {
        $valorTotal = Contratofatura::whereIn('id', $faturaIds)->sum('valorliquido');

        return DB::transaction(function () use ($valorTotal, $faturaIds) {
            $apropriacao = ApropriacaoFaturas::create([
                'valor' => $valorTotal,
                'fase_id' => ApropriacaoFaturas::FASE_EM_ANDAMENTO
            ]);

            foreach ($faturaIds as $id) {
                ApropriacaoContratoFaturas::create([
                    'apropriacoes_faturas_id' => $apropriacao->id,
                    'contratofaturas_id' => $id
                ]);
            }

            $this->apropriacaoId = $apropriacao->id;
            $this->apropriacaoValor = $valorTotal;
        });
    }

    /**
     * Se houver documento hábil padrão para fatura a apropriar, duplica dados SfPadrao (e dependências)
     *
     * @todo: Mudar os métodos para duplicação de Sf... para suas respectivas App\Models\Sf...
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function executaApropriacaoFaturas()
    {
        $modelo = new SfPadrao();

        $sfPadraoOriginal = $modelo->retornaPadraoDaFatura($this->apropriacaoId);

        if ($sfPadraoOriginal) {
            $sfPadraoIdDuplicado = $this->duplicaSfPadrao($sfPadraoOriginal);
            $this->duplicaSfDadosBasicos($sfPadraoOriginal->id, $sfPadraoIdDuplicado);
            $sfPcoIdDuplicado = $this->duplicaSfPco($sfPadraoOriginal->id, $sfPadraoIdDuplicado);
            $this->duplicaSfPcoItem($this->pcoId, $sfPcoIdDuplicado);

            $this->apropriacaoConcluida($this->apropriacaoId);
        }

        // Se existir, replica (atualizando DATA_ATESTE, DATA_EMISSAO, VALOR, outros dados [?])
        // ... Finalizar!
        //
        // Senão,
        // Solicitar informações para gravação
        // ...
    }

    /**
     * Duplica anterior registro sfpadrao
     *
     * @param $sfPadrao
     * @return int
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function duplicaSfPadrao($sfPadrao)
    {
        $anoDh = ($sfPadrao->anodh == date('Y')) ? $sfPadrao->anodh : date('Y-m');

        $sfPadraoDuplicado = $sfPadrao->replicate();
        $sfPadraoDuplicado->fk = $this->apropriacaoId;
        $sfPadraoDuplicado->categoriapadrao = 'EXECFATURA';
        $sfPadraoDuplicado->anodh = $anoDh;
        $sfPadraoDuplicado->tipo = '';
        $sfPadraoDuplicado->created_at = now();
        $sfPadraoDuplicado->updated_at = now();
        $sfPadraoDuplicado->user_id = backpack_user()->id;
        $sfPadraoDuplicado->push();

        return $sfPadraoDuplicado->id;
    }

    /**
     * Duplica anterior registro sfdadosbasicos
     *
     * @param $sfPadraoId
     * @param $sfPadraoIdDuplicado
     * @return bool
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function duplicaSfDadosBasicos($sfPadraoId, $sfPadraoIdDuplicado)
    {
        $sfDadosBasicos = SfDadosBasicos::where('sfpadrao_id', $sfPadraoId);

        if (!$sfDadosBasicos) {
            return false;
        }

        $sfDadosBasicosDuplicado = $sfDadosBasicos->first()->replicate();
        $sfDadosBasicosDuplicado->sfpadrao_id = $sfPadraoIdDuplicado;
        $sfDadosBasicosDuplicado->dtemis = now();
        // $sfDadosBasicosDuplicado->dtvenc = $anoDh;
        $sfDadosBasicosDuplicado->vlr = $this->apropriacaoValor;
        // $sfDadosBasicosDuplicado->dtateste = $anoDh;
        $sfDadosBasicosDuplicado->push();

        return true;
    }

    /**
     * Duplica anterior registro sfpco
     *
     * @param $sfPadraoId
     * @param $sfPadraoIdDuplicado
     * @return false
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function duplicaSfPco($sfPadraoId, $sfPadraoIdDuplicado)
    {
        $sfPco = SfPco::where('sfpadrao_id', $sfPadraoId);

        if (!$sfPco) {
            return false;
        }

        $sfPcoDuplicado = $sfPco->first()->replicate();
        $sfPcoDuplicado->sfpadrao_id = $sfPadraoIdDuplicado;
        $sfPcoDuplicado->push();

        $this->pcoId = $sfPco->first()->id;

        return $sfPcoDuplicado->id;
    }

    /**
     * Duplica anterior registro sfpcoitem
     *
     * @param $sfPcoId
     * @param $sfPcoIdDuplicado
     * @return bool
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function duplicaSfPcoItem($sfPcoId, $sfPcoIdDuplicado)
    {
        // Um PcoItem por apropriação... ou um PcoItem a cada fatura (que podem ser 1-n)?
        $sfPcoItem = SfPcoItem::where('sfpco_id', $sfPcoId);

        if (!$sfPcoItem) {
            return false;
        }

        $sfPcoItemDuplicado = $sfPcoItem->first()->replicate();
        $sfPcoItemDuplicado->sfpco_id = $sfPcoIdDuplicado;
        // $sfPcoItemDuplicado->numne = '';
        $sfPcoItemDuplicado->vlr = $this->apropriacaoValor;
        $sfPcoItemDuplicado->push();

        return true;
    }

    protected function apropriacaoConcluida($apropriacaoId)
    {
        $apropriacao = ApropriacaoFaturas::find($apropriacaoId);

        $apropriacao->fase_id = ApropriacaoFaturas::FASE_CONCLUIDA;
        $apropriacao->save();
    }

    /**
     * Monta $html com definições para montagem do Grid
     *
     * @return \Yajra\DataTables\Html\Builder
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function retornaHtmlGrid()
    {
        $html = $this->htmlBuilder;

        $html->addColumn([
            'data' => 'numero',
            'name' => 'numero',
            'title' => 'Contrato'
        ]);

        $html->addColumn([
            'data' => 'fornecedor',
            'name' => 'fornecedor',
            'title' => 'Fornecedor'
        ]);

        $html->addColumn([
            'data' => 'ateste',
            'name' => 'ateste',
            'title' => 'Dt. Ateste'
        ]);

        $html->addColumn([
            'data' => 'vencimento',
            'name' => 'vencimento',
            'title' => 'Dt. Vencimento'
        ]);

        $html->addColumn([
            'data' => 'faturas',
            'name' => 'faturas',
            'title' => 'Faturas'
        ]);

        $html->addColumn([
            'data' => 'total',
            'name' => 'total',
            'title' => 'Total',
            'class' => 'text-right'
        ]);

        $html->addColumn([
            'data' => 'valor',
            'name' => 'valor',
            'title' => 'Valor informado',
            'class' => 'text-right'
        ]);

        $html->addColumn([
            'data' => 'fase',
            'name' => 'fase',
            'title' => 'Fase'
        ]);

        $html->addColumn([
            'data' => 'action',
            'name' => 'action',
            'title' => 'Ações',
            'class' => 'text-nowrap',
            'orderable' => false,
            'searchable' => false
        ]);

        $html->parameters([
            'processing' => true,
            'serverSide' => true,
            'responsive' => true,
            'info' => true,
            'order' => [
                0,
                'desc'
            ],
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

    /**
     * Monta $html com definições da coluna Ações
     *
     * @param $registro
     * @return string
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function retornaAcoes($registro)
    {
        $apropriacaoId = $registro->id;
        $finalizada = $registro->fase_id == ApropriacaoFaturas::FASE_CONCLUIDA ? true : false;

        $editar = $this->retornaBtnEditar($apropriacaoId);
        $excluir = $this->retornaBtnExcluir($apropriacaoId);
        $relatorio = $this->retornaBtnRelatorio($apropriacaoId);
        $dochabil = $this->retornaBtnDocHabil($apropriacaoId);

        $acaoFinalizada = $relatorio . $dochabil;
        $acaoEmAndamento = $editar . $excluir;

        if ($finalizada) {
            $acaoEmAndamento .= $relatorio;
        }

        $acoes = '';
        $acoes .= '<div class="btn-group">';
        $acoes .= ($finalizada == true) ? $acaoFinalizada : $acaoEmAndamento;
        $acoes .= '</div>';

        return $acoes;
    }

    /**
     * Monta $html para exibição, ou não, do botão de excluir
     *
     * @param $apropriacaoId
     * @return string
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function retornaBtnExcluir($apropriacaoId)
    {
        $excluir = '';
        $excluir .= '<a '; //href="" ';
        $excluir .= "class='btn btn-default btn-sm '";
        $excluir .= 'data-toggle="modal" ';
        $excluir .= 'data-target="#confirmaExclusaoApropriacaoFatura" ';
        $excluir .= 'data-id="' . $apropriacaoId . '"';
        $excluir .= 'name="delete_modal" ';
        $excluir .= 'title="Excluir apropriação da fatura">';
        $excluir .= '<i class="fa fa-trash"></i></a>';

        return $excluir;
    }

    /**
     * Monta $html para exibição, ou não, do botão de edição
     *
     * @param $apropriacaoId
     * @return string
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function retornaBtnEditar($apropriacaoId)
    {
        $editar = '';
        // Exibir form com campos para geração da apropriação
        $editar .= '<a href="#" ';
        $editar .= "class='btn btn-default btn-sm '";
        $editar .= 'title="Informar dados para apropriação da fatura">';
        $editar .= '<i class="fa fa-play"></i></a>';

        return $editar;
    }

    protected function retornaBtnRelatorio($apropriacaoId)
    {
        $relatorio = '';
        $relatorio .= '<a href="/apropriacao/fatura/' . $apropriacaoId . '" ';
        $relatorio .= "class='btn btn-default btn-sm '";
        $relatorio .= 'title="Relatório da apropriação">';
        $relatorio .= '<i class="fa fa-list-alt"></i></a>';

        return $relatorio;
    }

    protected function retornaBtnDocHabil($apropriacaoId)
    {
        $docHabil = '';
        $docHabil .= '<a href="/apropriacao/fatura/' . $apropriacaoId . '/dochabil/" ';
        $docHabil .= "class='btn btn-default btn-sm '";
        $docHabil .= 'title="Documento Hábil Apropriado">';
        $docHabil .= '<i class="fa fa-file-o"></i></a>';

        return $docHabil;
    }
}
