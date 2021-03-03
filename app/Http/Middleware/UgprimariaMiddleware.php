<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class UgprimariaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (backpack_user()) {

            if (!session()->get('user_ug') AND !session()->get('user_ug_id')) {
                if (backpack_user()->ugprimaria) {
                    $unidade = backpack_user()->unidadeprimaria(backpack_user()->ugprimaria);
                    if ($unidade) {
                        session(['user_ug' => $unidade->codigo]);
                        session(['user_ug_id' => $unidade->id]);
                        session(['user_orgao_id' => $unidade->orgao_id]);
                    } else {
                        session(['user_ug' => null]);
                        session(['user_ug_id' => null]);
                        session(['user_orgao_id' => null]);
                    }

                } else {
                    session(['user_ug' => null]);
                    session(['user_ug_id' => null]);
                }
            } elseif (session()->get('user_ug') == null AND session()->get('user_ug_id') == null) {
                if (backpack_user()->ugprimaria) {
                    $unidade = backpack_user()->unidadeprimaria(backpack_user()->ugprimaria);
                    if ($unidade) {
                        session(['user_ug' => $unidade->codigo]);
                        session(['user_ug_id' => $unidade->id]);
                        session(['user_orgao_id' => $unidade->orgao_id]);
                    } else {
                        session(['user_ug' => null]);
                        session(['user_ug_id' => null]);
                        session(['user_orgao_id' => null]);
                    }
                } else {
                    session(['user_ug' => null]);
                    session(['user_ug_id' => null]);
                    session(['user_orgao_id' => null]);
                }
            } else {
                // se chegou aqui é porque tem o user_ug e user_ug_id - se for adm, vamos deixar passar - mvascs@gmail.com.
                if (backpack_user()->hasRole('Administrador')) {
                    $ok = true;
                } else {
                    $ok = backpack_user()->havePermissionUg(session()->get('user_ug_id'));
                }
                if ($ok == false) {
                    \Session::flush();
                    backpack_auth()->logout();
                    return Redirect::to('/inicio');
                }
            }
        }

        return $next($request);
    }
}
