<?php

namespace App\Http\Middleware;

use App\Models\ContaCorrentePassivoAnterior;
use App\Models\Codigoitem;
use App\Models\MinutaEmpenhoRemessa;
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
    public $rotas_minuta_original = [
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

    public $rotas_minuta_alteracao = [
        'empenho.crud.alteracao.create' => 1,
        'empenho.crud.alteracao.edit' => 1,
        'empenho.crud.alteracao.passivo-anterior' => 2,
        'empenho.crud.alteracao.passivo-anterior.edit' => 2,
        'empenho.crud.alteracao.show' => 3
    ];

    public function handle($request, Closure $next)
    {

        //SE A ROTA EXISTE NA LISTA DE ROTAS DA MINUTA ORIGINAL
        if (array_key_exists(Route::current()->action['as'], $this->rotas_minuta_original)) {
            //se for a rota 1 limpa tudo
            if ($this->rotas_minuta_original[Route::current()->action['as']] === 1) {
                session(['empenho_etapa' => '']);
                session(['conta_id' => '']);
                session(['fornecedor_compra' => '']);
                session(['fornecedor_cpf_cnpj_idgener' => '']);
                session(['situacao' => '']);
                session(['unidade_ajax_id' => '']);
                return $next($request);
            }
//            se for a rota 4
            if ($this->rotas_minuta_original[Route::current()->action['as']] === 1) {
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

            if ($minuta->situacao->descricao == 'ERRO') {
                $situacao = Codigoitem::wherehas('codigo', function ($q) {
                    $q->where('descricao', '=', 'Situações Minuta Empenho');
                })
                    ->where('descricao', 'EM ANDAMENTO')
                    ->first();
                $minuta->situacao_id = $situacao->id;
                $minuta->save();
            }

            if ($minuta && ($minuta->etapa >= $this->rotas_minuta_original[Route::current()->action['as']]
                    || ($minuta->etapa === 2 && $this->rotas_minuta_original[Route::current()->action['as']] === 3))
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

            if ($this->rotas_minuta_original[Route::current()->action['as']] === 8) {
                return $next($request);
            }

            return redirect()->route('empenho.crud./minuta.index')->withError('Não permitido');
        }

        //SE A ROTA EXISTE NA LISTA DE ROTAS DA MINUTA de Alteração
        if (array_key_exists(Route::current()->action['as'], $this->rotas_minuta_alteracao)) {
            $minuta_id = Route::current()->parameter('minuta_id');
            $minuta = MinutaEmpenho::find($minuta_id);
            $remessa_id = Route::current()->parameter('remessa')
                ?? $minuta->max_remessa;
            $remessa = MinutaEmpenhoRemessa::find($remessa_id);

            session(['empenho_etapa' => '']);
            session(['conta_id' => '']);
            session(['fornecedor_compra' => '']);
            session(['fornecedor_cpf_cnpj_idgener' => '']);
            session(['situacao' => '']);
            session(['unidade_ajax_id' => '']);
            session(['etapa' => '']);

            if ($this->rotas_minuta_alteracao[Route::current()->action['as']] === 1) {
                session(['situacao' => 'EM ANDAMENTO']);
                session(['empenho_etapa' => 1]);

                if (strpos(Route::current()->action['as'], 'create') !== false) {
                    if ($remessa->remessa === 0) {
                        return $next($request);
                    }

                    if ($remessa->situacao->descricao === 'ERRO' || $remessa->situacao->descricao === 'EM ANDAMENTO') {
                        return redirect(route('empenho.crud.alteracao.edit', [
                            'minuta_id' => $minuta_id,
                            'remessa' => $remessa->id,
                            'minuta' => $minuta_id
                        ]));
                    }
                }
            }
            if ($this->rotas_minuta_alteracao[Route::current()->action['as']] === 2) {
                session(['situacao' => 'EM ANDAMENTO']);
                session(['empenho_etapa' => 2]);

                //se for create
                if (strpos(Route::current()->action['as'], 'edit') === false) {
                    if (count($remessa->contacorrente()->get()) > 0) {
                        return redirect(route('empenho.crud.alteracao.passivo-anterior.edit', [
                            'minuta_id' => $minuta_id,
                            'remessa' => $remessa->id,
                        ]));
                    }
                    return $next($request);
                }
            }

            //caso a rota seja a 3
            session(['situacao' => $remessa->situacao->descricao]);
            session(['empenho_etapa' => 3]);
        }

        return $next($request);
    }
}
