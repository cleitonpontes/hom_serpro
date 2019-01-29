<?php
/**
 * Controller com métodos e funções do Passo 1 da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */
namespace App\Http\Controllers\Folha\Apropriacao;

use App\Forms\ApropriacaoPasso1Form;
use App\Models\Apropriacao;
use App\Models\Apropriacaofases;
use App\Models\Apropriacaoimportacao;
use Illuminate\Http\Request;

/**
 * Disponibiliza as funcionalidades específicas para o Passo 1 - Importar DDP
 *
 * @category Conta
 * @package Conta_Folha_Apropriacao_Passo1
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 * @copyright AGU - Advocacia Geral da União ©2018 <http://www.agu.gov.br>
 * @copyright Basis Tecnologia da Informação ©2018 <http://www.basis.com.br>
 * @license MIT License. <https://opensource.org/licenses/MIT>
 */
class Passo1Controller extends BaseController
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function novo()
    {
        $form = \FormBuilder::create(ApropriacaoPasso1Form::class, [
            'url' => route('folha.apropriacao.passo.1.grava'),
            'method' => 'POST'
        ]);

        return view('backpack::mod.folha.apropriacao.passo1', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function adiciona(Request $request)
    {
        $pathRetorno = '/folha/apropriacao/passo/1';
        $this->iniciaVariaveisDeSessao();

        $form = \FormBuilder::create(ApropriacaoPasso1Form::class);
        if (! $form->isValid()) {
            return redirect()->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        // Isola arquivos selecionados
        $arquivos = $this->retornaArquivos($request, $form);

        // Efetua validações quanto a quantidade de arquivos
        $msgErro = $this->validaQuantidadeArquivos($arquivos);

        if ($msgErro != '') {
            $this->exibeMensagemErro($msgErro);
            return redirect($pathRetorno)->withInput();
        }

        // NOTA: Os ERROS, a partir daqui, não mais geram mensagem de erro imediatamente. Os mesmos são gravados em
        // sessão para posterior exibição.

        // Efetua diversas validações nos arquivos, retornando mensagem de erro quando inválido
        $this->validaArquivos($arquivos);

        $erros = session('validacao.erros');
        if ($erros != null) {
            $msgAlerta = config('mensagens.import-ddp-pendencia-validacoes');
            $this->exibeMensagemAlerta($msgAlerta);
            return redirect($pathRetorno)->withInput();
        }

        // Se arquivos selecionados não tem erros, passa a gravação dos dados...
        $dadosApropriacao = $this->montaDadosApropriacao();

        // Grava de fato a nova apropriação
        $apropriacao = Apropriacao::create($dadosApropriacao);

        // Apresenta resultados na tela - resumo dos arquivos importados
        $sucesso = session('validacao.arquivos');
        $sucesso['apropriacao'] = $apropriacao;
        session(['validacao.sucesso' => $sucesso]);

        // Grava dados da importação
        $apropriacaoId = $apropriacao->id;
        $dados = $this->montaDadosImportacao($apropriacaoId);

        Apropriacaoimportacao::insert($dados);

        $mensagem = config('mensagens.apropriacao-novo');
        $this->exibeMensagemSucesso($mensagem);
        return redirect($pathRetorno)->withInput();
    }

    /**
     * Inicia variáveis de sessão com valores nulo
     */
    protected function iniciaVariaveisDeSessao()
    {
        session(['validacao.sucesso' => null]);
        session(['validacao.erros' => null]);
        session(['validacao.arquivos' => null]);

        session(['validacao.arquivo.nome' => null]);
        session(['validacao.arquivo.conteudo' => null]);

        session(['validacao.competencia' => null]);
        session(['validacao.nivel' => null]);
        session(['validacao.valor.bruto' => null]);
        session(['validacao.valor.liquido' => null]);
    }

    /**
     * Retornar os arquivos selecionados para importação
     *
     * @param Request $request
     * @param ApropriacaoPasso1Form $form
     * @return array
     */
    private function retornaArquivos(Request $request, $form)
    {
        $arquivos = array();

        if ($request->hasFile('arquivos')) {
            $campos = $form->getFieldValues();
            $arquivos = $campos['arquivos'];
        }

        return $arquivos;
    }

    /**
     * Valida quantidade de arquivos selecionados na importação
     *
     * @param UploadedFile $arquivos
     * @return string
     */
    private function validaQuantidadeArquivos($arquivos)
    {
        // Verifica quantidade de arquivos a importar antes de demais validações
        $qtdeArquivos = count($arquivos);

        if ($qtdeArquivos < 1 || $qtdeArquivos > 3) {
            return config('mensagens.import-ddp-qtde-arquivos');
        }

        return '';
    }

    /**
     * Valida os arquivos selecionados na importação
     *
     * @param UploadedFile $arquivos
     */
    private function validaArquivos($arquivos)
    {
        // Validações iniciais para cada arquivo
        foreach ($arquivos as $arquivo) {
            // Grava informações para tratamento de erro
            $nomeArquivo = $arquivo->getClientOriginalName();
            session(['validacao.arquivo.nome' => $nomeArquivo]);

            // Verifica arquivos com extensão inválidas
            $extensaoValida = $this->validaExtensao($arquivo);

            if ($extensaoValida) {
                $this->validaArquivo($arquivo);
            }
        }
    }

    /**
     * Retorna array com dados para gravação de nova apropriação
     *
     * @return array
     */
    private function montaDadosApropriacao()
    {
        // Prepara para gravar uma nova apropriação
        $competenciaUnica = session('validacao.competencia');
        $ug = session('user_ug');
        $niveisEmMemoria = session('validacao.nivel');
        $valorBruto = session('validacao.valor.bruto');
        $valorLiquido = session('validacao.valor.liquido');
        $arquivosEmMemoria = session('validacao.arquivos');

        $niveis = '';
        foreach ($niveisEmMemoria as $nivel) {
            $niveis .= $nivel;
        }

        $nomeDosArquivos = '';
        foreach ($arquivosEmMemoria as $arquivo) {
            $nomeDosArquivos .= $arquivo['arquivo'] . ', ';
        }
        $nomeDosArquivos = substr($nomeDosArquivos, 0, - 2);

        $dados = array();
        
        $dados['competencia'] = $competenciaUnica;
        $dados['ug'] = $ug;
        $dados['nivel'] = $niveis;
        $dados['valor_bruto'] = $valorBruto;
        $dados['valor_liquido'] = $valorLiquido;
        $dados['fase_id'] = Apropriacaofases::APROP_FASE_IDENTIFICAR_SITUACAO;
        $dados['arquivos'] = $nomeDosArquivos;

        return $dados;
    }

    /**
     * Retorna array com dados da importação para gravação
     *
     * @param number $apropriacaoId
     * @return array
     */
    private function montaDadosImportacao($apropriacaoId)
    {
        // Preparação para as validações adicionais
        $campoCompetencia = config('importacao.ddp-campo-competencia');
        $campoNivel = config('importacao.ddp-campo-nivel');
        $campoCategoria = config('importacao.ddp-campo-categoria');
        $campoConta = config('importacao.ddp-campo-conta');
        $campoRubrica = config('importacao.ddp-campo-rubrica');
        $campoDescricao = config('importacao.ddp-campo-descricao');
        $campoValor = config('importacao.ddp-campo-valor');
        $categoriaRodape = config('importacao.ddp-categoria-rodape');

        // Arquivos a importar e seus conteúdos
        $arquivos = session('validacao.arquivos');

        $numRegistro = 0;
        $dados = array();

        foreach ($arquivos as $arquivo) {
            $ordem = 1;
            $nomeArquivo = $arquivo['arquivo'];
            $conteudo = $arquivo['conteudo'];

            foreach ($conteudo as $registro) {
                // Apenas registros que não forem do rodapé
                if ($registro[$campoCategoria] != $categoriaRodape) {
                    $dados[$numRegistro]['apropriacao_id'] = $apropriacaoId;
                    $dados[$numRegistro]['competencia'] = $registro[$campoCompetencia];
                    $dados[$numRegistro]['nivel'] = $registro[$campoNivel];
                    $dados[$numRegistro]['categoria'] = $registro[$campoCategoria];
                    $dados[$numRegistro]['conta'] = $registro[$campoConta];
                    $dados[$numRegistro]['rubrica'] = $registro[$campoRubrica];
                    $dados[$numRegistro]['descricao'] = utf8_encode(trim($registro[$campoDescricao]));
                    $dados[$numRegistro]['valor'] = $registro[$campoValor];
                    $dados[$numRegistro]['numero_linha'] = $ordem ++;
                    $dados[$numRegistro]['linha'] = utf8_encode($registro['linha']);
                    $dados[$numRegistro ++]['nome_arquivo'] = $nomeArquivo;
                }
            }
        }

        return $dados;
    }

    /**
     * Valida extensão de cada arquivo informado na importação
     *
     * @param UploadedFile $arquivo
     * @return boolean
     */
    private function validaExtensao($arquivo)
    {
        $extensaoValida = config('importacao.ddp-extensao-arquivo');

        // Valida extensão
        if (strtolower($arquivo->getClientOriginalExtension()) != $extensaoValida) {
            $descErro = config('mensagens.import-ddp-extensao-invalida');
            $this->defineDadosErro(0, $descErro);

            return false;
        }

        return true;
    }

    /**
     * Valida diversos linhas bem como cabeçalho e rodapé, de cada arquivo informado na importação
     *
     * @param UploadedFile $arquivo
     * @return boolean
     */
    private function validaArquivo($arquivo)
    {
        // Converte arquivo em array...
        $conteudo = $this->retornaConteudoArquivo($arquivo);

        // Isola cabeçalho
        $primeiraLinha = array_shift($conteudo);
        $cabecalho = $primeiraLinha['linha'];
        session(['validacao.arquivo.conteudo' => $conteudo]);

        // Valida cabeçalho
        $cabecalhoValido = $this->validaCabecalho($cabecalho);

        if ($cabecalhoValido) {
            // Valida arquivo e rodapé
            $this->validacoesAdicionais($conteudo);

            // Valida única competência
            $competenciaValida = $this->validaCompetenciaUnica($conteudo);

            if (! $competenciaValida) {
                $descErro = config('mensagens.import-ddp-competencias-multiplas');
                $this->defineDadosErro(0, $descErro);

                return false;
            }

            $this->gravaArquivoEmMemoria();
        }
    }

    /**
     * Retorna o conteúdo (linhas) do arquivo
     *
     * @param UploadedFile $arquivo
     * @return array
     */
    private function retornaConteudoArquivo($arquivo)
    {
        // Lê o arquivo como array
        $linhas = file($arquivo->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $conteudo = array();
        $separador = config('importacao.separador-campos');

        foreach ($linhas as $num => $linha) {
            $conteudo[$num] = explode($separador, $linha);
            $conteudo[$num]['linha'] = $linha;
        }

        return $conteudo;
    }

    /**
     * Valida cabeçalho do arquivo
     *
     * @param string $cabecalho
     * @return boolean
     */
    private function validaCabecalho($cabecalho)
    {
        $cabecalhoEsperado = config('importacao.ddp-cabecalho', '');

        // Ajusta cabeçalho
        $cabecalhoAtual = trim(strtolower($cabecalho));

        // Valida o cabeçalho
        if ($cabecalhoAtual != $cabecalhoEsperado) {
            $descErro = config('mensagens.import-ddp-cabecalho-invalido');
            $this->defineDadosErro(1, $descErro);

            return false;
        }

        return true;
    }

    /**
     * Efetua validações adicionais no conteúdo do arquivo
     *
     * @param array $conteudo
     * @return boolean
     */
    private function validacoesAdicionais($conteudo)
    {
        $valida = true;

        // Preparação para as validações adicionais
        $qtdeCampos = count(config('importacao.ddp-campos'));
        $campoCategoria = config('importacao.ddp-campo-categoria');
        $campoValor = config('importacao.ddp-campo-valor');
        $campoDescricao = config('importacao.ddp-campo-descricao');
        $categoriaRodape = config('importacao.ddp-categoria-rodape');

        $totais = array();
        $rodape = array();

        foreach ($conteudo as $id => $linha) {
            // Valida a quantidade de campos, por linha
            $qtdeColunas = count($linha) - 1; // -1, para desconsiderar o array['linha']
            $qtdeColunaValido = ($qtdeColunas == $qtdeCampos);
            $valida = ($qtdeColunaValido == false) ? false : $valida;

            if ($qtdeColunaValido) {
                // Valida campos de cada linha
                $camposValidos = $this->validaCamposPorLinha($id, $linha);
                $valida = ($camposValidos == false) ? false : $valida;

                // Se campos validados, calcula valores para posterior validação do rodapé
                if ($camposValidos) {
                    $valor = $linha[$campoValor];

                    // Incrementa os totais por categoria
                    $totais = $this->incrementaTotais($totais, $linha[$campoCategoria], $valor);

                    // Isola rodapé
                    if ($linha[$campoCategoria] == $categoriaRodape) {
                        $descricao = strtolower(trim($linha[$campoDescricao]));
                        $rodape[$descricao] = $valor;
                    }
                }

                // TODO: Valida rubricas - ida ao banco
            } else {
                $descErro = config('mensagens.import-ddp-qtde-campos-invalida');
                $this->defineDadosErro($id + 1, $descErro);
            }
        }

        if ($valida) {
            // Valida totais por categoria no rodapé
            $rodapeValido = $this->validaValoresRodape($id, $rodape, $totais);

            $valida = ($rodapeValido == false) ? false : $valida;
        }

        return $valida;
    }

    /**
     * Incrementa valores dos totais por categoria
     *
     * @param array $totais
     * @param number $categoria
     * @param number $valor
     * @return number
     */
    private function incrementaTotais($totais, $categoria, $valor)
    {
        $valor = (is_numeric($valor)) ? $valor : 0;

        if (! isset($totais[$categoria])) {
            $totais[$categoria] = 0;
        }

        $totais[$categoria] += $valor;

        return $totais;
    }

    /**
     * Valida os totais do rodapé do arquivo
     *
     * @param number $id
     * @param array $rodape
     * @param array $totais
     * @return boolean
     */
    private function validaValoresRodape($id, $rodape, $totais)
    {
        $valida = true;

        $bruto = (string) isset($rodape['bruto']) ? $rodape['bruto'] : 0;
        $desconto = (string) isset($rodape['desconto']) ? $rodape['desconto'] : 0;
        $liquido = (string) isset($rodape['liquido']) ? $rodape['liquido'] : 0;
        
        $total1 = isset($totais[1]) ? $totais[1] : 0;
        $total2 = isset($totais[2]) ? $totais[2] : 0;
        $total3 = isset($totais[3]) ? $totais[3] : 0;
        $total4 = isset($totais[4]) ? $totais[4] : 0;
        
        // TODO: Nesse momento, não se utilizam os totais 6 nem 7
        // $total6 = isset($totais[6]) ? $totais[6] : 0;
        // $total7 = isset($totais[7]) ? $totais[7] : 0;
        
        $calculadoBruto = (string) ($total1 + $total4);
        $calculadoDesconto = (string) ($total2 + $total3);
        $calculadoLiquido = (string) ($bruto - $desconto);
        
        // Confere valor Bruto...
        if ($calculadoBruto != $bruto) {
            $descErro = config('mensagens.import-ddp-rodape-bruto-nao-confere');
            $this->defineDadosErro($id - 2, $descErro);

            $valida = false;
        }

        // Confere valor Desconto...
        if ($calculadoDesconto != $desconto) {
            $descErro = config('mensagens.import-ddp-rodape-desconto-nao-confere');
            $this->defineDadosErro($id - 1, $descErro);

            $valida = false;
        }

        // Confere valor Líquido...
        if ($calculadoLiquido != $liquido) {
            $descErro = config('mensagens.import-ddp-rodape-liquido-nao-confere');
            $this->defineDadosErro($id, $descErro);

            $valida = false;
        }

        session(['validacao.valor.bruto' => $bruto]);
        session(['validacao.valor.liquido' => $liquido]);
        
        return $valida;
    }

    /**
     * Valida se há apenas uma competência por arquivo
     *
     * @param array $conteudo
     * @return boolean
     */
    private function validaCompetenciaUnica($conteudo)
    {
        $campoCompetencia = config('importacao.ddp-campo-competencia');

        // Monta array apenas da key = idCampo
        $dados = array_column($conteudo, $campoCompetencia);

        // Verifica competências distintas
        $qtdeEncontrada = count(array_unique($dados));

        if ($qtdeEncontrada == 1) {
            $competenciaUnica = $dados[0];
            session(['validacao.competencia' => $competenciaUnica]);

            return true;
        }

        return false;
    }

    /**
     * Valida os campos esperados em cada linha
     *
     * @param number $id
     * @param string $linha
     * @return boolean
     */
    private function validaCamposPorLinha($id, $linha)
    {
        // Valida competência
        $competencia = $linha[config('importacao.ddp-campo-competencia')];

        if (! $this->validaCompetencia($competencia)) {
            $descErro = config('mensagens.import-ddp-linha-campo-competencia');
            $this->defineDadosErro($id + 1, $descErro);

            return false;
        }

        // Valida nível
        $nivel = $linha[config('importacao.ddp-campo-nivel')];

        if (! $this->validaNivel($nivel)) {
            $descErro = config('mensagens.import-ddp-linha-campo-nivel');
            $this->defineDadosErro($id + 1, $descErro);

            return false;
        }

        // Valida categoria
        $categoria = $linha[config('importacao.ddp-campo-categoria')];

        if (! $this->validaCategoria($categoria)) {
            $descErro = config('mensagens.import-ddp-linha-campo-categoria');
            $this->defineDadosErro($id + 1, $descErro);

            return false;
        }

        // Valida conta
        $conta = $linha[config('importacao.ddp-campo-conta')];

        if (! $this->validaConta($conta)) {
            $descErro = config('mensagens.import-ddp-linha-campo-conta');
            $this->defineDadosErro($id + 1, $descErro);

            return false;
        }

        // Valida rubrica
        $rubrica = $linha[config('importacao.ddp-campo-rubrica')];

        if (! $this->validaRubrica($rubrica)) {
            $descErro = config('mensagens.import-ddp-linha-campo-rubrica');
            $this->defineDadosErro($id + 1, $descErro);

            return false;
        }

        // Valida descrição
        $descricao = trim($linha[config('importacao.ddp-campo-descricao')]);

        if (! $this->validaDescricao($descricao)) {
            $descErro = config('mensagens.import-ddp-linha-campo-descricao');
            $this->defineDadosErro($id + 1, $descErro);

            return false;
        }

        // Valida valor
        $valor = $linha[config('importacao.ddp-campo-valor')];

        if (! $this->validaValor($valor)) {
            $descErro = config('mensagens.import-ddp-linha-campo-valor');
            $this->defineDadosErro($id + 1, $descErro);

            return false;
        }

        return true;
    }

    /**
     * Valida competência informada
     *
     * @param string $competencia
     * @return boolean
     */
    private function validaCompetencia($competencia)
    {
        // Tamanho de 7 caracteres
        if (strlen($competencia) != 7) {
            return false;
        }

        // Data válida
        if (! checkdate(substr($competencia, 5, 2), 1, substr($competencia, 0, 4))) {
            return false;
        }

        return true;
    }

    /**
     * Valida nível informado
     *
     * @param string $nivel
     * @return boolean
     */
    private function validaNivel($nivel)
    {
        // Tamanho de 1 caracter
        if (strlen($nivel) != 1) {
            return false;
        }

        // Está no conjunto abaixo
        $niveis = ['A', 'B', 'E', 'T'];
        if (! in_array($nivel, $niveis)) {
            return false;
        }
        
        if ($nivel != 'T') {
            // Não grava registro, se o nível for 'T', meramente de total
            $this->gravaNivelEmMemoria($nivel);
        }

        return true;
    }

    /**
     * Valida categoria informada
     *
     * @param string $categoria
     * @return boolean
     */
    private function validaCategoria($categoria)
    {
        // Tamanho de 1 caracter
        if (strlen($categoria) != 1) {
            return false;
        }

        // Está no conjunto abaixo
        // $categorias = ['1', '2', '3', '4', '5', '6', '7'];
        $categorias = ['1', '2', '5'];
        if (! in_array($categoria, $categorias)) {
            return false;
        }

        return true;
    }

    /**
     * Valida conta informada
     *
     * @param string $conta
     * @return boolean
     */
    private function validaConta($conta)
    {
        // Tamanho de 8 caracteres
        if (strlen($conta) != 8) {
            return false;
        }

        // Se é numérico
        if (! is_numeric($conta)) {
            return false;
        }

        return true;
    }

    /**
     * Valida rubrica informada
     *
     * @param string $rubrica
     * @return boolean
     */
    private function validaRubrica($rubrica)
    {
        // Tamanho de 5 caracteres
        if (strlen($rubrica) != 5) {
            return false;
        }

        // Se é numérico
        if (! is_numeric($rubrica)) {
            return false;
        }

        return true;
    }

    /**
     * Valida descrição informada
     *
     * @param string $descricao
     * @return boolean
     */
    private function validaDescricao($descricao)
    {
        // Tamanho máximo de 30 caracteres
        if (strlen($descricao) > 40) {
            return false;
        }

        return true;
    }

    /**
     * Valida valor informado
     *
     * @param string $valor
     * @return boolean
     */
    private function validaValor($valor)
    {
        // Se é numérico
        if (! is_numeric($valor)) {
            return false;
        }

        return true;
    }

    /**
     * Incrementa mensagem de erro para futura exibição
     *
     * @param number $numLinha
     * @param string $descErro
     */
    private function defineDadosErro($numLinha, $descErro)
    {
        $erros = session('validacao.erros');
        $nomeArquivo = session('validacao.arquivo.nome');

        // $erros[$nomeArquivo][$numLinha] = $descErro;
        $erros[] = [
            'arquivo' => $nomeArquivo,
            'linha' => $numLinha,
            'descricao' => $descErro
        ];

        session(['validacao.erros' => $erros]);
    }

    /**
     * Grava em memória todos os presentes níveis distintos
     *
     * @param string $nivel
     */
    private function gravaNivelEmMemoria($nivel)
    {
        $niveisMemoria = session('validacao.nivel');
        $niveis = is_null($niveisMemoria) ? array() : $niveisMemoria;

        if (! in_array($nivel, $niveis)) {
            $nivelNovo = str_split($nivel, 1);
            
            $niveis = array_merge($niveis, $nivelNovo);
            array_multisort($niveis, SORT_ASC);
        }
        
        session(['validacao.nivel' => $niveis]);
    }

    /**
     * Grava em memória os dados dos arquivos informados
     */
    private function gravaArquivoEmMemoria()
    {
        $arquivosEmMemoria = session('validacao.arquivos');
        $nomeArquivo = session('validacao.arquivo.nome');
        $conteudo = session('validacao.arquivo.conteudo');

        $arquivosEmMemoria[] = [
            'arquivo' => $nomeArquivo,
            'conteudo' => $conteudo
        ];

        session(['validacao.arquivos' => $arquivosEmMemoria]);
    }
}
