<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'namespace' => 'Api',
], function () {

    //API Campos Transparência Index
    Route::get('transparenciaorgaos', 'ApiTransparenciaController@orgaos');
    Route::get('transparenciaunidades', 'ApiTransparenciaController@unidades');
    Route::get('transparenciafornecedores', 'ApiTransparenciaController@fornecedores');
    Route::get('transparenciacontratos', 'ApiTransparenciaController@contratos');


    //API Consulta Contratos
    Route::group([
        'prefix' => 'contrato',
    ], function (){
        Route::get('/', 'ContratoController@contratoAtivoAll');
        Route::get('/ug/{unidade_codigo}', 'ContratoController@contratoAtivoPorUg');
        Route::get('/orgao/{orgao}', 'ContratoController@contratoAtivoPorOrgao');

        Route::get('/inativo/ug/{unidade_codigo}', 'ContratoController@contratoInativoPorUg');
        Route::get('/inativo/orgao/{orgao}', 'ContratoController@contratoInativoPorOrgao');

        Route::get('/{contrato_id}/historico', 'ContratoController@historicoPorContratoId');

        Route::get('/{contrato_id}/garantias', 'ContratoController@garantiasPorContratoId');
        Route::get('/{contrato_id}/itens', 'ContratoController@itensPorContratoId');
        Route::get('/{contrato_id}/prepostos', 'ContratoController@prepostosPorContratoId');
        Route::get('/{contrato_id}/responsaveis', 'ContratoController@responsaveisPorContratoId');
        Route::get('/{contrato_id}/despesas_acessorias', 'ContratoController@despesasAcessoriasPorContratoId');
        Route::get('/{contrato_id}/faturas', 'ContratoController@faturasPorContratoId');
        Route::get('/{contrato_id}/ocorrencias', 'ContratoController@ocorrenciasPorContratoId');
        Route::get('/{contrato_id}/terceirizados', 'ContratoController@terceirizadosPorContratoId');
        Route::get('/{contrato_id}/arquivos', 'ContratoController@arquivosPorContratoId');

        Route::get('/{contrato_id}/empenhos', 'ContratoController@empenhosPorContratoId');
        Route::get('/empenhos', 'ContratoController@empenhosPorContratos');
        Route::get('/{contrato_id}/cronograma', 'ContratoController@cronogramaPorContratoId');
        Route::get('/orgaos', 'ContratoController@orgaosComContratosAtivos');
        Route::get('/unidades', 'ContratoController@unidadesComContratosAtivos');
    });

    Route::group([
        'prefix' => 'empenho',
    ], function (){
        Route::get('/ano/{ano}/ug/{unidade}', 'EmpenhoController@empenhosPorAnoUg');
        Route::get('/ug/{unidade}', 'EmpenhoController@empenhosPorUg');
        Route::put('/sem/contrato/e/{empenho}/f/{fornecedor}/c/{contrato}', 'EmpenhoController@gravaContratoEmpenho');
    });

    Route::group([
        'prefix' => 'cronograma',
    ], function (){
        Route::get('/ug/{unidade}', 'ContratocronogramaController@cronogramaPorUg');
    });

});
