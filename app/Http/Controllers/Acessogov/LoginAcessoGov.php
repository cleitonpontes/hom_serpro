<?php


namespace App\Http\Controllers\Acessogov;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use App\Models\BackpackUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Request;

class LoginAcessoGov extends Controller
{
    protected $host_acessogov;

    public function __construct()
    {
        $this->host_acessogov = 'https://sso.staging.acesso.gov.br';
    }

    public function autorizacao()
    {
        $response_type = 'code';
        $client_id	= 'sc-treino.agu.gov.br';
        $scope = 'openid+email+phone+profile+govbr_confiabilidades';
        $redirect_uri = urlencode('https://sc-treino.agu.gov.br/acessogov/tokenacesso');
        $nonce =  $this->generateRandomString(12);//valor aleatório - Item obrigatório.
        $state = $this->generateRandomString(13); //Item não obrigatório.

        $url = $this->host_acessogov . '/authorize?response_type=' . $response_type . '&client_id=' . $client_id . '&scope=' . $scope . '&redirect_uri=' . $redirect_uri . '&nonce=' . $nonce.'&state='.$state;

        return Redirect::away($url);
    }

    public function tokenAcesso(Request $request)
    {
        $code = $request->get('code');
        $state = $request->get('state');
        $redirect_uri = urlencode('https://sc-treino.agu.gov.br/acessogov/login');
        $secret = 'PrWSPE-3dlrbZgIHQxDrXV7Oq3c4FCCdz1nI4o7htB5FHlfm97fl5MqK3XOMwPnu4nQCxLYGg1HoJgeWVINigA';
        $headers = array(
            "Content-type:application/x-www-form-urlencoded",
            "Authorization: Basic " . base64_encode($secret)
        );

        $url = $this->host_acessogov . '/token?response_type=authorization_code&code='.$code.'&redirect_uri='.$redirect_uri;

        $ch = curl_init();
                curl_setopt($ch, CURLOPT_TIMEOUT, 900);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 900);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_exec($ch);
            curl_close($ch);

    }

    public function login(Request $request)
    {

        dd($request);

        $userJson = [
        'sub' => '444.444.444-44',
        'amr' => ['passwd' => '123456'],
        'name' => 'Ciclano de tal',
        'email' => 'ciclanodetal@foo.com'
        ];

        $cpf = $userJson['sub'];
        $user = BackpackUser::where('cpf',$cpf)->first();

        (is_null($user))? $this->cadastraUsuarioAcessoGov($userJson) : $this->loginUsuarioAcessoGov($user);
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

    public function processToClaims(string $token){
                $url = 'https://sso.staging.acesso.gov.br/jwk';


        }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
