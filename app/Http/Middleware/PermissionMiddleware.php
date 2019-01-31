<?php

namespace App\Http\Middleware;

use Closure;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (backpack_user()->can($permission)) {
            return $next($request);
        } else {
            abort('403', config('app.erro_permissao'));
        }

    }
}
