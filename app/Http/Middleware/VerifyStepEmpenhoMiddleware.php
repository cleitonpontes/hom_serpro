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
        'empenho.crud./minuta.edit' => 6,
        'empenho.minuta.etapa.passivo-anterior' => 7,
        'empenho.crud./minuta.show' => 8
    ];

    public function handle($request, Closure $next)
    {

        if (array_key_exists(Route::current()->action['as'], $this->rotas)) {

            $minuta_id = Route::current()->parameter('minuta_id') ?? Route::current()->parameter('minutum');

            $minuta = MinutaEmpenho::find($minuta_id);
            session(['empenho_etapa' => $minuta->etapa]);
            session(['fornecedor_compra' => $minuta->fornecedor_compra_id]);
            if ($minuta->etapa >= $this->rotas[Route::current()->action['as']]
                || ($minuta->etapa === 2 && $this->rotas[Route::current()->action['as']] === 3)
            ) {
                return $next($request);
            }
            dd($minuta->etapa, $this->rotas[Route::current()->action['as']]);

            dd(2);


//            $minuta_id = this->get
        }

        return $next($request);
    }
}
