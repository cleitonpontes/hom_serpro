<?php

namespace App\Http\Middleware;

use App\Models\Ipsacesso;

use Closure;

class ThrottleRequestsWithIp extends \Illuminate\Routing\Middleware\ThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        // $ipsCadastrado = Ipsacesso::whereJsonContains('ips', [['name' =>$request->ip()]])->get()->toArray();
        $ipsCadastrado = Ipsacesso::whereJsonContains('ips', [['name' =>$request->ip()]]);
        dd($ipsCadastrado->toSql());
        
        if(empty($ipsCadastrado)) {
            abort('403', config('app.erro_permissao'));
        }
        return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
    }
}
