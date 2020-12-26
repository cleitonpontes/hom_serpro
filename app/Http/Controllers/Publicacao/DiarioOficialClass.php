<?php

namespace App\Http\Controllers\Publicacao;

use Alert;
use App\Http\Traits\BuscaCodigoItens;
use App\Jobs\AtualizaSituacaoPublicacaoJob;
use App\Jobs\PublicaPreviewOficioJob;
use App\Models\Codigoitem;
use App\Models\Contratohistorico;
use App\Models\ContratoPublicacoes;
use App\Models\Empenho;
use App\Models\Padroespublicacao;
use Exception;
use Illuminate\Support\Carbon;
use SoapHeader;
use SoapVar;
use PHPRtfLite;
use PHPRtfLite_Font;

class DiarioOficialClass extends BaseSoapController
{

    use BuscaCodigoItens;

    private $soapClient;
    private $securityNS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    private $Urlwsdl;
    private $username;
    private $password;


    public function __construct()
    {
        $this->Urlwsdl = config("publicacao.sistema.diario_oficial_uniao");
        $this->username = env('PUBLICACAO_DOU_USER');
        $this->password = env('PUBLICACAO_DOU_PWD');

        self::setWsdl($this->Urlwsdl);
        $node1 = new SoapVar($this->username, XSD_STRING, null, null, 'Username', $this->securityNS);
        $node2 = new SoapVar($this->password, XSD_STRING, null, null, 'Password', $this->securityNS);
        $token = new SoapVar(array($node1, $node2), SOAP_ENC_OBJECT, null, null, 'UsernameToken', $this->securityNS);
        $security = new SoapVar(array($token), SOAP_ENC_OBJECT, null, null, 'Security', $this->securityNS);
        $headers[] = new SOAPHeader($this->securityNS, 'Security', $security, false);

        $this->soapClient = InstanceSoapClient::init($headers);
    }

    public function consultaTodosFeriado()
    {
        try {
            $response = $this->soapClient->ConsultaTodosOrgaosPermitidos();

            dd($response);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function consultaSituacaoOficio($oficio_id)
    {
        try {
            $dados ['dados']['CPF'] = config('publicacao.usuario_publicacao');
            $dados ['dados']['IDOficio'] = $oficio_id;

            return $this->soapClient->ConsultaAcompanhamentoOficio($dados);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function oficioPreview($contrato_id)
    {
        try {
            $contratoHistorico = Contratohistorico::where('contrato_id', $contrato_id)
                ->orderBy('id', 'desc')
                ->first();

            $contratoPublicacoes = ContratoPublicacoes::where('contratohistorico_id', $contratoHistorico->id)
                ->orderBy('id', 'desc')
                ->first();

            $this->enviaPublicacao($contratoHistorico, $contratoPublicacoes);

            dd('fim');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function oficioPreviewNovo()
    {
        $data = Carbon::createFromFormat('Y-m-d', date('Y-m-d'));

//        $contratoPublicacoes = ContratoPublicacoes::where('data_publicacao', $data->addDay())
        $contratoPublicacoes = ContratoPublicacoes::where('id',98)
            ->orderBy('id', 'desc')
            ->get();

        foreach ($contratoPublicacoes as $contratoPublicacao) {
//            PublicaPreviewOficioJob::dispatch($contratoPublicacao)->onQueue('envia_preview_oficio');
            $this->enviaPublicacao($contratoPublicacao->contratohistorico, $contratoPublicacao);
        }
    }

    private function enviaPublicacao($contratoHistorico, $contratoPublicacoes)
    {
        $arrayPreview = $this->montaOficioPreview($contratoHistorico);
        $responsePreview = $this->soapClient->OficioPreview($arrayPreview);
        dd($responsePreview);
        if (!isset($responsePreview->out->publicacaoPreview->DadosMateriaResponse->HASH)) {
            $contratoPublicacoes->status = 'Erro Preview!';
            $contratoPublicacoes->status_publicacao_id = self::retornaIdCodigoItem('Situacao Publicacao','DEVOLVIDO PELA IMPRENSA');
            $contratoPublicacoes->log = $this->retornaErroValidacoesDOU($responsePreview);
            $contratoPublicacoes->texto_dou = $this->retornaTextoRtf($contratoHistorico);

            $contratoPublicacoes->save();

            return false;
        }

        $contratoPublicacoes->status = 'Preview';
        $contratoPublicacoes->texto_dou = self::retornaTextoModelo($contratoHistorico);
        $contratoPublicacoes->save();

        $this->oficioConfirmacao($contratoHistorico, $contratoPublicacoes);

        return true;
    }

    public function retornaErroValidacoesDOU($responsePreview)
    {

        $erro = 'VERIFICAR : ';

        $erro .= ($responsePreview->out->validacaoCliente != "OK") ? 'CLENTE: ' . $responsePreview->out->validacaoCliente . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoDataPublicacao != "OK") ? 'DATA PUBLICACAO: ' . $responsePreview->out->validacaoDataPublicacao . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoIdentificadorNorma != "OK") ? 'IDENTIFICOR NORMA: ' . $responsePreview->out->validacaoIdentificadorNorma . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoIdentificadorTipoPagamento != "OK") ? 'TIPO PAGAMENTO: ' . $responsePreview->out->validacaoIdentificadorTipoPagamento . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoNUP != "OK") ? 'NUP: ' . $responsePreview->out->validacaoNUP . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoRTF != "OK") ? 'TEXTO RTF: ' . $responsePreview->out->validacaoRTF . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoSIORGCliente != "OK") ? 'SIORG CLIENT: ' . $responsePreview->out->validacaoSIORGCliente . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoSIORGMateria != "OK") ? 'SIORG MATERIA: ' . $responsePreview->out->validacaoSIORGMateria . ' | ' : '';

