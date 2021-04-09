<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\Formatador;
use App\Models\BackpackUser;
use App\Models\Role;
use App\Models\Unidade;
use App\User;
use Carbon\Carbon;
use PHPUnit\Util\Json;
use Illuminate\Support\Collection;
use Throwable;

class UsuarioAntecipaGovController extends Controller
{   
    use Formatador;
    
    //perfis={"perfis":["Administrador", "Acesso API"]}

    /**
     * @OA\Get(
     *     tags={"usuarios"},
     *     summary="Retorna os usuários de uma UG",
     *     description="Retorna um Json de usuários",
     *     path="api/v1/usuario/ug/{unidade_codigo}",
     *     @OA\Parameter(
     *         name="unidade_codigo",
     *         in="path",
     *         description="codigo da unidade",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários da UG retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Usuarios")
     *         ),
     *     )
     * )
     */
    public function usuariosPorUG(String $ug, Request $request)
    {   
        $usuarios_array = [];
        $usuarios = $this->buscaUsuariosPorUG($this->verificaUG($ug), $this->verificaData($request->date, $request->time), $this->verificaPerfis($request->perfis));
     
        foreach ($usuarios as $usuario) {
            $usuarios_array[] = $this->formataUsuarioAPI($usuario);
        }

        return json_encode($usuarios_array);
    }
    /**
     * @OA\Get(
     *     tags={"usuarios"},
     *     summary="Retorna um usuário pelo CPF",
     *     description="Retorna um usuários",
     *     path="/api/v1/usuario/cpf/{cpf}",
     *     @OA\Parameter(
     *         name="cpf",
     *         in="path",
     *         description="CPF do usuário",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cronograma do contrato retornado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Cronograma")
     *         ),
     *     )
     * )
     */
    public function usuarioPorCPF(String $cpf, Request $request)
    {   
        $usuarios_array = [];
        $usuarios = $this->buscaUsuarioPorCPF($cpf, $this->verificaData($request->date, $request->time));
        
        foreach ($usuarios as $usuario) {
            $usuarios_array[] = $this->formataUsuarioAPI($usuario);
        }

        return json_encode($usuarios_array);

    }

    private function buscaUsuariosPorUG(String $ug, $dataInformada, $perfis)
    {   
        
        $usuarios = BackpackUser::whereHas('unidade', function ($x) use($ug) {
            $x->where('codigo',$ug);
        })
            ->when($dataInformada != null, function ($d) use ($dataInformada) {
                $d->where('users.updated_at', '>', $dataInformada);
            })
            ->when($perfis!=null, function ($f) use ($perfis){
                $f->whereHas('roles', function($q) use ($perfis){
                    $q->whereIn('name', $perfis);
                });
            })
            ->get();
        return $usuarios;
    }

    private function buscaUsuarioPorCPF(String $cpf, $dataInformada)
    {   
        $usuario = BackpackUser::where('cpf', $this->formataCPF($cpf))
            ->when($dataInformada != null, function ($d) use ($dataInformada) {
                $d->where('users.updated_at', '>', $dataInformada);
            })
            ->get();

        return $usuario;
    }

    private function formataUsuarioAPI(BackpackUser $usuario){
        
        return [
            'cpf' => $usuario->cpf,
            'nome' => $usuario->name,
            'email' => $usuario->email,
            'ugprimaria' => $usuario->unidadeprimaria($usuario->ugprimaria)->codigo,
            'ugssecundarias' => $usuario->unidadesSecundarias(),
            'perfis' => array_column($usuario->roles->toArray(), 'name'),
            'situacao' => $usuario->situacao == true ? 'Ativo' : 'Inativo'
        ];   
    }

    private function verificaUG(String $ug){
        if(!Unidade::where('codigo', '=', $ug)->exists()){
            abort(response()->json(['errors' => "A 'UG' informada não existe",], 422));
            return;
        }else{
            return $ug;
        }
    }

    private function verificaData($data, $time)
    {   
        if ($data != null || $time != null) {
            
            if ($this->IsNullOrEmptyString($data) && !$this->IsNullOrEmptyString($time)) {
                abort(response()->json(['errors' => "O parametro 'date' é obrigatorio quando o 'time' for informado",], 422));
                return;
            }
            if (!$this->IsNullOrEmptyString($data) && $this->IsNullOrEmptyString($time)) {
                $time = '0800';
            }
            if (!is_numeric($data) || !is_numeric($data)) {
                abort(response()->json(['errors' => "Valor não numerico informado para 'date' ou 'time'",], 422));
                return;
            }
            try {
                $dataInformada = new Carbon($data . ' ' . $time);
            } catch (Throwable $e) {
                abort(response()->json($e->getMessage()));
                return $e;
            }
            if ($dataInformada > Carbon::now()) {
                abort(response()->json(['errors' => "A data deve ser menor que a atual",], 422));
                return;
            }
            return $dataInformada;
        } else {
            return null;
        }
    }

    private function verificaPerfis($perfis)
    {
        if ($perfis != null) {
            try {
                //Formato lista sistema: ["Administrador", "Acesso API"]
                //$perfis = json_decode($perfis);
                //Formato lista simples: Administrador,Acesso API
                $perfis = explode(",", $perfis);
            } catch (Throwable $e) {
                abort(response()->json($e->getMessage()));
                return $e;
            }
            
            if(!is_array($perfis)){
                abort(response()->json(['errors' => "Array (perfis) em formato invalido",], 422));
            }

            if (empty($perfis)) {
                return null;
            }
            
            $errados = array_diff($perfis, Role::get()->pluck('name')->toArray());

            if (!empty($errados)) {
                abort(response()->json(['errors' => "Perfis inválidos: [". implode(",",$errados) ."];",], 422));
                return;
            }

            return $perfis;
        } else {
            return null;
        }
    }
    private function IsNullOrEmptyString($data)
    {
        return (!isset($data) || trim($data) === '');
    }

}
    /**
     *
     * @OA\Components(
     *
     *          @OA\Schema(
     *             schema="usuarios",
     *             type="object",
     *             @OA\Property(property="cpf",type="string",example="111.111.111-11"),
     *             @OA\Property(property="nome",type="string",example="Fulano de Souza"),
     *             @OA\Property(property="email",type="string",example="fulano@email.com"),
     *             @OA\Property(property="ugprimaria",type="string",example="110161"),
     *             @OA\Property(property="ugssecundarias",type="array", @OA\Items(type="object", example="["110001","020001","170200","110203"]"))
     *             @OA\Property(property="perfis",type="array", @OA\Items(type="object", example="["Setor Contratos","Administrador Unidade","Administrador","Acesso API"]"))
     *             @OA\Property(property="situacao",type="string",example="Ativo"),
     *         )
     *     )
     *
     */
