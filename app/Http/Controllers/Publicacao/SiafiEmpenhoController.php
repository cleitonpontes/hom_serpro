<?php

namespace App\Http\Controllers\Publicacao;

use App\Models\Contratohistorico;
use App\Models\ContratoPublicacoes;
use App\Models\Empenho;
use SoapHeader;
use SoapVar;
use PHPRtfLite;
use PHPRtfLite_Font;


class SiafiEmpenhoController extends BaseSoapController
{
    private $soapClient;
    private $securityNS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    private $Urlwsdl = 'https://servicos-siafi.tesouro.gov.br/siafi2020he/services/tabelas/consultarTabelasAdministrativas?wsdl';
    private $username = 'E0350';
    private $password = 'CC112020';


    public function __construct()
    {
        self::setWsdl($this->Urlwsdl);
        $node1 = new SoapVar($this->username, XSD_STRING, null, null, 'Username', $this->securityNS);
        $node2 = new SoapVar($this->password, XSD_STRING, null, null, 'Password', $this->securityNS);
        $token = new SoapVar(array($node1,$node2), SOAP_ENC_OBJECT, null, null, 'UsernameToken', $this->securityNS);
        $security = new SoapVar(array($token), SOAP_ENC_OBJECT, null, null, 'Security', $this->securityNS);
        $headers[] = new SOAPHeader($this->securityNS, 'Security', $security, false);

        $this->soapClient = InstanceSoapClient::init($headers);
    }

