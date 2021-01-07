<?php

namespace App\Http\Controllers\Publicacao;

use Alert;
use App\Http\Traits\BuscaCodigoItens;
use App\Http\Traits\DiarioOficial;
use App\Http\Traits\Formatador;
use App\Jobs\AtualizaSituacaoPublicacaoJob;
use App\Jobs\PublicaPreviewOficioJob;
use App\Models\Codigoitem;
use App\Models\Contratohistorico;
use App\Models\ContratoPublicacoes;
use App\Models\Empenho;
use App\Models\Fornecedor;
use App\Models\Padroespublicacao;
use App\Models\Unidade;
use Exception;
use Route;
use Illuminate\Support\Carbon;
use SoapHeader;
use SoapVar;
use PHPRtfLite;
use PHPRtfLite_Font;

class DiarioOficialClass extends BaseSoapController
{

    use BuscaCodigoItens;
    use Formatador;
    use DiarioOficial;

    private $soapClient;
    private $securityNS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    private $Urlwsdl;
    private $username;
    private $password;

    public function setSoapClient(){

        $this->Urlwsdl = config("publicacao.sistema.diario_oficial_uniao");
        $this->username = env('PUBLICACAO_DOU_USER');
        $this->password = env('PUBLICACAO_DOU_PWD');
        dump($this->Urlwsdl,$this->username,$this->password);

        self::setWsdl($this->Urlwsdl);
        $node1 = new SoapVar($this->username, XSD_STRING, null, null, 'Username', $this->securityNS);
        $node2 = new SoapVar($this->password, XSD_STRING, null, null, 'Password', $this->securityNS);
        $token = new SoapVar(array($node1, $node2), SOAP_ENC_OBJECT, null, null, 'UsernameToken', $this->securityNS);
        $security = new SoapVar(array($token), SOAP_ENC_OBJECT, null, null, 'Security', $this->securityNS);
        $headers[] = new SOAPHeader($this->securityNS, 'Security', $security, false);

        $this->soapClient = InstanceSoapClient::init($headers);
        dump($this->soapClient);

    }

