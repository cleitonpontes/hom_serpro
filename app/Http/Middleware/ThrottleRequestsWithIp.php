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

        $ipurl = '207.64.1.64';
//        207.64.1.68/28
        $todosIps = [];
        foreach(Ipsacesso::all() as $ipsJson) {
            $arrIps = json_decode($ipsJson->ips);
            foreach($arrIps as $ip){
                $split = explode('/', $ip->name);
                if (count($split) > 1) {
                    $retornoArrIps = $this->cidrToRange($ip->name);
                    dd($retornoArrIps);
                    foreach($retornoArrIps as $ip){
                        array_push($todosIps, $ip);
                    }
                }else{
                    array_push($todosIps, $ip->name);
                }
            }
        }

//        if(!in_array($request->ip(), $todosIps)) {
        if(!in_array($ipurl, $todosIps)) {
            abort('403', config('app.erro_permissao'));
        }
        return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
    }

    public function cidrToRange($value) {
        $range = array();
        $split = explode('/', $value);
        if (!empty($split[0]) && is_scalar($split[1]) && filter_var($split[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $rangeStart = ip2long($split[0]) & ((-1 << (32 - (int)$split[1])));
            $rangeStartIP = long2ip($rangeStart);
            $rangeEnd = ip2long($rangeStartIP) + pow(2, (32 - (int)$split[1])) - 1;

            for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
                $range[] = long2ip($i);
            }
            return $range;
        } else {
            return $value;
        }
    }
}
