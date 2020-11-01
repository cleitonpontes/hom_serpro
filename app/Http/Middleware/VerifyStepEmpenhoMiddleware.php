<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Route;
use App\Models\MinutaEmpenho;

class VerifyStepEmpenhoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */

    /*
     * Rotas para verificação
     */
    public $rotas = [
        'empenho.minuta.etapa.fornecedor' => 2,
        'empenho.minuta.etapa.item' => 3,
        'empenho.minuta.etapa.saldocontabil' => 4,
        'empenho.minuta.etapa.subelemento' => 5,
        'empenho.minuta.etapa.passivo-anterior' => 7
    ];

    public function handle($request, Closure $next)
    {
        //rotas para verificação
//        if ($this->rotas[Route::current()->action['as']]) {
//        dd(2);
//        dump(Route::current());
//        dump(Route::current()->action['as']);
//        dump($request->method());
//        dd($request);

        if (array_key_exists(Route::current()->action['as'], $this->rotas)) {

            $minuta_id = Route::current()->parameter('minuta_id');

            $minuta = MinutaEmpenho::find($minuta_id);
//          dd($minuta->etapa);
            session(['empenho_etapa' => $minuta->etapa]);
            session(['empenho_etapa' => $minuta->etapa]);
            if ($minuta->etapa >= $this->rotas[Route::current()->action['as']]) {
                return $next($request);
            }
            dd($minuta->etapa , $this->rotas[Route::current()->action['as']]);

            dd(2);


//            $minuta_id = this->get
        }

        return $next($request);
    }
}
