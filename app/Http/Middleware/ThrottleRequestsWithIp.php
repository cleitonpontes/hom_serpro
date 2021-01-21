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
        $ipsCadastrado = Ipsacesso::whereJsonContains('ips', [['name' =>$request->ip()]])->get()->toArray();

        if(empty($ipsCadastrado)) {
            abort('403', config('app.erro_permissao'));
        }
        return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
    }

    public function cidrToRange($value) {
        $range = array();
        $split = explode('/', $value);
        if (!empty($split[0]) && is_scalar($split[1]) && filter_var($split[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $rangeStart = ip2long($split[0]) & ((-1 << (32 - (int)$split[1])));
            $rangeEnd = ip2long($split[0]) + pow(2, (32 - (int)$split[1])) - 1;

            for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
                $range[] = long2ip($i);
            }
            return $range;
        } else {
            return $value;
        }
    }
}
