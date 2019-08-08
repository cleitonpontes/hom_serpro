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

    //API Consulta Contratos
    Route::group([
        'prefix' => 'contrato',
    ], function (){
        Route::get('/ug/{unidade_codigo}', 'ContratoController@contratoAtivoPorUg');
        Route::get('/{contrato_id}/historico', 'ContratoController@historicoPorContratoId');
        Route::get('/{contrato_id}/empenhos', 'ContratoController@empenhosPorContratoId');
        Route::get('/{contrato_id}/cronograma', 'ContratoController@cronogramaPorContratoId');

    });




});
