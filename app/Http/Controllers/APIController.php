<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;
use JWTAuth;

class APIController extends Controller
{

    public function range($dataMin, $dataMax){
        $dataMin = $this->verificaData($dataMin);
        $dataMax = $this->verificaData($dataMax);
        if($dataMin == null && $dataMax == null){
            return null;
        }
        $this->validaRange($dataMin, $dataMax);
        return [$dataMin,$dataMax];
    }

    public function verificaData($dataInformada){
        
        if($dataInformada == null){
            return null;
        }

        if(!$this->validaDataRegex($dataInformada)){
            abort(response()->json(['errors' => "Os parametros de Data devem seguir o seguinte padrão (2000-01-01T01:00:00Z)",], 422));
            return;
        }

        try {
            $dataInformada = new Carbon($dataInformada);
        } catch (Throwable $e) {
            abort(response()->json($e->getMessage()));
            return $e;
        }
        
        if ($dataInformada > Carbon::now()) {
            abort(response()->json(['errors' => "A data deve ser menor que a atual",], 422));
            return;
        }

        return $dataInformada;

    }

    function validaRange($dtMin, $dtMax){

        if($dtMin == null || $dtMax == null){
            abort(response()->json(['errors' => "Quando informado, ambos os parametros de data são obrigatórios",], 422));
            return;
        }

        if($dtMin->gte($dtMax)){
            abort(response()->json(['errors' => "O parametro 'dt_alteracao_min' deve ser menor que o'dt_alteracao_max'",], 422));
            return;
        }

        return;

    }

    function validaDataRegex($date){

        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/', $date, $parts)) {
            return true;
        } else {
            return false;
        }
    }

    public function usuarioTransparencia(string $nome, string $cpf, bool $dadosAbertos){   
        if ($dadosAbertos) {
            return $cpf . ' - ' . $nome;
        }else{
            $cpf = '***' . substr($cpf, 3, 9) . '**';
            return $cpf . ' - ' . $nome;
        }
    }

    public function dadosAbertos(){
        //auth()->check()sendo utilizado por mais segurança (informação redundante);
        return $this->verificaAutenticacaoJWT() && auth()->check() && backpack_user()->hasPermissionTo('usuario_consulta_api');
    }

    public function verificaAutenticacaoJWT(){
        $autenticadoJWT = false;
        try {
            $tokenFetch = JWTAuth::parseToken()->authenticate();
            if ($tokenFetch) {
                $autenticadoJWT = true;
            } else {
                $autenticadoJWT = false;
            }
        } catch(\Tymon\JWTAuth\Exceptions\JWTException $e){
            $autenticadoJWT = false;
        }
        return $autenticadoJWT;
    }

    public function verificaUG(String $ug){
        if(!Unidade::where('codigo', '=', $ug)->exists()){
            abort(response()->json(['errors' => "A 'UG' informada não existe",], 422));
            return;
        }else{
            return $ug;
        }
    }

    public function verificaListaParametro($parametros, $listaOriginal, $nomeParametro)
    {
        if ($parametros != null) {
            try {
                //Formato lista simples: Administrador,Acesso API
                $parametros = explode(",", $parametros);
            } catch (Throwable $e) {
                abort(response()->json($e->getMessage()));
                return $e;
            }
            
            if(!is_array($parametros)){
                abort(response()->json(['errors' => "Array ('. $nomeParametro .') em formato invalido",], 422));
            }

            if (empty($parametros)) {
                return null;
            }
            
            $errados = array_diff($parametros, $listaOriginal);

            if (!empty($errados)) {
                abort(response()->json(['errors' => $nomeParametro. " inválidos: [". implode(",",$errados) ."];",], 422));
                return;
            }

            return $parametros;
        } else {
            return null;
        }
    }

}
