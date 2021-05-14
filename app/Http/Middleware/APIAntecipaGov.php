<?php

namespace App\Http\Middleware;

use Closure;

class APIAntecipaGov
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!backpack_user()->hasPermissionTo('usuario_consulta_api')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}