    public function consultaTodosServicos(){
        try {

            $response = $this->soapClient->__getTypes();

            dd($response);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }


    public function oficioPreview($contrato_id){
        try {

            $contratoHistorico = Contratohistorico::where('contrato_id',$contrato_id)
                                                    ->orderBy('id','desc')
                                                    ->first();

            $contratoPublicacoes = ContratoPublicacoes::where('contratohistorico_id',$contratoHistorico->id)
                                                        ->orderBy('id','desc')
                                                        ->first();

            $arrayPreview = $this->montaOficioPreview($contratoHistorico);
            $responsePreview = $this->soapClient->OficioPreview($arrayPreview);

            if(!isset($responsePreview->out->publicacaoPreview->DadosMateriaResponse->HASH)){
                $contratoPublicacoes->staus = 'Erro Preview!';
                $contratoPublicacoes->situacao = 'Preview n??o enviado!';
                $contratoPublicacoes->log = json_encode($responsePreview);
                $contratoPublicacoes->save();
                \Alert::warning('Houve um erro ao enviar o Preview - Verifique o Log !')->flash();
                return redirect()->back();
            }

            $contratoPublicacoes->status = 'Preview';
            $contratoPublicacoes->situacao = 'Enviado';
            $contratoPublicacoes->texto_dou = $this->retornaTextoModelo($contratoHistorico);
            $contratoPublicacoes->save();
            $this->oficioConfirmacao($contratoHistorico,$contratoPublicacoes);

        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }


    public function oficioConfirmacao(Contratohistorico  $contratoHistorico,ContratoPublicacoes $contratoPublicacoes){
        try {

            $arrayConfirmacao = $this->montaOficioConfirmacao($contratoHistorico);

            $responseConfirmacao = $this->soapClient->OficioConfirmacao($arrayConfirmacao);
            if(!isset($responseConfirmacao->out->publicacaoConfirmacao->DadosMateriaResponse->reciboConfirmacao)){
                $contratoPublicacoes->staus = 'Erro Of??cio!';
                $contratoPublicacoes->situacao = 'Oficio n??o confirmado!';
                $contratoPublicacoes->log = json_encode($responseConfirmacao);
                $contratoPublicacoes->save();
                \Alert::warning('Houve um erro ao confirmar o Of??cio - Verifique o Log !')->flash();
                return redirect()->back();
            }
            $contratoPublicacoes->status = 'Oficio';
            $contratoPublicacoes->situacao = 'Confirmado';
            $contratoPublicacoes->transacao_id = $arrayConfirmacao['dados']['IDTransacao'];
            $contratoPublicacoes->materia_id = $responseConfirmacao->out->publicacaoConfirmacao->DadosMateriaResponse->reciboConfirmacao->IDMateria;
            $contratoPublicacoes->oficio_id = $responseConfirmacao->out->publicacaoConfirmacao->DadosMateriaResponse->reciboConfirmacao->IDOficio;
            $contratoPublicacoes->save();

            \Alert::success('Enviado com sucesso - Aguarde Atualizacao !')->flash();
            return redirect()->route('listar.historico',['contrato_id' => $contratoHistorico->contrato_id]);

        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }


    public function montaOficioPreview(Contratohistorico $contratoHistorico)
    {
        $dados ['dados']['CPF'] = '01895591111';
        $dados ['dados']['UG'] = $contratoHistorico->unidade->codigo;
        $dados ['dados']['dataPublicacao'] = strtotime($contratoHistorico->data_publicacao);
        $dados ['dados']['empenho'] = $this->retornaNumeroEmpenho($contratoHistorico)['numero'];
        $dados ['dados']['identificadorJornal'] = 3;
        $dados ['dados']['identificadorTipoPagamento'] = 149;
        $dados ['dados']['materia']['DadosMateriaRequest']['NUP'] = '';
        $dados ['dados']['materia']['DadosMateriaRequest']['conteudo'] = $this->retornaTextoRtf($contratoHistorico);
        $dados ['dados']['materia']['DadosMateriaRequest']['identificadorNorma'] = 134;
        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'] = $contratoHistorico->unidade->codigo_siorg;
        $dados ['dados']['motivoIsencao'] = 9;
        $dados ['dados']['siorgCliente'] = $contratoHistorico->unidade->codigo_siorg;

        return $dados;
    }


    public function montaOficioConfirmacao(Contratohistorico $contratoHistorico)
    {
        $dados ['dados']['CPF'] = '01895591111';
        $dados ['dados']['IDTransacao'] = $contratoHistorico->unidade->nomeresumido.$this->generateRandonNumbers(13);
        $dados ['dados']['UG'] = $contratoHistorico->unidade->codigo;
        $dados ['dados']['dataPublicacao'] = strtotime($contratoHistorico->data_publicacao);
        $dados ['dados']['empenho'] = $this->retornaNumeroEmpenho($contratoHistorico)['numero'];
        $dados ['dados']['identificadorJornal'] = 3;
        $dados ['dados']['identificadorTipoPagamento'] = 149;
        $dados ['dados']['materia']['DadosMateriaRequest']['NUP'] = '';
        $dados ['dados']['materia']['DadosMateriaRequest']['conteudo'] = $this->retornaTextoRtf($contratoHistorico);
        $dados ['dados']['materia']['DadosMateriaRequest']['identificadorNorma'] = 134;
        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'] = $contratoHistorico->unidade->codigo_siorg;
        $dados ['dados']['motivoIsencao'] = 9;
        $dados ['dados']['siorgCliente'] = $contratoHistorico->unidade->codigo_siorg;


        return $dados;
    }


    public function retornaNumeroEmpenho(Contratohistorico $contratoHistorico)
    {
        $retorno = [];
        $contrato = $contratoHistorico->contrato;
        (!($contrato->empenhos->isEmpty())) ? $empenhos = $contrato->empenhos : $empenho = '';

        $cont = count($empenhos);

        foreach($empenhos  as $key => $value){
                $empenho = Empenho::find($value->empenho_id);
                if($cont < 2){
                    $retorno['numero'] = $empenho->numero;
                    $retorno['texto'] = " Fonte: ".$empenho->fonte." - ".$empenho->numero;
                }
                if($key == 0 && $cont > 1){
                    $retorno['numero'] = $empenho->numero." - ";
                    $retorno['texto'] = " Fonte: ".$empenho->fonte." - ".$empenho->numero;
                }
                if($key > 0 && $key < ($cont - 1)){
                    $retorno['numero'] .= $empenho->numero." - ";
                    $retorno['texto'] .= " Fonte: ".$empenho->fonte." - ".$empenho->numero;
                }
                if($key == ($cont - 1)){
                    $retorno['numero'] .= $empenho->numero;
                    $retorno['texto'] .= " Fonte: ".$empenho->fonte." - ".$empenho->numero;
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
        $section =  $rtf->addSection();
        $font = new PHPRtfLite_Font(9,'Calibri');
        $section->writeText($TextoModelo, $font);
        $texto = $rtf->getContent();

        return $texto;
    }


    public function retornaTextoRtf(Contratohistorico $contratoHistorico)
    {

        $textoCabecalho = $this->retornaCabecalhoRtf();

        $textomodelo = $this->retornaTextoModelo($contratoHistorico);
        $texto = $this->converteTextoParaRtf($textomodelo);
        $texto = $textoCabecalho.substr($texto,strripos($texto, '##ATO'));

        return $texto;
    }


    public function retornaTextoModelo(Contratohistorico $contratoHistorico)
    {
        switch ($contratoHistorico->getTipo()){
            case "Contrato":
                $textomodelo = $this->retornaTextoModeloContrato($contratoHistorico);
                break;
            case "Termo Aditivo":
                $textomodelo = $this->retornaTextoModelorAditivo($contratoHistorico);
                break;
        }
        return $textomodelo;
    }


    public function retornaTextoModeloContrato(Contratohistorico $contratoHistorico)
    {
        $contrato = $contratoHistorico->contrato;
        $TextoModelo = "##ATO EXTRATO DE CONTRATO N?? ".$contratoHistorico->numero." - UASG ".$contratoHistorico->getUnidade()."
        N?? Processo: ".$contrato->processo.".
        ##TEX ".strtoupper($contrato->modalidade->descricao)." SRP N?? ".$contrato->licitacao_numero.". Contratante: ".$contrato->unidade->nome.".
        CNPJ Contratado: ".$contratoHistorico->fornecedor->cpf_cnpj_idgener.". Contratado : ".$contratoHistorico->fornecedor->nome." -.
        Objeto: ".$contratoHistorico->objeto.".
        Fundamento Legal: ".$contrato->retornaAmparo()." . Vig??ncia: ".$contratoHistorico->getVigenciaInicio()." a ".$contratoHistorico->getVigenciaFim() .
            ". Valor Total: R$".$contratoHistorico->getValorGlobal().".".$this->retornaNumeroEmpenho($contratoHistorico)['texto'].". Data de Assinatura: ".$contratoHistorico->data_assinatura.".";

        return $TextoModelo;
    }


    public function retornaTextoModelorAditivo(Contratohistorico $contratoHistorico)
    {
        $contrato = $contratoHistorico->contrato;
        $textomodelo = "##ATO EXTRATO DE TERMO ADITIVO N?? ".$contratoHistorico->numero." - UASG ".$contratoHistorico->getUnidade()." N??mero do Contrato: ".$contrato->numero.". N?? Processo: ".$contrato->processo.".
                        ##TEX ".strtoupper($contrato->modalidade->descricao)." N?? ".$contrato->licitacao_numero.". Contratante: ".$contrato->unidade->nome.". CNPJ Contratado: ".$contratoHistorico->fornecedor->cpf_cnpj_idgener.". Contratado : ".$contratoHistorico->fornecedor->nome." -.Objeto: ".$contratoHistorico->objeto." Fundamento Legal: ".$contrato->retornaAmparo().". Vig??ncia: ".$contratoHistorico->getVigenciaInicio()." a ".$contratoHistorico->getVigenciaFim().". ".$this->retornaNumeroEmpenho($contratoHistorico)['texto'].". Data de Assinatura: 01/04/2020.";
        return $textomodelo;
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

}

