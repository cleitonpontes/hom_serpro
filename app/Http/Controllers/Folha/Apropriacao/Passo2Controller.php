<?php
/**
 * Controller com métodos e funções do Passo 2 da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */
namespace App\Http\Controllers\Folha\Apropriacao;

use App\Models\Apropriacaoimportacao;
use App\Models\Situacoes;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

/**
 * Disponibiliza as funcionalidades específicas para o Passo 2 - Identificar Situação
 *
 * @category Conta
 * @package Conta_Folha_Apropriacao_Passo2
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 * @copyright AGU - Advocacia Geral da União ©2018 <http://www.agu.gov.br>
 * @copyright Basis Tecnologia da Informação ©2018 <http://www.basis.com.br>
 * @license MIT License. <https://opensource.org/licenses/MIT>
 */
class Passo2Controller extends BaseController
{

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param number $apid
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $apid)
    {
        $modelo = new Apropriacaoimportacao();
        $importacoes = $modelo->retornaListagemPasso2($apid);

        $modeloSituacoes = new Situacoes();
        $situacoes = $modeloSituacoes->retornaListagemComoArray();
        session(['identificacao.situacao.situacoes' => $situacoes]);
        
        if ($request->ajax()) {
            $grid = DataTables::of($importacoes);
            
            $grid->addColumn('situacao', function ($importacao) {
                // Apenas para registros não atualizados anteriormente!
                if ($importacao->situacao_original == null) {
                    $dados = $this->retornaSituacaoVpd($importacao);

                    if (count($dados) > 0) {
                        // Se ainda não gravou...
                        $situacao = $dados['situacao'];
                        $vpd = $dados['vpd'];

                        // Atualiza Situação e VPD
                        $this->atualizaSituacao($importacao->apropriacao_id, $importacao->id, $situacao, $vpd);
                    }
                }

                // Ações disponíveis
                $situacoes = $this->retornaSituacoes($importacao);

                return $situacoes;
            })->addColumn('vpd', function ($importacao) {
                // VPDs para listar
                return $this->retornaVpds($importacao);
            });

            $grid->rawColumns(['situacao', 'vpd']);

            return $grid->make(true);
        }

        $html = $this->retornaGrid();

        return view('backpack::mod.folha.apropriacao.passo2', compact('html'));
    }

    /**
     * Atualiza situação
     *
     * @param Request $request
     * @param number $id
     * @param string $situacao
     * @return \Illuminate\Http\Response
     */
    public function atualiza($apid, $id, $situacao, $vpd)
    {
        return $this->atualizaSituacao($apid, $id, $situacao, $vpd, true);
    }

    /**
     * Verifica se pode ou não avançar ao próximo passo
     *
     * @param Request $request
     * @return boolean
     */
    public function verificaPodeAvancar(Request $request)
    {
        $apid = $request->apid;
        $modelo = new Apropriacaoimportacao();

        // Verifica se já pode passar para o próximo passo
        $qtde = $modelo->retornaQtdeRegistroComSituacaoInformada($apid);
        
        return ($qtde == 0);
    }

    /**
     * Retorna mensagem no caso de erro ao avançar
     *
     * @return string
     */
    public function retornaMensagemErroAvanco()
    {
        return config('mensagens.apropriacao-situacao-pendencias');
    }
    
    /**
     * Monta $html com definições do Grid
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    private function retornaGrid()
    {
        $html = $this->htmlBuilder;
        
        $html->addColumn([
            'data' => 'natureza',
            'name' => 'natureza',
            'title' => 'Natureza <br />da despesa'
        ]);
        $html->addColumn([
            'data' => 'subitem',
            'name' => 'subitem',
            'title' => 'Sub Item'
        ]);
        $html->addColumn([
            'data' => 'nivel',
            'name' => 'nivel',
            'title' => 'Nível'
        ]);
        $html->addColumn([
            'data' => 'categoria',
            'name' => 'categoria',
            'title' => 'Categoria'
        ]);
        $html->addColumn([
            'data' => 'rubrica',
            'name' => 'rubrica',
            'title' => 'Rubrica'
        ]);
        $html->addColumn([
            'data' => 'situacao',
            'name' => 'situacao',
            'title' => 'Situação',
            'orderable' => true,
            'searchable' => false
        ]);
        $html->addColumn([
            'data' => 'vpd',
            'name' => 'vpd',
            'title' => 'VPD',
            'orderable' => true,
            'searchable' => false
        ]);
        $html->addColumn([
            'data' => 'conta',
            'name' => 'conta',
            'visible' => false,
            'title' => 'ND Completa'
        ]);
        
        $html->parameters([
            'processing' => true,
            'serverSide' => true,
            'responsive' => true,
            'info' => true,
            'autoWidth' => true,
            'paging' => true,
            'lengthChange' => true,
            'language' => [
                'url' => "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
            ]
        ]);
        
        return $html;
    }
    
    /**
     * Retorna Situação e VPD, conforme registro da importação
     *
     * @param array $importacao
     * @return array
     */
    private function retornaSituacaoVpd($importacao)
    {
        $achados = $this->retornaAchados($importacao);

        // Quantidade de situações por registro
        $qtdeAchados = count($achados);

        $dados = array();
        foreach ($achados as $achado) {
            if ($qtdeAchados == 1) {
                $dados['situacao'] = $achado['situacao'];
                $dados['vpd'] = $achado['vpd'];
            }
        }

        return $dados;
    }

    /**
     * Retorna html das ações disponíveis
     *
     * @param number $apropriacaoId
     * @param string $inativo
     * @return string
     */
    private function retornaSituacoes($importacao)
    {
        $achados = $this->retornaAchados($importacao);

        // Quantidade de situações por registro
        $qtdeAchados = count($achados);

        // Monta parte das string para montagem da exibição do campo
        $formId = $importacao->apropriacao_id . '_' . $importacao->id;
        $situacaoAtual = $importacao->situacao;
        $formParte = '';
        $sitParte = '';

        foreach ($achados as $achado) {
            $situacao = $achado['situacao'];
            $vpd = $achado['vpd'];
            $selecionado = ($situacao == $situacaoAtual) ? ' checked ' : '';
            $campoId = $formId . '_' . $situacao . '_' . $vpd;

            $formParte .= "<input ";
            $formParte .= "type='radio' ";
            $formParte .= "class='situacao' ";
            $formParte .= "style='margin: 0px;' ";
            $formParte .= "name='$formId' ";
            $formParte .= "id='$campoId' ";
            $formParte .= "data-apid='$importacao->apropriacao_id' ";
            $formParte .= "data-id='$importacao->id' ";
            $formParte .= "data-situacao='$situacao' ";
            $formParte .= "data-vpd='$vpd' ";
            $formParte .= "$selecionado ";
            $formParte .= '> ';
            $formParte .= "<label for='$campoId' style='margin: 0px;'>";
            $formParte .= "$situacao";
            $formParte .= "</label>";
            $formParte .= '<br />';

            $sitParte .= "$situacao <br />";
        }

        $formulario = $this->montaFormulario($qtdeAchados, $formParte, $formId);
        $campo = $this->montaCampoSituacao($qtdeAchados, $sitParte, $formulario);

        return $campo;
    }
    
    /**
     * Retorna html das ações disponíveis
     *
     * @param number $apropriacaoId
     * @param string $inativo
     * @return string
     */
    private function retornaVpds($importacao)
    {
        $achados = $this->retornaAchados($importacao);
        
        // Quantidade de situações por registro
        $qtdeAchados = count($achados);
        
        // Monta parte das string para montagem da exibição do campo
        $vpdParte = '';
        
        foreach ($achados as $achado) {
            $vpd = $achado['vpd'];
            $vpdParte .= "$vpd <br />";
        }
        
        $campo = '';
        $campo .= '<div class="btn-group">';
        $campo .= '<!-- ' . $qtdeAchados . ' -->';
        $campo .= $vpdParte;
        $campo .= '</div>';
        
        return $campo;
    }
    
    /**
     * Atualiza registro importado com suas respectivas Situação e VPD
     *
     * @param number $apid
     * @param number $id
     * @param string $situacao
     * @param string $vpd
     * @param boolean $forcaUpdate
     */
    private function atualizaSituacao($apid, $id, $situacao, $vpd, $forcaUpdate = false)
    {
        $modelo = new Apropriacaoimportacao();
        $registro = $modelo::find($id);
        
        $registro->situacao = $situacao;
        $registro->vpd = $vpd;
        
        if (!$forcaUpdate) {
            $registro->situacao_original = $situacao;
            $registro->vpd_original = $vpd;
        }
        
        $registro->save();
    }

    /**
     * Verifica existência de Situação e VPD por registro da importação
     *
     * @param array $importacao
     * @return array
     */
    private function retornaAchados($importacao)
    {
        // Dados de todas as situações - em memória
        $situacoes = session('identificacao.situacao.situacoes');

        // Filtra as situações existentes para cada registro
        $achados = array_filter($situacoes, function ($situacao) use ($importacao) {
            return (
                $situacao['natureza'] == $importacao->conta &&
                $situacao['nivel'] == $importacao->nivel &&
                $situacao['categoria'] == $importacao->categoria &&
                $situacao['rubrica'] == $importacao->rubrica
            );
        });

        return $achados;
    }

    /**
     * Monta formulário html
     *
     * @param number $qtdeAchados
     * @param string $formParte
     * @param string $formId
     * @return string
     */
    private function montaFormulario($qtdeAchados, $formParte, $formId)
    {
        $formulario = '';
        if ($qtdeAchados > 1) {
            $formulario .= "<form id='frm_$formId'>";
            $formulario .= substr($formParte, 0, - 6);
            $formulario .= "</form>";
        }

        return $formulario;
    }

    /**
     * Monta html com DIV para ordenação do campo e um ou mais situações, com formulário se for o caso
     *
     * @param number $qtdeAchados
     * @param string $sitParte
     * @param string $formulario
     * @return string
     */
    private function montaCampoSituacao($qtdeAchados, $sitParte, $formulario)
    {
        $campo = '';
        $campo .= '<div class="btn-group">';
        $campo .= '<!-- ' . $qtdeAchados . ' -->';
        $campo .= ($formulario == '') ? $sitParte : $formulario;
        $campo .= '</div>';
        
        return $campo;
    }
}
