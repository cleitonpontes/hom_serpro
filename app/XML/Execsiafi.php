<?php

namespace App\XML;

use App\Models\BackpackUser;
use App\Models\Sfcentrocusto;
use App\Models\Sfcertificado;
use App\Models\SfDadosBasicos;
use App\Models\SfDocOrigem;
use App\Models\SfNonce;
use App\Models\SfPadrao;
use App\Models\SfPco;
use App\Models\SfPcoItem;
use App\Models\Sfrelitemvlrcc;

class Execsiafi
{
    public $resultado = [];

    protected function conexao_xml($user, $pass, $ug, $sf_id, $amb, $exercicio, $wsdl)
    {

        if ($amb == 'PROD') {
            //ambiente produção
            if ($wsdl == 'CONSULTA') {
                $wsdl = 'https://servicos-siafi.tesouro.gov.br/siafi' . $exercicio . '/services/tabelas/consultarTabelasAdministrativas?wsdl';
            }
            if ($wsdl == 'CPR') {
                $wsdl = 'https://servicos-siafi.tesouro.gov.br/siafi' . $exercicio . '/services/cpr/manterContasPagarReceber?wsdl';
            }
        }

        if ($amb == 'HOM') {
            //ambiente homologação
            if ($wsdl == 'CONSULTA') {
                $wsdl = 'https://homextservicos-siafi.tesouro.gov.br/siafi' . $exercicio . 'he/services/tabelas/consultarTabelasAdministrativas?wsdl';
            }
            if ($wsdl == 'CPR') {
                $wsdl = 'https://homextservicos-siafi.tesouro.gov.br/siafi' . $exercicio . 'he/services/cpr/manterContasPagarReceber?wsdl';
            }

        }


        $certificado = SfCertificado::where('situacao', '=', 1)->orderBy('id', 'desc')->first();

        $dado = null;
        foreach ($certificado->chaveprivada as $c) {
            $dado = explode('/', $c);
        }
        $chave = $dado[2];

        $dado = null;
        foreach ($certificado->certificado as $c) {
            $dado = explode('/', $c);
        }
        $cert = $dado[2];

        //certificado
        $key = env('APP_PATH') . env('APP_PATH_CERT') . $chave;
        $crtkey = env('APP_PATH') . env('APP_PATH_CERT') . $cert;


        $context = stream_context_create([
            'ssl' => [
                'local_cert' => $crtkey,
                'local_pk' => $key,
                'verify_peer' => false,
                'passphrase' => base64_decode($certificado->senhacertificado)
            ]
        ]);



        $client = new \SoapClient($wsdl, [
        'trace' => 1,
        'stream_context' => $context,
    ]);


        $cabecalho = $this->cabecalho($ug, $sf_id, $wsdl);

        $client->__setSoapHeaders(array($this->wssecurity($user, $pass), $cabecalho));


        return $client;

    }