    public function consultaTodosFeriado()
    {
        try {
            $this->setSoapClient();
            $response = $this->soapClient->ConsultaTodosOrgaosPermitidos();

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function consultaSituacaoOficio($oficio_id,$cpf)
    {
        try {
            $dados ['dados']['CPF'] = config('publicacao.usuario_publicacao');
//            $dados ['dados']['CPF'] = $cpf;
            $dados ['dados']['IDOficio'] = $oficio_id;
            $this->setSoapClient();
            return $this->soapClient->ConsultaAcompanhamentoOficio($dados);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function sustaMateriaPublicacao($publicacao_id,$cpf)
    {
        $publicacao = ContratoPublicacoes::where('id', $publicacao_id)->first();
        if(!is_null($publicacao->materia_id)) {
            try {
            $dados ['dados']['CPF'] = config('publicacao.usuario_publicacao');
//                $dados ['dados']['CPF'] = $cpf;
                $dados ['dados']['IDMateria'] = $publicacao->materia_id;
                $this->setSoapClient();
                return $this->soapClient->SustaMateria($dados);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }


    public function enviarPublicacaoCommand($contratohistorico,$publicacao)
    {
        dump('entrei na funcao');
        try {
            $this->setSoapClient();
            dump("Publicacao_id: ".$publicacao->id);
            dump("Contratohistorico_id: ".$publicacao->contratohistorico_id);

            $retificacao = $publicacao->texto_dou;
            $tipo_texto = strpos($publicacao->texto_dou, 'RETIFICA');
            ($tipo_texto == false)
                            ? $this->enviaPublicacao($contratohistorico, $publicacao,null,$publicacao->cpf)
                            : $this->enviaPublicacao($contratohistorico, $publicacao,$retificacao,$publicacao->cpf);

        } catch (Exception $e) {
            return $e->getMessage();
        }

    }


    public function reenviarPublicacao($publicacao_id)
    {
        try {
            $publicacao = ContratoPublicacoes::where('id', $publicacao_id)->first();
            $contratohistorico= $publicacao->contratohistorico;
            $this->setSoapClient();
            $this->enviaPublicacao($contratohistorico, $publicacao);

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function enviaPublicacao($contratoHistorico, $contratoPublicacoes,$retificacao = null,$cpf)
    {
        $data_publicacao = $contratoPublicacoes->data_publicacao;

        $arrayPreview = $this->montaOficioPreview($contratoHistorico,$data_publicacao,$retificacao,$cpf);

        $responsePreview = $this->soapClient->OficioPreview($arrayPreview);

        if (!isset($responsePreview->out->publicacaoPreview->DadosMateriaResponse->HASH)) {
            $contratoPublicacoes->status = 'Erro Preview!';
            $contratoPublicacoes->motivo_isencao_id =  $this->retornaIdCodigoItem('Motivo Isenção','Indefinido');
            $contratoPublicacoes->status_publicacao_id = (int)self::retornaIdCodigoItem('Situacao Publicacao','DEVOLVIDO PELA IMPRENSA');
            $contratoPublicacoes->log = $this->retornaErroValidacoesDOU($responsePreview);
            $contratoPublicacoes->texto_dou = (!is_null($retificacao)) ? $retificacao : $this->retornaTextoModelo($contratoHistorico);

            $contratoPublicacoes->save();

            return false;
        }

        $contratoPublicacoes->status = 'Preview';
        $contratoPublicacoes->motivo_isencao_id =  $this->retornaIdCodigoItem('Motivo Isenção','Indefinido');
        $contratoPublicacoes->texto_dou = (!is_null($retificacao)) ?  $retificacao : $this->retornaTextoModelo($contratoHistorico);
        $contratoPublicacoes->save();

        $this->oficioConfirmacao($contratoHistorico, $contratoPublicacoes,$data_publicacao,$retificacao,$cpf);

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

    public function oficioConfirmacao(Contratohistorico $contratoHistorico, ContratoPublicacoes $contratoPublicacoes,$data_publicacao,$retificacao,$cpf)
    {
        try {
            $arrayConfirmacao = $this->montaOficioConfirmacao($contratoHistorico,$data_publicacao,$retificacao,$cpf);

            $responseConfirmacao = $this->soapClient->OficioConfirmacao($arrayConfirmacao);

            if (!isset($responseConfirmacao->out->publicacaoConfirmacao->DadosMateriaResponse->reciboConfirmacao)) {
                $contratoPublicacoes->status = 'Erro Ofício!';

                $contratoPublicacoes->status_publicacao_id = (int)self::retornaIdCodigoItem('Situacao Publicacao','DEVOLVIDO PELA IMPRENSA');
                $contratoPublicacoes->motivo_isencao_id =  $this->retornaIdCodigoItem('Motivo Isenção','Indefinido');
                $contratoPublicacoes->log = json_encode($responseConfirmacao);
                $contratoPublicacoes->save();

                return false;
            }

            $contratoPublicacoes->status = 'Oficio';
            $contratoPublicacoes->motivo_isencao_id =  $this->retornaIdCodigoItem('Motivo Isenção','Indefinido');
            $contratoPublicacoes->status_publicacao_id = (int)self::retornaIdCodigoItem('Situacao Publicacao','TRANSFERIDO PARA IMPRENSA');
            $contratoPublicacoes->transacao_id = $arrayConfirmacao['dados']['IDTransacao'];
            $contratoPublicacoes->materia_id = (int)$responseConfirmacao->out->publicacaoConfirmacao->DadosMateriaResponse->reciboConfirmacao->IDMateria;
            $contratoPublicacoes->oficio_id = (int)$responseConfirmacao->out->publicacaoConfirmacao->DadosMateriaResponse->reciboConfirmacao->IDOficio;
            $contratoPublicacoes->save();

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    //TODO COLOCAR ZERO NA ISENÇÃO
    public function montaOficioPreview(Contratohistorico $contratoHistorico,$data_publicacao,$retificacao = null,$cpf)
    {
        $sisg = (isset($contratoHistorico->unidade->sisg)) ? $contratoHistorico->unidade->sisg : '';

//        $dados ['dados']['CPF'] = config('publicacao.usuario_publicacao'); //$cpf;
        $dados ['dados']['CPF'] = $cpf;
        $dados ['dados']['UG'] = $contratoHistorico->unidade->codigo;
        $dados ['dados']['dataPublicacao'] = strtotime($data_publicacao);
        $dados ['dados']['empenho'] = '';
        $dados ['dados']['identificadorJornal'] = 3;
        $dados ['dados']['identificadorTipoPagamento'] = 149;
        $dados ['dados']['materia']['DadosMateriaRequest']['NUP'] = '';
        $dados ['dados']['materia']['DadosMateriaRequest']['conteudo'] = (!is_null($retificacao)) ? $this->retornaRtfRetificacao($retificacao) : $this->retornaTextoRtf($contratoHistorico);
        $dados ['dados']['materia']['DadosMateriaRequest']['identificadorNorma'] = $this->retornaIdentificadorNorma($contratoHistorico,$retificacao);
        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'] = $contratoHistorico->unidade->codigo_siorg;
//        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'] = config('publicacao.siorgmateria');
        $dados ['dados']['motivoIsencao'] = 0;
        $dados ['dados']['siorgCliente'] = $contratoHistorico->unidade->codigo_siorg;

        return $dados;
    }


    public function montaOficioConfirmacao(Contratohistorico $contratoHistorico,$data_publicacao,$retificacao = null,$cpf)
    {
        $sisg = (isset($contratoHistorico->unidade->sisg)) ? $contratoHistorico->unidade->sisg : '';

//        $dados ['dados']['CPF'] = config('publicacao.usuario_publicacao'); //$cpf
        $dados ['dados']['CPF'] = $cpf;
        $dados ['dados']['IDTransacao'] = $contratoHistorico->unidade->nomeresumido . $this->generateRandonNumbers(13);
        $dados ['dados']['UG'] = $contratoHistorico->unidade->codigo;
        $dados ['dados']['dataPublicacao'] = strtotime($data_publicacao);
        $dados ['dados']['empenho'] = '';
        $dados ['dados']['identificadorJornal'] = 3;
        $dados ['dados']['identificadorTipoPagamento'] = 149;
        $dados ['dados']['materia']['DadosMateriaRequest']['NUP'] = '';
        $dados ['dados']['materia']['DadosMateriaRequest']['conteudo'] = (!is_null($retificacao)) ? $this->retornaRtfRetificacao($retificacao) : $this->retornaTextoRtf($contratoHistorico);
        $dados ['dados']['materia']['DadosMateriaRequest']['identificadorNorma'] = $this->retornaIdentificadorNorma($contratoHistorico,$retificacao);
        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'] = $contratoHistorico->unidade->codigo_siorg;
//        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'] = config('publicacao.siorgmateria');
        $dados ['dados']['motivoIsencao'] = 0;
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
        $textomodelo = self::retornaTextoModelo($contratoHistorico);
//        $textomodelo .= $textomodelo.'\n ##OFI COMPRASNET 4.0 - '.date('d-m-Y').'.';
        $textomodelo .= $textomodelo;
        $texto = $this->converteTextoParaRtf($textomodelo);
        $texto = $textoCabecalho . substr($texto, strripos($texto, '##ATO'));

        return $texto;
    }

    public function retornaRtfRetificacao($retificacao)
    {
//        $retificacao .= $retificacao.'\n ##OFI COMPRASNET 4.0 - '.date('d-m-Y').'.';
        $retificacao .= $retificacao;
        $textoCabecalho = $this->retornaCabecalhoRtf();
        $texto = $this->converteTextoParaRtf($retificacao);
        $texto = $textoCabecalho . substr($texto, strripos($texto, '##ATO'));

        return $texto;
    }


    public static function retornaTextoModelo(Contratohistorico $contratoHistorico)
    {
        $tipos_contrato = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '=', 'Termo Aditivo')
            ->orWhere('descricao', '=', 'Termo de Apostilamento')
            ->orWhere('descricao', '=', 'Empenho')
            ->orWhere('descricao', '=', 'Outros')
            ->pluck('id')
            ->toArray();

        switch ($contratoHistorico->getTipo()) {
            case "Contrato":
            case "Arrendamento":
            case "Credenciamento":
            case "Comodato":
            case "Concessão":
            case "Termo de Adesão":
            case "Convênio":
            case "Termo de Compromisso":
            case "Acordo de Cooperação Técnica (ACT)":
            case "Termo de Execução Descentralizada (TED)":
                $textomodelo = self::retornaTextoModeloContrato($contratoHistorico);
                break;
            case "Termo Aditivo":
                $textomodelo = self::retornaTextoModelorAditivo($contratoHistorico);
                break;
            case "Termo de Apostilamento":
                $textomodelo = self::retornaTextoModeloApostilamento($contratoHistorico);
                break;
            case "Termo de Rescisão":
                (!in_array($contratoHistorico->tipo_id,$tipos_contrato))
                    ?$textomodelo = self::retornaTextoModeloRescisao($contratoHistorico)
                    :'';
                break;
        }

        return $textomodelo;
    }


    public static function retornaTextoModeloContrato(Contratohistorico $contratoHistorico)
    {
        $data = date('d/m/Y');
        $desc_tipo_contrato = strtoupper($contratoHistorico->getTipo());
        $unidade = ($contratoHistorico->getUnidadeOrigem())?$contratoHistorico->getUnidadeOrigem():$contratoHistorico->getUnidade();
        $tipocontrato = self::retornaIdCodigoItem('Tipo de Contrato','Contrato');
        $tipomudanca = self::retornaIdCodigoItem('Tipo Publicacao','INCLUSAO');

        $padrao = Padroespublicacao::where('tipo_contrato_id',$tipocontrato)
            ->where('tipo_mudanca_id',$tipomudanca)->first();

        $padraoPublicacaoContrato = $padrao->texto_padrao;

        if(!is_null($padraoPublicacaoContrato)) {

            $contrato = $contratoHistorico->contrato;

            $padraoPublicacaoContrato = str_replace('|TIPO_CONTRATO|', $desc_tipo_contrato, $padraoPublicacaoContrato);
            $padraoPublicacaoContrato = str_replace('|CONTRATOHISTORICO_NUMERO|', $contratoHistorico->numero, $padraoPublicacaoContrato);
            $padraoPublicacaoContrato = str_replace('|CONTRATOHISTORICO_GETUNIDADE|', $unidade, $padraoPublicacaoContrato);
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
        return '';
    }



    public static function retornaTextoModelorAditivo(Contratohistorico $contratoHistorico)
    {

        $data = date('d/m/Y');
        $unidade = ($contratoHistorico->getUnidadeOrigem())?$contratoHistorico->getUnidadeOrigem():$contratoHistorico->getUnidade();

        $tipocontrato = self::retornaIdCodigoItem('Tipo de Contrato','Termo Aditivo');
        $tipomudanca = self::retornaIdCodigoItem('Tipo Publicacao','INCLUSAO');

        $padrao = Padroespublicacao::where('tipo_contrato_id',$tipocontrato)->where('tipo_mudanca_id',$tipomudanca)->first();

        $padraoPublicacaoAditivo = $padrao->texto_padrao;

        if(!is_null($padraoPublicacaoAditivo)) {

            $contrato = $contratoHistorico->contrato;

            $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_NUMERO|', $contratoHistorico->numero, $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_GETUNIDADE|', $unidade, $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|CONTRATO_NUMERO|', $contrato->numero, $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|CONTRATO_PROCESSO|', $contrato->processo, $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|CONTRATO_MODALIDADE_DESCRICAO|', $contrato->modalidade->descricao, $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|CONTRATO_LICITACAO_NUMERO|', $contrato->licitacao_numero, $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|CONTRATO_UNIDADE_NOME|', $contrato->unidade->nome, $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_FORNECEDOR_CPF_CNPJ_IDGENER|', $contratoHistorico->fornecedor->cpf_cnpj_idgener, $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_FORNECEDOR_NOME|', $contratoHistorico->fornecedor->nome, $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_OBJETO|', $contratoHistorico->observacao, $padraoPublicacaoAditivo);
//        $padraoPublicacaoAditivo = str_replace('|contrato_retornaAmparo|', $contrato->retornaAmparo(), $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_GETVIGENCIAINICIO|', $contratoHistorico->getVigenciaInicio(), $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_GETVIGENCIAFIM|', $contratoHistorico->getVigenciaFim(), $padraoPublicacaoAditivo);
//        $padraoPublicacaoAditivo = str_replace('|numero_empenho|', $this->retornaNumeroEmpenho($contratoHistorico)['texto'], $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_VALOR_GLOBAL|', $contratoHistorico->valor_global, $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|CONTRATOHISTORICO_DATA_ASSINATURA|', self::retornaDataFormatada($contratoHistorico->data_assinatura), $padraoPublicacaoAditivo);
            $padraoPublicacaoAditivo = str_replace('|DATA_ASSINATURA_SISTEMA|', $data, $padraoPublicacaoAditivo);

            return $padraoPublicacaoAditivo;
        }
        return '';
    }

    public static function retornaTextoModeloApostilamento(Contratohistorico $contratoHistorico)
    {
        $data = date('d/m/Y');

        $tipocontrato = self::retornaIdCodigoItem('Tipo de Contrato','Termo de Apostilamento');
        $tipomudanca = self::retornaIdCodigoItem('Tipo Publicacao','INCLUSAO');

        $padrao = Padroespublicacao::where('tipo_contrato_id',$tipocontrato)
            ->where('tipo_mudanca_id',$tipomudanca)->first();

        $padraoPublicacaoApostilamento = $padrao->texto_padrao;

        if(!is_null($padraoPublicacaoApostilamento)) {
            $padraoPublicacaoApostilamento = str_replace('|CONTRATOHISTORICO_OBJETO|', $contratoHistorico->observacao, $padraoPublicacaoApostilamento);
            $padraoPublicacaoApostilamento = str_replace('|DATA_ASSINATURA_SISTEMA|', $data, $padraoPublicacaoApostilamento);

            return $padraoPublicacaoApostilamento;
        }
        return '';
    }

    public static function retornaTextoModeloRescisao(Contratohistorico $contratoHistorico)
    {
        $data = date('d/m/Y');

        $tipocontrato = self::retornaIdCodigoItem('Tipo de Contrato','Termo de Rescisão');
        $tipomudanca = self::retornaIdCodigoItem('Tipo Publicacao','INCLUSAO');

        $padraoPublicacaoRecisao = Padroespublicacao::where('tipo_contrato_id',$tipocontrato)
            ->where('tipo_mudanca_id',$tipomudanca)->first()->texto_padrao;

        if(!is_null($padraoPublicacaoRecisao)) {

            $contrato = $contratoHistorico->contrato;

            $padraoPublicacaoRecisao = str_replace('|CONTRATOHISTORICO_NUMERO|', $contratoHistorico->numero, $padraoPublicacaoRecisao);
            $padraoPublicacaoRecisao = str_replace('|CONTRATO_PROCESSO|', $contrato->processo, $padraoPublicacaoRecisao);
            $padraoPublicacaoRecisao = str_replace('|CONTRATO_UNIDADE_NOME|', $contrato->unidade->nome, $padraoPublicacaoRecisao);
            $padraoPublicacaoRecisao = str_replace('|CONTRATOHISTORICO_FORNECEDOR_CPF_CNPJ_IDGENER|', $contratoHistorico->fornecedor->cpf_cnpj_idgener, $padraoPublicacaoRecisao);
            $padraoPublicacaoRecisao = str_replace('|CONTRATOHISTORICO_FORNECEDOR_NOME|', $contratoHistorico->fornecedor->nome, $padraoPublicacaoRecisao);
            $padraoPublicacaoRecisao = str_replace('|CONTRATOHISTORICO_OBJETO|', $contratoHistorico->observacao, $padraoPublicacaoRecisao);
            $padraoPublicacaoRecisao = str_replace('|CONTRATO_RETORNAAMPARO|', $contrato->retornaAmparo(), $padraoPublicacaoRecisao);
            $padraoPublicacaoRecisao = str_replace('|CONTRATOHISTORICO_DATA_PUBLICACAO|', self::retornaDataFormatada($contratoHistorico->data_publicacao), $padraoPublicacaoRecisao);
            $padraoPublicacaoRecisao = str_replace('|DATA_ASSINATURA_SISTEMA|', $data, $padraoPublicacaoRecisao);

            return $padraoPublicacaoRecisao;
        }
        return '';
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
            }
        }

    }


    public function atualizaStatusPublicacao($publicacao_id,$cpf)
    {
        $publicacao = ContratoPublicacoes::where('id', $publicacao_id)->first();
        if(!is_null($publicacao->oficio_id)) {
            $retorno = $this->consultaSituacaoOficio($publicacao->oficio_id, $cpf);
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
        }
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


    private function retornaCodigoItem($descCodigo, $descCodItem)
    {
        return Codigoitem::whereHas('codigo', function ($query) use ($descCodigo) {
            $query->where('descricao', '=', $descCodigo);
        })
            ->where('descricao', '=', $descCodItem)
            ->first();
    }

    private static function retornaIdCodigoItem($descCodigo, $descCodItem)
    {
        return Codigoitem::whereHas('codigo', function ($query) use ($descCodigo) {
            $query->where('descricao', '=', $descCodigo);
        })
            ->where('descricao', '=', $descCodItem)
            ->first()->id;
    }

    private static function retornaDescresMotivoIsencao($descCodigo, $descCodItem)
    {
        return Codigoitem::whereHas('codigo', function ($query) use ($descCodigo) {
            $query->where('descricao', '=', $descCodigo);
        })
            ->where('descricao', '=', $descCodItem)
            ->first()->descres;
    }

    public static function retornaCampoFormatadoComoNumero($campo, $prefix = false)
    {
        try {
            $numero = number_format($campo, 2, ',', '.');
            $numeroPrefixado = ($prefix === true ? 'R$ ' : '') . $numero;
            $retorno = ($campo < 0) ? "($numeroPrefixado)" : $numeroPrefixado;
        } catch (\Exception $e) {
            $retorno = '';
        }

        return $retorno;
    }


    public static function retornaTextoretificacao(Contratohistorico $contratoHistorico)
    {
        $data = date('d/m/Y');

        $arrayCotratos = self::retornaContratosPermitidos();
        (in_array($contratoHistorico->tipo_id,$arrayCotratos))
            ? $tipo_descricao = 'Contrato'
            : $tipo_descricao = $contratoHistorico->getTipo();

        $tipocontrato = self::retornaIdCodigoItem('Tipo de Contrato',$tipo_descricao);

        $tipomudanca = self::retornaIdCodigoItem('Tipo Publicacao','ALTERACAO / RETIFICACAO');

        $retificacoes = self::retornaAlteracoes($contratoHistorico);

        if($retificacoes != '') {
            $publicacao = $contratoHistorico->publicacao;
            $pagina = ((isset($publicacao->pagina_publicacao)) ? ", Pág." . $publicacao->pagina_publicacao : '');
            $desc_tipo_contrato = strtoupper($contratoHistorico->getTipo());

            $padraoPublicacaoRetificacao = Padroespublicacao::where('tipo_contrato_id', $tipocontrato)
                ->where('tipo_mudanca_id', $tipomudanca)->first()->texto_padrao;

            if (!is_null($padraoPublicacaoRetificacao)) {
                $padraoPublicacaoRetificacao = str_replace('|TIPO_CONTRATO|', $desc_tipo_contrato, $padraoPublicacaoRetificacao);
                $padraoPublicacaoRetificacao = str_replace('|CONTRATOHISTORICO_NUMERO|', $contratoHistorico->numero, $padraoPublicacaoRetificacao);
                $padraoPublicacaoRetificacao = str_replace('|CONTRATOHISTORICO_DATA_PUBLICACAO|', $contratoHistorico->data_publicacao, $padraoPublicacaoRetificacao);
                $padraoPublicacaoRetificacao = str_replace('|PAGINA|', $pagina, $padraoPublicacaoRetificacao);
                $padraoPublicacaoRetificacao = str_replace('|RETIFICACAO|', $retificacoes, $padraoPublicacaoRetificacao);
                $padraoPublicacaoRetificacao = str_replace('|DATA_ASSINATURA_SISTEMA|', $data, $padraoPublicacaoRetificacao);

                return $padraoPublicacaoRetificacao;
            }
        }
        return null;
    }

    private static function verificaRetificacaoValor($le,$leia,$original,$mudancas){
        $retificacaoValor = '';
        if (isset($mudancas['valor_global'])){
            $le .= 'Valor Total: ';
            $leia .= 'Valor Total: ';
            if($mudancas['valor_global'] != $original['valor_global']){
                $retificacaoValor = $le.self::retornaCampoFormatadoComoNumero($original['valor_global'],true)
                    .$leia.self::retornaCampoFormatadoComoNumero($mudancas['valor_global'],true).'. ';
            }
        }
        return $retificacaoValor;
    }

    private static function verificaRetificacaoVigencia($le,$leia,$original,$mudancas){
        $retificacaoVigencia = '';
        if ((isset($mudancas['vigencia_inicio']) ||(isset($mudancas['vigencia_fim'])))){
            $le .= 'Vigência: ';
            $leia .= 'Vigência: ';
            if(($mudancas['vigencia_inicio'] != $original['vigencia_inicio'])
                || ($mudancas['vigencia_fim'] != $original['vigencia_fim'])){
                $retificacaoVigencia = $le.self::retornaDataFormatada($original['vigencia_inicio'])
                                            ." a "
                                            .self::retornaDataFormatada($original['vigencia_fim']).". "
                                      .$leia.self::retornaDataFormatada($mudancas['vigencia_inicio'])
                                            ." a "
                                            .self::retornaDataFormatada($mudancas['vigencia_fim']).". ";
            }
        }
        return $retificacaoVigencia;
    }


    private static function verificaRetificacaoFornecedor($le,$leia,$original,$mudancas){
        $retificacaoFornecedor = '';

        if (isset($mudancas['fornecedor_id'])){
            $le .= 'Contratada: ';
            $leia .= 'Contratada: ';
            if($mudancas['fornecedor_id'] != $original['fornecedor_id']){
                $retificacaoFornecedor = $le.self::retornaFornecedorById($original['fornecedor_id']).'. '
                                        .$leia.self::retornaFornecedorById($mudancas['fornecedor_id']).'. ';
            }
        }

        return $retificacaoFornecedor;
    }

    private static function verificaRetificacaoObservacao($le,$leia,$original,$mudancas){
        $retificacaoObservacao = '';

        if (isset($mudancas['observacao'])){
            if($mudancas['observacao'] != $original['observacao']){
                $retificacaoObservacao = $le.$original['observacao'].'. '
                    .$leia.$mudancas['observacao'].'. ';
            }
        }
        return $retificacaoObservacao;
    }

    private static function verificaRetificacaoObjeto($le,$leia,$original,$mudancas){
        $retificacaoObjeto = '';

        if (isset($mudancas['objeto'])){
            if($mudancas['objeto'] != $original['objeto']){
                $retificacaoObjeto = $le.$original['objeto'].'. '
                    .$leia.$mudancas['objeto'].'. ';
            }
        }
        return $retificacaoObjeto;
    }

    private static function verificaRetificacaoProcesso($le,$leia,$original,$mudancas){
        $retificacaoProcesso = '';

        if (isset($mudancas['processo'])){
            $le .= 'N° PROCESSO: ';
            $leia .= 'N° PROCESSO: ';
            if($mudancas['processo'] != $original['processo']){
                $retificacaoProcesso = $le.$original['processo'].'. '
                    .$leia.$mudancas['processo'].'. ';
            }
        }
        return $retificacaoProcesso;
    }
    private static function verificaRetificacaoNumero($le,$leia,$original,$mudancas,$tipocontrato){

        $retificacaoNumero = '';

        if (isset($mudancas['numero'])){
            $le .= 'EXTRATO DE '.$tipocontrato.': ';
            $leia .= 'EXTRATO DE '.$tipocontrato.': ';
            if($mudancas['numero'] != $original['numero']){
                $retificacaoNumero = $le.$original['numero'].'. '
                    .$leia.$mudancas['numero'].'. ';
            }
        }
        return $retificacaoNumero;
    }

    private static function verificaRetificacaoUnidadeOrigem($le,$leia,$original,$mudancas){
        $retificacaoUnidadeOrigem = '';

        if (isset($mudancas['unidadeorigem_id'])){
            $le .= 'UASG: ';
            $leia .= 'UASG: ';
            if($mudancas['unidadeorigem_id'] != $original['unidadeorigem_id']){
                $uasgOriginal = Unidade::where('id',$original['unidadeorigem_id'])->first();
                $novaUasg = Unidade::where('id',$mudancas['unidadeorigem_id'])->first();
                $retificacaoUnidadeOrigem = $le.$uasgOriginal->codigo.' - '.$uasgOriginal->nomeresumido.'. '
                    .$leia.$novaUasg->codigo.' - '.$novaUasg->nomeresumido.'. ';
            }
        }
        return $retificacaoUnidadeOrigem;
    }


    private static function verificaRetificacaoDtAssinatura($le,$leia,$original,$mudancas){
        $retificacaoFornecedor = '';

        if (isset($mudancas['data_assinatura'])){
            $le .= 'Assinatura: ';
            $leia .= 'Assinatura: ';
            if($mudancas['data_assinatura'] != $original['data_assinatura']){
                $retificacaoFornecedor = $le.self::retornaDataFormatada($original['data_assinatura']).'. '
                    .$leia.self::retornaDataFormatada($mudancas['data_assinatura']).'. ';
            }
        }
        return $retificacaoFornecedor;
    }

    private static function retornaAlteracoes($contratoHistorico)
    {
        $arrayContratos = self::retornaContratosPermitidos();

        $tipocontrato = $contratoHistorico->getTipo();

        $retificacoes = '';
        $le = 'Onde se lê: ';
        $leia = '. Leia-se: ';
        $original = $contratoHistorico->getOriginal();
        $mudancas = $contratoHistorico->getChanges();

        $retificacoes .= self::verificaRetificacaoValor($le,$leia,$original,$mudancas);
        $retificacoes .= self::verificaRetificacaoVigencia($le,$leia,$original,$mudancas);
        $retificacoes .= self::verificaRetificacaoFornecedor($le,$leia,$original,$mudancas);
        $retificacoes .= self::verificaRetificacaoDtAssinatura($le,$leia,$original,$mudancas);
        $retificacoes .= self::verificaRetificacaoNumero($le,$leia,$original,$mudancas,$tipocontrato);

        if(in_array($contratoHistorico->tipo_id,$arrayContratos)){
            $retificacoes .= self::verificaRetificacaoObjeto($le,$leia,$original,$mudancas);
            $retificacoes .= self::verificaRetificacaoProcesso($le,$leia,$original,$mudancas);
            $retificacoes .= self::verificaRetificacaoUnidadeOrigem($le,$leia,$original,$mudancas);
        }else{
            $retificacoes .= self::verificaRetificacaoObservacao($le,$leia,$original,$mudancas);
        }

        return $retificacoes;
    }

    private static function retornaFornecedorById($fornecedor_id)
    {
        $fornecedor = Fornecedor::where('id',$fornecedor_id)->first();
        return $fornecedor->nome.' - '.$fornecedor->cpf_cnpj_idgener;
    }

    private static function retornaContratosPermitidos()
    {
        return Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '<>', 'Termo Aditivo')
            ->Where('descricao', '<>', 'Termo de Apostilamento')
            ->Where('descricao', '<>', 'Termo de Rescisão')
            ->Where('descricao', '<>', 'Empenho')
            ->Where('descricao', '<>', 'Outros')
            ->pluck('id')
            ->toArray();
    }

    public function retornaIdentificadorNorma(Contratohistorico $contratoHistorico, $retificacao = null){

        $codigo = 'Tipo Norma Publicação';
        $tipo_intrumento = $this->retornaDescCodigoItem($contratoHistorico->tipo_id);
        $norma_id = 0;

        if(!is_null($retificacao)){
            return  (int)$this->retornaDescresCodigoItem($codigo,'Retificação');
        }

        switch ($tipo_intrumento) {
            case "Contrato":
                $norma_id = $this->retornaDescresCodigoItem($codigo,'Extrato de Contrato');
                break;
            case "Arrendamento":
                $norma_id = $this->retornaDescresCodigoItem($codigo,'Extrato de Contrato');
                break;
            case "Credenciamento":
                $norma_id = $this->retornaDescresCodigoItem($codigo,'Extrato de Credenciamento');
                break;
            case "Comodato":
                $norma_id = $this->retornaDescresCodigoItem($codigo,'Extrato de Comodato');
                break;
            case "Concessão":
                $norma_id = $this->retornaDescresCodigoItem($codigo,'Extrato de Concessão de Uso');
                break;
            case "Termo de Adesão":
                $norma_id = $this->retornaDescresCodigoItem($codigo,'Extrato de Adesão');
                break;
            case "Convênio":
                $norma_id = $this->retornaDescresCodigoItem($codigo,'Extrato de Convênio');
                break;
            case "Termo de Compromisso":
                $norma_id = $this->retornaDescresCodigoItem($codigo,'Extrato de Compromisso');
                break;
            case "Acordo de Cooperação Técnica (ACT)":
                $norma_id = $this->retornaDescresCodigoItem($codigo,'Extrato de Acordo de Cooperação Técnica');
                break;
            case "Termo de Execução Descentralizada (TED)":
                $norma_id = $this->retornaDescresCodigoItem($codigo,'Extrato de Termo de Execução Descentralizada');
                break;
            case "Termo Aditivo":
                $norma_id = $this->retornaDescresCodigoItem($codigo,'Extrato de Termo Aditivo');
                break;
            case "Termo Apostilamento":
                $norma_id = $this->retornaDescresCodigoItem($codigo,'Extrato de Apostilamento');
                break;
            case "Termo de Rescisão":
                $norma_id = $this->retornaDescresCodigoItem($codigo,'Extrato de Rescisão');
                break;
        }

        return (int)$norma_id;
    }




}
