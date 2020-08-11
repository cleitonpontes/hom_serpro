<?php


namespace App\Http\Controllers\Acessogov;

use App\Http\Controllers\Controller;
use App\Models\BackpackUser;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;


class LoginAcessoGov extends Controller
{
    private $host_acessogov;
    private $response_type;
    private $redirect_uri;
    private $client_id;
    private $scope;
    private $nonce;
    private $state;
    private $secret;
    private $url_logout;

    public function __construct()
    {
        $this->host_acessogov = 'https://sso.staging.acesso.gov.br';
        $this->response_type = 'code';
        $this->client_id	= 'sc-treino.agu.gov.br';
        $this->scope = 'openid+email+phone+profile+govbr_confiabilidades';
        $this->redirect_uri = 'https://sc-treino.agu.gov.br/acessogov';
        $this->nonce =  $this->generateRandomString(12);//valor aleatório - Item obrigatório.
        $this->state = $this->generateRandomString(13); //Item não obrigatório.
        $this->secret = 'PrWSPE-3dlrbZgIHQxDrXV7Oq3c4FCCdz1nI4o7htB5FHlfm97fl5MqK3XOMwPnu4nQCxLYGg1HoJgeWVINigA';
    }

    public function autorizacao()
    {
        $url = $this->host_acessogov
                    . '/authorize?response_type='.$this->response_type
                    . '&client_id='.$this->client_id
                    . '&scope='.$this->scope
                    . '&redirect_uri='.urlencode($this->redirect_uri.'/tokenacesso')
                    . '&nonce='.$this->nonce
                    . '&state='.$this->state;

        return Redirect::away($url);
    }


    public function tokenAcesso(Request $request)
    {

        try {
            $fields_string = '';

            $headers = array(
                'Content-Type:application/x-www-form-urlencoded',
                'Authorization: Basic '. base64_encode($this->client_id . ":" . $this->secret)
            );

            $campos = array(
                'grant_type' => 'authorization_code',
                'code' => $request->get('code'),
                'redirect_uri' => urlencode('https://sc-treino.agu.gov.br/acessogov/tokenacesso')
            );

            foreach($campos as $key=>$value) {
                $fields_string .= $key.'='.$value.'&';
            }

            rtrim($fields_string, '&');

            $URL_PROVIDER = $this->host_acessogov.'/token';

            $ch_token = curl_init();
                            curl_setopt($ch_token, CURLOPT_URL, $URL_PROVIDER);
                            curl_setopt($ch_token, CURLOPT_POSTFIELDS, $fields_string);
                            curl_setopt($ch_token, CURLOPT_RETURNTRANSFER, TRUE);
                            curl_setopt($ch_token, CURLOPT_SSL_VERIFYPEER, true);
                            curl_setopt($ch_token, CURLOPT_POST, true);
                            curl_setopt($ch_token, CURLOPT_HTTPHEADER, $headers);
            $json_output_tokens = json_decode(curl_exec($ch_token), true);
                         curl_close($ch_token);


            $url = $this->host_acessogov. "/jwk";
            $ch_jwk = curl_init();
                        curl_setopt($ch_jwk,CURLOPT_SSL_VERIFYPEER, true);
                        curl_setopt($ch_jwk,CURLOPT_URL, $url);
                        curl_setopt($ch_jwk, CURLOPT_RETURNTRANSFER, TRUE);
            $json_output_jwk = json_decode(curl_exec($ch_jwk), true);
            curl_close($ch_jwk);

            $access_token = $json_output_tokens['access_token'];

            try{
                $json_output_payload_access_token = $this->processToClaims($access_token, $json_output_jwk);
            } catch (Exception $e) {
                $detalhamentoErro  = $e;
            }

            $id_token = $json_output_tokens['id_token'];

            try{
                $json_output_payload_id_token = $this->processToClaims($id_token, $json_output_jwk);
            } catch (Exception $e) {
                $detalhamentoErro = $e;
            }

            $retorno =  ['access_token' => $access_token, 'id_token' => $id_token];
            $this->login($retorno);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return 'Ocorreu um erro ao se comunicar com o acesso gov, tente novamente mais tarde';
        }

    }


