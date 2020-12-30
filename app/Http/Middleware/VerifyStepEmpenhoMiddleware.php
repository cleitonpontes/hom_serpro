<?php

namespace App\Http\Middleware;

use App\Models\ContaCorrentePassivoAnterior;
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
        'empenho.minuta.etapa.compra' => 1,
        'empenho.minuta.etapa.fornecedor' => 2,
        'empenho.minuta.etapa.item' => 3,
        'empenho.minuta.etapa.saldocontabil' => 4,
        'empenho.minuta.etapa.subelemento' => 5,
//        'empenho.minuta.etapa.subelemento.edit' => 5,
        'empenho.crud./minuta.edit' => 6,
        'empenho.minuta.etapa.passivo-anterior' => 7,
        'empenho.crud.passivo-anterior.edit' => 7,
        'empenho.crud./minuta.show' => 8
    ];

    public function handle($request, Closure $next)
    {

        //se a rota existe na lista de rotas
        if (array_key_exists(Route::current()->action['as'], $this->rotas)) {
            //se for a rota 1 limpa tudo
            if ($this->rotas[Route::current()->action['as']] === 1) {
                session(['empenho_etapa' => '']);
                session(['conta_id' => '']);
                session(['fornecedor_compra' => '']);
                session(['fornecedor_cpf_cnpj_idgener' => '']);
                session(['situacao' => '']);
                session(['unidade_ajax_id' => '']);
                return $next($request);
            }
//            se for a rota 4
            if ($this->rotas[Route::current()->action['as']] === 1) {
                session(['unidade_ajax_id' => '']);
            }

            $minuta_id = Route::current()->parameter('minuta_id')
                ?? Route::current()->parameter('minutum');

            if (is_null($minuta_id)) {
                $conta = ContaCorrentePassivoAnterior::find(Route::current()->parameter('passivo_anterior'));
                $minuta_id = $conta->minutaempenho_id;
                session(['conta_id' => $conta->id]);
            } else {
                $conta = ContaCorrentePassivoAnterior::where('minutaempenho_id', $minuta_id)->first();

                session(['conta_id' => '']);

                if ($conta) {
                    session(['conta_id' => $conta->id]);
                }
            }
            $minuta = MinutaEmpenho::find($minuta_id);

            if ($minuta && ($minuta->etapa >= $this->rotas[Route::current()->action['as']]
                    || ($minuta->etapa === 2 && $this->rotas[Route::current()->action['as']] === 3))
            ) {
                session(['minuta_id' => $minuta->id]);
                session(['empenho_etapa' => $minuta->etapa]);
                session(['fornecedor_compra' => $minuta->fornecedor_compra_id]);
                session([
                    'fornecedor_cpf_cnpj_idgener' => $minuta->fornecedor_empenho_cpfcnpjidgener_sessao
                ]);
                session(['situacao' => $minuta->situacao->descricao]);

                return $next($request);
            }

            session(['minuta_id' => '']);
            session(['empenho_etapa' => '']);
            session(['fornecedor_compra' => '']);
            session(['fornecedor_cpf_cnpj_idgener' => '']);
            session(['conta_id' => '']);
            session(['situacao' => '']);

            if ($this->rotas[Route::current()->action['as']] === 8) {
                return $next($request);
            }

            return redirect()->route('empenho.crud./minuta.index')->withError('Não permitido');
        }

        return $next($request);
    }
}