        return $erro;
    }

    public function oficioConfirmacao(Contratohistorico $contratoHistorico, ContratoPublicacoes $contratoPublicacoes)
    {
        try {
            $arrayConfirmacao = $this->montaOficioConfirmacao($contratoHistorico);

            $responseConfirmacao = $this->soapClient->OficioConfirmacao($arrayConfirmacao);
            if (!isset($responseConfirmacao->out->publicacaoConfirmacao->DadosMateriaResponse->reciboConfirmacao)) {
                $contratoPublicacoes->status = 'Erro Ofício!';


                $contratoPublicacoes->status_publicacao_id = self::retornaIdCodigoItem('Situacao Publicacao','DEVOLVIDO PELA IMPRENSA');
                $contratoPublicacoes->log = json_encode($responseConfirmacao);
                $contratoPublicacoes->save();

                return false;
            }

            $contratoPublicacoes->status = 'Oficio';
            $contratoPublicacoes->status_publicacao_id = self::retornaIdCodigoItem('Situacao Publicacao','PUBLICADO');
            $contratoPublicacoes->transacao_id = $arrayConfirmacao['dados']['IDTransacao'];
            $contratoPublicacoes->materia_id = $responseConfirmacao->out->publicacaoConfirmacao->DadosMateriaResponse->reciboConfirmacao->IDMateria;
            $contratoPublicacoes->oficio_id = $responseConfirmacao->out->publicacaoConfirmacao->DadosMateriaResponse->reciboConfirmacao->IDOficio;
            $contratoPublicacoes->save();

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function montaOficioPreview(Contratohistorico $contratoHistorico)
    {

        $dados ['dados']['CPF'] = config('publicacao.usuario_publicacao');
        $dados ['dados']['UG'] = $contratoHistorico->unidade->codigo;
        $dados ['dados']['dataPublicacao'] = strtotime($contratoHistorico->data_publicacao);
        $dados ['dados']['empenho'] = '';
        $dados ['dados']['identificadorJornal'] = 3;
        $dados ['dados']['identificadorTipoPagamento'] = 149;
        $dados ['dados']['materia']['DadosMateriaRequest']['NUP'] = '';
        $dados ['dados']['materia']['DadosMateriaRequest']['conteudo'] = $this->retornaTextoRtf($contratoHistorico);
        $dados ['dados']['materia']['DadosMateriaRequest']['identificadorNorma'] = 134;
        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'] = config('publicacao.siorgmateria');
        $dados ['dados']['motivoIsencao'] = 9;
        $dados ['dados']['siorgCliente'] = $contratoHistorico->unidade->codigo_siorg;

        return $dados;
    }


    public function montaOficioConfirmacao(Contratohistorico $contratoHistorico)
    {
        $dados ['dados']['CPF'] = config('publicacao.usuario_publicacao');
        $dados ['dados']['IDTransacao'] = $contratoHistorico->unidade->nomeresumido . $this->generateRandonNumbers(13);
        $dados ['dados']['UG'] = $contratoHistorico->unidade->codigo;
        $dados ['dados']['dataPublicacao'] = strtotime($contratoHistorico->data_publicacao);
        $dados ['dados']['empenho'] = '';
        $dados ['dados']['identificadorJornal'] = 3;
        $dados ['dados']['identificadorTipoPagamento'] = 149;
        $dados ['dados']['materia']['DadosMateriaRequest']['NUP'] = '';
        $dados ['dados']['materia']['DadosMateriaRequest']['conteudo'] = $this->retornaTextoRtf($contratoHistorico);
        $dados ['dados']['materia']['DadosMateriaRequest']['identificadorNorma'] = 134;
        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'] = config('publicacao.siorgmateria');
        $dados ['dados']['motivoIsencao'] = 9;
        $dados ['dados']['siorgCliente'] = $contratoHistorico->unidade->codigo_siorg;


        return $dados;
    }


    public function retornaNumeroEmpenho(Contratohistorico $contratoHistorico)
    {
        $retorno = [
            'numero'=> '',
            'texto'=> ''
        ];
        $contrato = $contratoHistorico->contrato;
        (!($contrato->empenhos->isEmpty())) ? $empenhos = $contrato->empenhos : $empenhos = [];

        $cont = count($empenhos);

        foreach ($empenhos as $key => $value) {
            $empenho = Empenho::find($value->empenho_id);
            if ($cont < 2) {
                $retorno['numero'] = $empenho->numero;
                $retorno['texto'] = " Fonte: " . $empenho->fonte . " - " . $empenho->numero;
            }
            if ($key == 0 && $cont > 1) {
                $retorno['numero'] = $empenho->numero . " - ";
                $retorno['texto'] = " Fonte: " . $empenho->fonte . " - " . $empenho->numero;
            }
            if ($key > 0 && $key < ($cont - 1)) {
                $retorno['numero'] .= $empenho->numero . " - ";
                $retorno['texto'] .= " Fonte: " . $empenho->fonte . " - " . $empenho->numero;
            }
            if ($key == ($cont - 1)) {
                $retorno['numero'] .= $empenho->numero;
                $retorno['texto'] .= " Fonte: " . $empenho->fonte . " - " . $empenho->numero;
            }
        }
        return $retorno;
    }


    public function retornaCabecalhoRtf()
    {
        $textoCabecalho = "{\\rtf1\ansi\ansicpg1252\deff0\\nouicompat\deflang1046\deflangfe1046\deftab708{\\fonttbl{\\f0\\fnil\\fcharset0 Calibri;}}
                    {\colortbl ;\\red0\green0\blue255;}
                    {\*\generator Riched20 10.0.17763}{\*\mmathPr\mdispDef1\mwrapIndent1440 }\\viewkind4\uc1 \pard\widctlpar\\f0\\fs18 \par ";
        return $textoCabecalho;
    }


    public function converteTextoParaRtf(string $TextoModelo)
    {
        $rtf = new PHPRtfLite();
        $section = $rtf->addSection();
        $font = new PHPRtfLite_Font(9, 'Calibri');
        $section->writeText($TextoModelo, $font);
        $texto = $rtf->getContent();

        return $texto;
    }


    public function retornaTextoRtf(Contratohistorico $contratoHistorico)
    {

        $textoCabecalho = $this->retornaCabecalhoRtf();
        //todo buscar texto_dou para converter para RTF
        $textomodelo = self::retornaTextoModelo($contratoHistorico);
        $texto = $this->converteTextoParaRtf($textomodelo);
        $texto = $textoCabecalho . substr($texto, strripos($texto, '##ATO'));

        return $texto;
    }


    public static function retornaTextoModelo(Contratohistorico $contratoHistorico)
    {

        switch ($contratoHistorico->getTipo()) {
            case "Contrato":
                $textomodelo = self::retornaTextoModeloContrato($contratoHistorico);
                break;
            case "Termo Aditivo":
                $textomodelo = self::retornaTextoretificacao($contratoHistorico);
                break;
            case "Termo Apostilamento":
                $textomodelo = self::retornaTextoModeloApostilamento($contratoHistorico);
                break;
            case "Termo de Rescisão":
                $textomodelo = self::retornaTextoModeloRescisão($contratoHistorico);
                break;
        }
        return $textomodelo;
    }


    public static function retornaTextoModeloContrato(Contratohistorico $contratoHistorico)
    {
        $data = date('d/m/Y');

        $tipocontrato = self::retornaIdCodigoItem('Tipo de Contrato','Contrato');
        $tipomudanca = self::retornaIdCodigoItem('Tipo Publicacao','INCLUSAO');

        $padrao = Padroespublicacao::where('tipo_contrato_id',$tipocontrato)
            ->where('tipo_mudanca_id',$tipomudanca)->first();

        $padraoPublicacaoContrato = $padrao->texto_padrao;

        $contrato = $contratoHistorico->contrato;

        $padraoPublicacaoContrato = str_replace('|CONTRATOHISTORICO_NUMERO|', $contratoHistorico->numero, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|CONTRATOHISTORICO_GETUNIDADE|', $contratoHistorico->getUnidade(), $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|CONTRATO_PROCESSO|', $contrato->processo, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|CONTRATO_MODALIDADE_DESCRICAO|', $contrato->modalidade->descricao, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|CONTRATO_LICITACAO_NUMERO|', $contrato->licitacao_numero, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|CONTRATO_UNIDADE_NOME|', $contrato->unidade->nome, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|CONTRATOHISTORICO_FORNECEDOR_CPF_CNPJ_IDGENER|', $contratoHistorico->fornecedor->cpf_cnpj_idgener, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|CONTRATOHISTORICO_FORNECEDOR_NOME|', $contratoHistorico->fornecedor->nome, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|CONTRATOHISTORICO_OBJETO|', $contratoHistorico->objeto, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|CONTRATO_RETORNAAMPARO|', $contrato->retornaAmparo(), $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|CONTRATOHISTORICO_GETVIGENCIAINICIO|', $contratoHistorico->getVigenciaInicio(), $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|CONTRATOHISTORICO_GETVIGENCIAFIM|', $contratoHistorico->getVigenciaFim(), $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|CONTRATOHISTORICO_VALOR_GLOBAL|', $contratoHistorico->valor_global, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|CONTRATOHISTORICO_DATA_ASSINATURA|', self::retornaDataFormatada($contratoHistorico->data_assinatura), $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|DATA_ASSINATURA_SISTEMA|', $data, $padraoPublicacaoContrato);

        return $padraoPublicacaoContrato;
    }

    public static function retornaTextoModelorAditivo(Contratohistorico $contratoHistorico)
    {
        $data = date('d/m/Y');

        $tipocontrato = self::retornaIdCodigoItem('Tipo de Contrato','Termo Aditivo');
        $tipomudanca = self::retornaIdCodigoItem('Tipo Publicacao','INCLUSAO');

        $padrao = Padroespublicacao::where('tipo_contrato_id',$tipocontrato)->where('tipo_mudanca_id',$tipomudanca)->first();

        $padraoPublicacaoAditivo = $padrao->texto_padrao;

        $contrato = $contratoHistorico->contrato;

        $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_NUMERO|', $contratoHistorico->numero, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_GETUNIDADE|', $contratoHistorico->getUnidade(), $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|CONTRATO_NUMERO|', $contrato->numero, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|CONTRATO_PROCESSO|', $contrato->processo, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|CONTRATO_MODALIDADE_DESCRICAO|', $contrato->modalidade->descricao, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|CONTRATO_LICITACAO_NUMERO|', $contrato->licitacao_numero, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|CONTRATO_UNIDADE_NOME|', $contrato->unidade->nome, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_FORNECEDOR_CPF_CNPJ_IDGENER|', $contratoHistorico->fornecedor->cpf_cnpj_idgener, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_FORNECEDOR_NOME|', $contratoHistorico->fornecedor->nome, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_OBJETO|', $contratoHistorico->objeto, $padraoPublicacaoAditivo);
//        $padraoPublicacaoAditivo = str_replace('|contrato_retornaAmparo|', $contrato->retornaAmparo(), $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_GETVIGENCIAINICIO|', $contratoHistorico->getVigenciaInicio(), $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_GETVIGENCIAFIM|', $contratoHistorico->getVigenciaFim(), $padraoPublicacaoAditivo);
//        $padraoPublicacaoAditivo = str_replace('|numero_empenho|', $this->retornaNumeroEmpenho($contratoHistorico)['texto'], $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_VALOR_GLOBAL|', $contratoHistorico->valor_global, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_DATA_ASSINATURA|', self::retornaDataFormatada($contratoHistorico->data_assinatura), $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|DATA_ASSINATURA_SISTEMA|',$data, $padraoPublicacaoAditivo);

        return $padraoPublicacaoAditivo;
    }

    public static function retornaTextoModeloApostilamento(Contratohistorico $contratoHistorico)
    {
        $data = date('d/m/Y');

        $tipocontrato = self::retornaIdCodigoItem('Tipo de Contrato','Termo de Apostilamento');
        $tipomudanca = self::retornaIdCodigoItem('Tipo Publicacao','INCLUSAO');

        $padrao = Padroespublicacao::where('tipo_contrato_id',$tipocontrato)
            ->where('tipo_mudanca_id',$tipomudanca)->first();

        $padraoPublicacaoApostilamento = $padrao->texto_padrao;

        $padraoPublicacaoApostilamento = str_replace('|CONTRATOHISTORICO_OBJETO|', $contratoHistorico->objeto, $padraoPublicacaoApostilamento);
        $padraoPublicacaoApostilamento = str_replace('|DATA_ASSINATURA_SISTEMA|', $data, $padraoPublicacaoApostilamento);

        return $padraoPublicacaoApostilamento;
    }

    public static function retornaTextoModeloRescisao(Contratohistorico $contratoHistorico)
    {
        $data = date('d/m/Y');

        $tipocontrato = self::retornaIdCodigoItem('Tipo de Contrato','Termo de Rescisão');
        $tipomudanca = self::retornaIdCodigoItem('Tipo Publicacao','INCLUSAO');

        $padrao = Padroespublicacao::where('tipo_contrato_id',$tipocontrato)
            ->where('tipo_mudanca_id',$tipomudanca)->first();

        $padraoPublicacaoRecisao = $padrao->texto_padrao;

        $contrato = $contratoHistorico->contrato;

        $padraoPublicacaoRecisao = str_replace('|CONTRATOHISTORICO_NUMERO|', $contratoHistorico->numero, $padraoPublicacaoRecisao);
        $padraoPublicacaoRecisao = str_replace('|CONTRATO_PROCESSO|', $contrato->processo, $padraoPublicacaoRecisao);
        $padraoPublicacaoRecisao = str_replace('|CONTRATO_UNIDADE_NOME|', $contrato->unidade->nome, $padraoPublicacaoRecisao);
        $padraoPublicacaoRecisao = str_replace('|CONTRATOHISTORICO_FORNECEDOR_CPF_CNPJ_IDGENER|', $contratoHistorico->fornecedor->cpf_cnpj_idgener, $padraoPublicacaoRecisao);
        $padraoPublicacaoRecisao = str_replace('|CONTRATOHISTORICO_FORNECEDOR_NOME|', $contratoHistorico->fornecedor->nome, $padraoPublicacaoRecisao);
        $padraoPublicacaoRecisao = str_replace('|CONTRATOHISTORICO_OBJETO|', $contratoHistorico->objeto, $padraoPublicacaoRecisao);
        $padraoPublicacaoRecisao = str_replace('|CONTRATO_RETORNAAMPARO|', $contrato->retornaAmparo(), $padraoPublicacaoRecisao);
        $padraoPublicacaoRecisao = str_replace('|CONTRATOHISTORICO_DATA_ASSINATURA|', self::retornaDataFormatada($contratoHistorico->data_assinatura), $padraoPublicacaoRecisao);
        $padraoPublicacaoRecisao = str_replace('|DATA_ASSINATURA_SISTEMA|', $data,$padraoPublicacaoRecisao);

        return $padraoPublicacaoRecisao;
    }

    function generateRandonNumbers($length = 10)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function executaJobAtualizaSituacaoPublicacao()
    {
        $model = new ContratoPublicacoes;
        $publicacoes = $model->retornaPublicacoesEnviadas();

        foreach ($publicacoes as $publicacao) {
            if (isset($publicacao->id)) {
                AtualizaSituacaoPublicacaoJob::dispatch($publicacao)->onQueue('consulta_situacao_publicacao');
                //$this->testaAtualizacaoStatusPublicacao($publicacao);
            }
        }

    }


    public function testaAtualizacaoStatusPublicacao($publicacao)
    {
        $retorno = $this->consultaSituacaoOficio($publicacao->oficio_id);

        if ($retorno->out->validacaoIdOficio == "OK") {
            $status = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->estadoMateria;
            if ($status != "PUBLICADA") {
                $tipoSituacao = 'TRANSFERIDO PARA IMPRENSA';
                $this->atualizaPublicacao($publicacao, $retorno, $tipoSituacao);
            } else {
                $tipoSituacao = 'PUBLICADO';
                $this->atualizaPublicacao($publicacao, $retorno, $tipoSituacao);
            }
        }
        dd('fim');
    }


    public function atualizaPublicacao($publicacao, $retorno, $tipoSituacao)
    {
        $link = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->linkPublicacao;
        $pagina = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->paginaPublicacao;
        $motivo_devolucao = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->motivoDevolucao;
        $codigo = 'Situacao Publicacao';

        $publicacao->status_publicacao_id = $this->retornaIdCodigoItem($codigo,$tipoSituacao);
        $publicacao->status =  $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->estadoMateria;
        $publicacao->link_publicacao = $link;
        $publicacao->pagina_publicacao = $pagina;
        $publicacao->motivo_devolucao = $motivo_devolucao;
        $publicacao->secao_jornal = 3;

        $publicacao->save();

    }


    private static function retornaDataFormatada($data)
    {
        $date = new \DateTime($data);
        return date_format($date, 'd/m/Y');
    }


    public static function retornaIdCodigoItem($descCodigo, $descCodItem)
    {
        return Codigoitem::whereHas('codigo', function ($query) use ($descCodigo) {
            $query->where('descricao', '=', $descCodigo);
        })
            ->where('descricao', '=', $descCodItem)
            ->first()->id;
    }




    public static function retornaTextoretificacao(Contratohistorico $contratoHistorico)
    {
        $data = date('d/m/Y');
        dump($contratoHistorico);
        dd($contratoHistorico->getOriginal());
        $contrato = $contratoHistorico->contrato;
        $publicacao = $contratoHistorico->publicacao;
        $tipocontrato = Codigoitem::where('id',$contratoHistorico->tipo_id)->first();

        $textomodelo =
            "##ATO RETIFICAÇÃO
            ##TEX No Extrato de $tipocontrato->descricao Nº ".$contratoHistorico->numero.
            " publicado no D.O de ".self::retornaDataFormatada($contratoHistorico->data_publicacao).", ".
            "Seção 3, Pág.".$publicacao->pagina_publicacao.". Onde se lê:
            ##ASS COMPRASNET 4.0 - $data.";
        dd($textomodelo);

        return $textomodelo;
    }

}