    public function login(array $token)
    {
        dd($token);

        try {
            $headers = array(
                'Authorization: Bearer '. $token->get('access_token')
            );

            $url = $this->url_provider. "/jwk";

            $ch_jwk = curl_init();
            curl_setopt($ch_jwk, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch_jwk,CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch_jwk,CURLOPT_URL, $url);
            curl_setopt($ch_jwk, CURLOPT_RETURNTRANSFER, TRUE);
            $json_output_jwk = json_decode(curl_exec($ch_jwk), true);
            curl_close($ch_jwk);

            $dados = $this->processToClaims($token['id_token'], $json_output_jwk);
            dd($dados);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function processToClaims($token, $jwk)
    {
        $modulus = JWT::urlsafeB64Decode($jwk['keys'][0]['n']);
        $publicExponent = JWT::urlsafeB64Decode($jwk['keys'][0]['e']);
        $components = array(
            'modulus' => pack('Ca*a*', 2, $this->encodeLength(strlen($modulus)), $modulus),
            'publicExponent' => pack('Ca*a*', 2, $this->encodeLength(strlen($publicExponent)), $publicExponent)
        );
        $RSAPublicKey = pack(
            'Ca*a*a*',
            48,
            $this->encodeLength(strlen($components['modulus']) + strlen($components['publicExponent'])),
            $components['modulus'],
            $components['publicExponent']
        );
        $rsaOID = pack('H*', '300d06092a864886f70d0101010500'); // hex version of MA0GCSqGSIb3DQEBAQUA
        $RSAPublicKey = chr(0) . $RSAPublicKey;
        $RSAPublicKey = chr(3) . $this->encodeLength(strlen($RSAPublicKey)) . $RSAPublicKey;
        $RSAPublicKey = pack(
            'Ca*a*',
            48,
            $this->encodeLength(strlen($rsaOID . $RSAPublicKey)),
            $rsaOID . $RSAPublicKey
        );
        $RSAPublicKey = "-----BEGIN PUBLIC KEY-----\r\n" . chunk_split(base64_encode($RSAPublicKey), 64) . '-----END PUBLIC KEY-----';

        JWT::$leeway = 3 * 60; //em segundos

        $decoded = JWT::decode($token, $RSAPublicKey, array('RS256'));

        return (array)$decoded;
    }


    public function encodeLength($length)
    {
        if ($length <= 0x7F) {
            return chr($length);
        }
        $temp = ltrim(pack('N', $length), chr(0));
        return pack('Ca*', 0x80 | strlen($temp), $temp);
    }



    public function cadastraUsuarioAcessoGov($userJson)
    {
        $params = [
            'cpf' => $userJson['sub'],
            'name' => $userJson['name'],
            'password' => Hash::make($userJson['amr']['passwd']),
            'email' => $userJson['email'],
            'acessogov' => 1
            ];
            $backpackuser = new BackpackUser($params);
            $backpackuser->save();
            $user = BackpackUser::where('cpf',$params['cpf'])->first();

            $this->loginUsuarioAcessoGov($user);
    }

    public function loginUsuarioAcessoGov(BackpackUser $user)
    {
        Auth::login($user);
        backpack_url('dashboard');

    }


    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

//    public function login(Request $request)
//    {
//
//        dd($request);
//
//        $userJson = [
//            'sub' => '444.444.444-44',
//            'amr' => ['passwd' => '123456'],
//            'name' => 'Ciclano de tal',
//            'email' => 'ciclanodetal@foo.com'
//        ];
//
//        $cpf = $userJson['sub'];
//        $user = BackpackUser::where('cpf',$cpf)->first();
//
//        (is_null($user))? $this->cadastraUsuarioAcessoGov($userJson) : $this->loginUsuarioAcessoGov($user);
//    }

//    public function tokenAcesso(Request $request)
//    {
//        $code = $request->get('code');
//        $state = $request->get('state');
//        $redirect_uri = urlencode('https://sc-treino.agu.gov.br/acessogov/login');
//        $secret = 'PrWSPE-3dlrbZgIHQxDrXV7Oq3c4FCCdz1nI4o7htB5FHlfm97fl5MqK3XOMwPnu4nQCxLYGg1HoJgeWVINigA';
//        $headers = array(
//            "Content-type:application/x-www-form-urlencoded",
//            "Authorization: Basic " . base64_encode($secret)
//        );
//
//        $url = $this->host_acessogov . '/token?response_type=authorization_code&code='.$code.'&redirect_uri='.$redirect_uri;
//
//        $ch = curl_init();
//                curl_setopt($ch, CURLOPT_TIMEOUT, 900);
//                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 900);
//                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                curl_setopt($ch, CURLOPT_POST, true);
//                curl_setopt($ch, CURLOPT_URL, $url);
//                curl_exec($ch);
//            curl_close($ch);
//
//    }
}
