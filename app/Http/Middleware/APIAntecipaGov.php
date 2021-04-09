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
<<<<<<< HEAD
    {

        if (!backpack_user()->hasPermissionTo('usuario_consulta_api')) {
=======
    {   
        
        if (!backpack_user()->hasPermissionTo('usuario_inserir')) {
>>>>>>> 109684bbe19df8942143e44f3c5370dddb0749ce
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
