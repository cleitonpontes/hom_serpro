<?php

namespace App\Http\Controllers\Acessogov;

use App\Http\Controllers\Controller;
use App\Models\BackpackUser;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

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

    const MSG_ERRO = 'Ocorreu um erro ao se comunicar com o acesso gov, tente novamente mais tarde';
    const MSG_CHECK_EMAIL = "Seu e-mail não foi validado no cadastro Gov.br. Acesse o site acesso.gov para realizar a validação.";


    public function __construct()
    {
        $this->host_acessogov = config('acessogov.host');
        $this->response_type  = config('acessogov.response_type');
        $this->client_id	  = config('acessogov.client_id');
        $this->scope          = config('acessogov.scope');
        $this->redirect_uri   = config('app.url') . '/acessogov';
        $this->nonce          = Str::random(12);
        $this->state          = Str::random(13);
        $this->secret         = config('acessogov.secret');
    }

    public function autorizacao()
    {
        $url = $this->host_acessogov
            . '/authorize?response_type=' . $this->response_type
            . '&client_id=' . $this->client_id
            . '&scope=' . $this->scope
            . '&redirect_uri=' . urlencode($this->redirect_uri . '/tokenacesso')
            . '&nonce=' . $this->nonce
            . '&state=' . $this->state;

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
                'redirect_uri' => urlencode($this->redirect_uri.'/tokenacesso')
            );

            foreach($campos as $key=>$value) {
                $fields_string .= $key.'='.$value.'&';
            }

            rtrim($fields_string, '&');

            $urlProvider = $this->host_acessogov . '/token';
            $urlJwk = $this->host_acessogov. '/jwk';

            $ch_token = curl_init();
            curl_setopt($ch_token, CURLOPT_URL, $urlProvider);
            curl_setopt($ch_token, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch_token, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch_token, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch_token, CURLOPT_POST, true);
            curl_setopt($ch_token, CURLOPT_HTTPHEADER, $headers);
            $json_output_tokens = json_decode(curl_exec($ch_token), true);
            curl_close($ch_token);

            $ch_jwk = curl_init();
            curl_setopt($ch_jwk,CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch_jwk,CURLOPT_URL, $urlJwk);
            curl_setopt($ch_jwk, CURLOPT_RETURNTRANSFER, TRUE);
            $json_output_jwk = json_decode(curl_exec($ch_jwk), true);
            curl_close($ch_jwk);

            $access_token = $json_output_tokens['access_token'];

            try{
                $json_output_payload_access_token = $this->processToClaims($access_token, $json_output_jwk);
            } catch (Exception $e) {
                return redirect()->route('login')->withError($e->getMessage());
            }

            $id_token = $json_output_tokens['id_token'];

            try{
                $json_output_payload_id_token = $this->processToClaims($id_token, $json_output_jwk);
            } catch (Exception $e) {
                return redirect()->route('login')->withError($e->getMessage());
            }

            $retorno = ['access_token' => $access_token, 'id_token' => $id_token];

            $rota = $this->retornaDados($retorno);

            if($rota == 'login')
                return redirect('login')->withWarning(self::MSG_CHECK_EMAIL);

            return redirect()->route('transparencia.index');

        } catch (Exception $e) {
            return redirect()->route('login')->withError(self::MSG_ERRO);
        }
    }

    public function retornaDados(array $token)
    {
        try {
            $headers = array(
                'Authorization: Bearer '. $token['access_token']
            );

            $url = $this->host_acessogov. "/jwk";

            $ch_jwk = curl_init();
            curl_setopt($ch_jwk, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch_jwk,CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch_jwk,CURLOPT_URL, $url);
            curl_setopt($ch_jwk, CURLOPT_RETURNTRANSFER, TRUE);
            $json_output_jwk = json_decode(curl_exec($ch_jwk), true);
            curl_close($ch_jwk);

            $dados = $this->processToClaims($token['id_token'], $json_output_jwk);

            (!$dados['email_verified']) ? $rota = $this->login($dados) : $rota = 'login';

            return $rota;

        } catch (Exception $e) {
            return redirect()->route('login')->withError(self::MSG_ERRO);
        }
    }

    public function login($dados)
    {
        $cpf = $this->mask($dados['sub'],'###.###.###-##');
        $user = BackpackUser::where('cpf',$cpf)->first();;
        (is_null($user))? $rota = $this->cadastraUsuarioAcessoGov($dados) : $rota = $this->loginUsuarioAcessoGov($user);

        return $rota;
    }

    public function cadastraUsuarioAcessoGov($dados)
    {
        $params = [
            'cpf' => $this->mask($dados['sub'],'###.###.###-##'),
            'name' => $dados['name'],
            'password' => Hash::make($dados['amr'][0] . $this->generateRandomString(5)),
            'email' => $dados['email'],
            'acessogov' => 1
            ];

            $backpackuser = new BackpackUser($params);
            $backpackuser->save();
            $user = BackpackUser::where('cpf',$params['cpf'])->first();

            $rota = $this->loginUsuarioAcessoGov($user);
            return $rota;
    }

    public function loginUsuarioAcessoGov(BackpackUser $user)
    {
        Auth::login($user, true);
        $rota = 'transparencia.index';
        return $rota;
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
        $RSAPublicKey = "-----BEGIN PUBLIC KEY-----\r\n"
            . chunk_split(base64_encode($RSAPublicKey), 64)
            . '-----END PUBLIC KEY-----';

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

    function mask($val, $mask){
        $maskared = '';
        $k = 0;

        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k]))
                    $maskared .= $val[$k++];
            } else {
                if(isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }

        return $maskared;
    }

}