    protected function cabecalho($ug, $sf_id, $wsdl)
    {

        if($wsdl == 'CONSULTA'){
            $xml = '<ns1:cabecalhoSIAFI><ug>' . $ug . '</ug></ns1:cabecalhoSIAFI>';
            $header = new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd',
                'Security',
                new \SoapVar($xml, XSD_ANYXML),
                true
            );
        }else{
            $nonce = SfNonce::select()->orderBy('id', 'desc')->first();
            $nonce_id = $nonce->id + 1;
            $data = [
                'sf_id' => $sf_id,
                'tipo' => $ug . "_" . $nonce_id . "_" . $sf_id,
            ];
            if ($sf_id == '') {
                unset($data['sf_id']);
            }
            SfNonce::create($data);

            $xml = '<ns1:cabecalhoSIAFI><ug>' . $ug . '</ug><bilhetador><nonce>' . $nonce_id . '</nonce></bilhetador></ns1:cabecalhoSIAFI>';
            $header = new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd',
                'Security',
                new \SoapVar($xml, XSD_ANYXML),
                true
            );
        }


        return $header;

    }

    protected function wssecurity($user, $password)
    {
        // Creating date using yyyy-mm-ddThh:mm:ssZ format
        $tm_created = gmdate('Y-m-d\TH:i:s\Z');
        $tm_expires = gmdate('Y-m-d\TH:i:s\Z', gmdate('U') + 180); //only necessary if using the timestamp element

        // Generating and encoding a random number
        //$simple_nonce = mt_rand();
        //$encoded_nonce = base64_encode($simple_nonce);

        // Compiling WSS string
        //$passdigest = base64_encode(sha1($simple_nonce . $tm_created . $password, true));

        // Initializing namespaces
        $ns_wsse = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
        $ns_wsu = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
        $password_type = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText';
        //$encoding_type = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary';

        // Creating WSS identification header using SimpleXML
        $root = new \SimpleXMLElement('<root/>');

        $security = $root->addChild('wsse:Security', null, $ns_wsse);

        //the timestamp element is not required by all servers
        //$timestamp = $security->addChild('wsu:Timestamp', null, $ns_wsu);
        //$timestamp->addAttribute('wsu:Id', 'Timestamp-28');
        //$timestamp->addChild('wsu:Created', $tm_created, $ns_wsu);
        //$timestamp->addChild('wsu:Expires', $tm_expires, $ns_wsu);

        $usernameToken = $security->addChild('wsse:UsernameToken', null, $ns_wsse);
        $usernameToken->addChild('wsse:Username', $user, $ns_wsse);
        $usernameToken->addChild('wsse:Password', $password, $ns_wsse)->addAttribute('Type', $password_type);
        //$usernameToken->addChild('wsse:Nonce', $encoded_nonce, $ns_wsse);
        //$usernameToken->addChild('wsu:Created', $tm_created, $ns_wsu);

        // Recovering XML value from that object
        $root->registerXPathNamespace('wsse', $ns_wsse);
        $full = $root->xpath('/root/wsse:Security');
        $auth = $full[0]->asXML();

        return new \SoapHeader($ns_wsse, 'Security', new \SoapVar($auth, XSD_ANYXML), true);

    }

    protected function submit($client, $parms, $tipo)
    {
        try {

            if ($tipo == 'CONUG') {
                $client->tabConsultarUnidadeGestora($parms);
            }

            if ($tipo == 'CONRAZAO') {

                $client->TabConsultarSaldoContabil($parms);
            }

            if ($tipo == 'INCDH') {
                $client->cprDHCadastrarDocumentoHabil($parms);
            }

            if ($tipo == 'ALTDH') {
                $client->cprDHAlterarDHIncluirItensDH($parms);
            }

            if ($tipo == 'CANDH') {
                $client->cprDHCancelarDH($parms);
            }

            if ($tipo == 'CONSIT') {
                $client->cprDAConsultarSituacao($parms);
            }

            if ($tipo == 'CONDH') {
                $client->cprDHDetalharDH($parms);
            }

        } catch (\Exception $e) {

            //var_dump($e);

        }

        return $client->__getLastResponse();

    }

    public function conrazao($ug_user, $amb, $ano, $ug, $contacontabil, $contacorrente, $mesref)
    {

        $cpf = str_replace('-', '', str_replace('.', '', backpack_user()->cpf));
        $senha = '';
        if (backpack_user()->senhasiafi) {

            $senha = base64_decode(backpack_user()->senhasiafi);

        } else {

            \Alert::error('Cadastre sua Senha SIAFI em "Meus Dados"!')->flash();

        }

        $client = $this->conexao_xml($cpf, $senha, $ug_user, '', $amb, $ano, 'CONSULTA');

        $parms = new \stdClass;
        $parms->tabConsultarSaldo = [
            'codUG' => $ug,
            'contaContabil' => $contacontabil,
            'contaCorrente' => $contacorrente,
            'mesRefSaldo' => $mesref
        ];

        $retorno = $this->submit($client, $parms, 'CONRAZAO');

        return $this->trataretorno($retorno);


    }

    public function conrazaoUser($ug_user, $amb, $ano, $ug, $contacontabil, $contacorrente, $mesref, $user)
    {

        $cpf = str_replace('-', '', str_replace('.', '', $user->cpf));
        $senha = '';

        if($user->senhasiafi){
            $senha = base64_decode($user->senhasiafi);
        }

        $client = $this->conexao_xml($cpf, $senha, $ug_user, '', $amb, $ano, 'CONSULTA');

        $parms = new \stdClass;
        $parms->tabConsultarSaldo = [
            'codUG' => $ug,
            'contaContabil' => $contacontabil,
            'contaCorrente' => $contacorrente,
            'mesRefSaldo' => $mesref
        ];

        $retorno = $this->submit($client, $parms, 'CONRAZAO');

        return $this->trataretorno($retorno);


    }

    public function consultaDh(
        BackpackUser $user,
        string $ug_user,
        string $amb,
        string $ano,
        $sfpadrao
    ) {

        $cpf = str_replace('-', '', str_replace('.', '', $user->cpf));
        $senha = '';
        if ($user->senhasiafi) {
            $senha = base64_decode($user->senhasiafi);
        } else {
            \Alert::error('Cadastre sua Senha SIAFI em "Meus Dados"!')->flash();
        }

        $client = $this->conexao_xml($cpf, $senha, $ug_user, $sfpadrao->id, $amb, $ano, 'CPR');

        $parms = $this->montaXmlcprDHDetalhar($sfpadrao);

        $retorno = $this->submit($client, $parms, 'CONDH');

//        return $this->trataretorno($retorno);

        return $retorno;

    }

    public function apropriaNovoDh(
        BackpackUser $user,
        string $ug_user,
        string $amb,
        string $ano,
        SfPadrao $sfpadrao
    ) {

        $cpf = str_replace('-', '', str_replace('.', '', $user->cpf));
        $senha = '';
        if ($user->senhasiafi) {

            $senha = base64_decode($user->senhasiafi);

        } else {

            \Alert::error('Cadastre sua Senha SIAFI em "Meus Dados"!')->flash();

        }

        $client = $this->conexao_xml($cpf, $senha, $ug_user, $sfpadrao->id, $amb, $ano, 'CPR');

        $parms = $this->montaXmlcprDHCadastrar($sfpadrao);


        $retorno = $this->submit($client, $parms, 'INCDH');

        return $this->trataretorno($retorno);

    }

    public function apropriaAlteracaoDh(
        BackpackUser $user,
        string $ug_user,
        string $amb,
        string $ano,
        SfPadrao $sfpadrao
    ) {

        $cpf = str_replace('-', '', str_replace('.', '', $user->cpf));
        $senha = '';
        if ($user->senhasiafi) {

            $senha = base64_decode($user->senhasiafi);

        } else {

            \Alert::error('Cadastre sua Senha SIAFI em "Meus Dados"!')->flash();

        }

        $client = $this->conexao_xml($cpf, $senha, $ug_user, $sfpadrao->id, $amb, $ano, 'CPR');

        $parms = $this->montaXmlcprDHAlterarIncluirItensEntrada($sfpadrao);

        $retorno = $this->submit($client, $parms, 'ALTDH');

        return $this->trataretorno($retorno);

    }

    private function montaXmlcprDHAlterarIncluirItensEntrada(SfPadrao $sfPadrao)
    {


        $parms = new \stdClass;
        $parms->cprDHAlterarIncluirItensEntrada = [
            'codUgEmit' => $sfPadrao->codugemit,
            'anoDH' => $sfPadrao->anodh,
            'codTipoDH' => $sfPadrao->codtipodh,
            'numDH' => $sfPadrao->numdh,
            'dtEmis' => $sfPadrao->dtemis,
            'txtMotivo' => $sfPadrao->txtmotivo,
            'pco' => $this->montaPco($sfPadrao->id),
            'centroCusto' => $this->montaCentroCusto($sfPadrao->id),
        ];

        return $parms;
    }

    private function montaXmlcprDHDetalhar($sfPadrao)
    {
        $parms = new \stdClass;
        $parms->cprDHDetalharEntrada = [
            'codUgEmit' => $sfPadrao->codugemit,
            'anoDH' => $sfPadrao->anodh,
            'codTipoDH' => $sfPadrao->codtipodh,
            'numDH' => $sfPadrao->numdh,
        ];

        return $parms;
    }

    private function montaXmlcprDHCadastrar(SfPadrao $sfPadrao)
    {


        $parms = new \stdClass;
        $parms->cprDHCadastrar = [
            'codUgEmit' => $sfPadrao->codugemit,
            'anoDH' => $sfPadrao->anodh,
            'codTipoDH' => $sfPadrao->codtipodh,
            'dadosBasicos' => $this->montaDadosBasicos($sfPadrao->id),
            'pco' => $this->montaPco($sfPadrao->id),
            'centroCusto' => $this->montaCentroCusto($sfPadrao->id),
        ];

        return $parms;
    }

    private function montaCentroCusto(string $sfpadrao_id)
    {
        $array = [];

        $dados = Sfcentrocusto::where('sfpadrao_id', $sfpadrao_id)
            ->get();

        if ($dados) {
            foreach ($dados as $dado) {
                $ar = [
                    'numSeqItem' => $dado->numseqitem,
                    'codCentroCusto' => $dado->codcentrocusto,
                    'mesReferencia' => $dado->mesreferencia,
                    'anoReferencia' => $dado->anoreferencia,
                    'codUgBenef' => $dado->codugbenef,
                ];

                $array[] = $this->filtraDadosArray($ar) + [
                        'relPcoItem' => $this->montaRelItemVlrCc($dado->id, 'RELPCOITEM'),
                        'relOutrosLanc' => $this->montaRelItemVlrCc($dado->id, 'RELOULAN'),
                        'relOutrosLancCronogramaPatrimonial' => $this->montaRelItemVlrCc($dado->id, 'REOULACRPA'),
                        'relPsoItem' => $this->montaRelItemVlrCc($dado->id, 'RELPSOITEM'),
                        'relEncargo' => $this->montaRelItemVlrCc($dado->id, 'RELENCARGO'),
                        'relAcrescimoDeducao' => $this->montaRelItemVlrCc($dado->id, 'RELACREDED'),
                        'relAcrescimoEncargo' => $this->montaRelItemVlrCc($dado->id, 'RELACREENC'),
                        'relAcrescimoDadosPag' => $this->montaRelItemVlrCc($dado->id, 'RELACREPGT'),
                        'relDespesaAntecipada' => $this->montaRelItemVlrCc($dado->id, 'RELDESPANT'),
                        'relDespesaAnular' => $this->montaRelItemVlrCc($dado->id, 'RELDESPANU'),
                    ];
            }
        }
        $centrocusto = $array;

        return $centrocusto;
    }

    private function montaRelItemVlrCc(string $sfcc_id, string $tipo)
    {
        $array = [];

        $dados = Sfrelitemvlrcc::where('sfcc_id', $sfcc_id)
            ->where('tipo', '=', $tipo)
            ->get();

        if ($dados) {
            foreach ($dados as $dado) {
                $ar = [
                    'numSeqPai' => $dado->numseqpai,
                    'numSeqItem' => $dado->numseqitem,
                    'vlr' => $dado->vlr,
                ];

                $array[] = $this->filtraDadosArray($ar);
            }
        }

        $relitemvlrcc = $array;

        return $relitemvlrcc;
    }

    private function montaPco(string $sfpadrao_id)
    {
        $array = [];

        $dados = SfPco::where('sfpadrao_id', $sfpadrao_id)
            ->get();

        if ($dados) {
            foreach ($dados as $dado) {
                $ar = [
                    'numSeqItem' => $dado->numseqitem,
                    'codSit' => $dado->codsit,
                    'codUgEmpe' => $dado->codugempe,
                    'indrTemContrato' => ($dado->indrtemcontrato) == false ? 0 : 1,
                    'txtInscrD' => $dado->txtinscrd,
                    'numClassD' => ($dado->numclassd) == 0 ? '' : $dado->numclassd,
                    'txtInscrE' => $dado->txtinscre,
                    'numClassE' => ($dado->numclasse) == 0 ? '' : $dado->numclasse,
                ];

                $array[] = $this->filtraDadosArray($ar) + ['pcoItem' => $this->montaPcoItens($dado->id)];
            }
        }

        $pco = $array;

        return $pco;
    }

    private function montaPcoItens(string $sfpco_id)
    {
        $pcoitens = [];
        $array = [];

        $dados = SfPcoItem::where('sfpco_id', $sfpco_id)
            ->get();

        if ($dados) {
            foreach ($dados as $dado) {
                $ar = [
                    'numSeqItem' => $dado->numseqitem,
                    'numEmpe' => $dado->numempe,
                    'codSubItemEmpe' => $dado->codsubitemempe,
                    'indrLiquidado' => ($dado->indrliquidado) == false ? 0 : 1,
                    'vlr' => $dado->vlr,
                    'txtInscrA' => $dado->txtinscra,
                    'numClassA' => ($dado->numclassa) == 0 ? '' : $dado->numclassa,
                    'txtInscrB' => $dado->txtinscrb,
                    'numClassB' => ($dado->numclassb) == 0 ? '' : $dado->numclassb,
                    'txtInscrC' => $dado->txtinscrc,
                    'numClassC' => ($dado->numclassc) == 0 ? '' : $dado->numclassc,
                ];

                $array[] = $this->filtraDadosArray($ar);
            }
        }

        $pcoitens = $array;

        return $pcoitens;
    }

    private function montaDadosBasicos(string $sfpadrao_id)
    {
        $array = [];

        $dado = SfDadosBasicos::where('sfpadrao_id', $sfpadrao_id)
            ->first();

        if ($dado->id) {
            $ar = [
                'dtEmis' => $dado->dtemis,
                'dtVenc' => $dado->dtvenc,
                'codUgPgto' => $dado->codugpgto,
                'vlr' => $dado->vlr,
                'txtObser' => $dado->txtobser,
                'txtInfoAdic' => $dado->txtinfoadic,
                'vlrTaxaCambio' => $dado->vlrtaxacambio,
                'txtProcesso' => $dado->txtprocesso,
                'dtAteste' => $dado->dtateste,
                'codCredorDevedor' => $dado->codcredordevedor,
                'dtPgtoReceb' => $dado->dtpagtoreceb,

            ];

            $array = $this->filtraDadosArray($ar);
            $array['docOrigem'] = $this->montaDocOrigem($dado->id);

        }

        $dadosbasicos = $array;

        return $dadosbasicos;
    }

    private function montaDocOrigem(string $sfdadosbasicos_id)
    {
        $array = [];

        $dados = SfDocOrigem::where('sfdadosbasicos_id', $sfdadosbasicos_id)
            ->get();

        if ($dados) {
            foreach ($dados as $dado) {
                $ar = [
                    'codIdentEmit' => $dado->codidentemit,
                    'dtEmis' => $dado->dtemis,
                    'numDocOrigem' => $dado->numdocorigem,
                    'vlr' => $dado->vlr,
                ];

                $array[] = $this->filtraDadosArray($ar);
            }
        }

        $docorigem = $array;

        return $docorigem;
    }

    public function filtraDadosArray(array $entrada)
    {

        $array = array_filter($entrada, function ($a) {
            return trim($a) !== "";
        });

        $array1 = array_filter($array, function ($a) {
            return trim($a) !== null;
        });

        $array2 = array_filter($array1, function ($a) {
            return trim($a) !== 0;
        });

        return $array2;
    }

    protected function trataretorno($retorno)
    {

        $xml = simplexml_load_string(str_replace(':', '', $retorno));

        $resultado = [];

        if(isset($xml->soapHeader)){
            foreach ($xml->soapHeader as $var2) {

                foreach ($var2->ns2EfetivacaoOperacao as $var3) {

                    $this->resultado[0] = $var3->resultado;

                    if ($this->resultado[0] == 'SUCESSO') {

                        foreach ($xml->soapBody as $var4) {

                            if (isset($var4->ns3cprDHCadastrarDocumentoHabilResponse)) {

                                foreach ($var4->ns3cprDHCadastrarDocumentoHabilResponse as $var5) {

                                    foreach ($var5->CprDhResposta as $var6) {

                                        $this->resultado[1] = $var6->numDH;
                                        $this->resultado[2] = $var6->numNs;

                                    }

                                }

                            }

                            if (isset($var4->ns3cprDHAlterarDHIncluirItensResponse)) {

                                foreach ($var4->ns3cprDHAlterarDHIncluirItensResponse as $var5) {

                                    foreach ($var5->cprDhResposta as $var6) {

                                        $this->resultado[1] = $var6->numDH;
                                        $this->resultado[2] = $var6->numNs;

                                    }

                                }

                            }

                            if (isset($var4->ns3tabConsultarSaldoContabilResponse)) {

                                foreach ($var4->ns3tabConsultarSaldoContabilResponse as $var5) {

                                    foreach ($var5->saldoContabilInfo as $var6) {

                                        $this->resultado[1] = $var6->codUG;
                                        $this->resultado[2] = $var6->contaContabil;
                                        $this->resultado[3] = $var6->contaCorrente;
                                        $this->resultado[4] = $var6->vlrSaldo;
                                        $this->resultado[5] = $var6->tipoSaldo;

                                    }

                                }

                            }

                        }

                    }

                    if ($this->resultado[0] == 'FALHA') {

                        foreach ($xml->soapBody as $var4) {

                            if (isset($var4->ns3cprDHCadastrarDocumentoHabilResponse)) {

                                foreach ($var4->ns3cprDHCadastrarDocumentoHabilResponse as $var5) {

                                    foreach ($var5->CprDhResposta as $var6) {

                                        if (isset($var6->mensagem)) {

                                            $this->resultado[1] = '';

                                            foreach ($var6->mensagem as $var7) {

                                                $this->resultado[1] .= " | " . str_replace('"', '',
                                                        str_replace("'", "", trim($var7->txtMsg)));

                                            }

                                        }

                                    }

                                }

                            }

                            if (isset($var4->ns3cprDHAlterarDHIncluirItensResponse)) {

                                foreach ($var4->ns3cprDHAlterarDHIncluirItensResponse as $var5) {

                                    foreach ($var5->cprDhResposta as $var6) {

                                        if (isset($var6->mensagem)) {

                                            $this->resultado[1] = '';

                                            foreach ($var6->mensagem as $var7) {

                                                $this->resultado[1] .= " | " . str_replace('"', '',
                                                        str_replace("'", "", trim($var7->txtMsg)));

                                            }

                                        }

                                    }

                                }

                            }

                            if (isset($var4->soapFault)) {

                                foreach ($var4->soapFault as $var5) {

                                    $this->resultado[1] = 0;
                                    $this->resultado[2] = " | " . str_replace('"', '',
                                            str_replace("'", "", $var5->faultcode . " - " . $var5->faultstring));

                                }

                            }

                        }

                    }

                }

            }
        }


        return $this;
    }

}
