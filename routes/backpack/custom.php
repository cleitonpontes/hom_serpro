<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

//Route::group([
//    'prefix'     => config('backpack.base.route_prefix', ''),
//    'middleware' => ['web', config('backpack.base.middleware_key', '')],
//    'namespace'  => 'App\Http\Controllers\Admin',
//], function () { // custom admin routes
//}); // this should be the absolute last line of this file

Route::group([
//    'prefix' => config('backpack.base.route_prefix', ''),
    'middleware' => ['web', config('backpack.base.middleware_key', '')],
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::group(['middleware' => 'ugprimaria'], function () {

        Route::group([
            'prefix' => 'api',
            'namespace' => 'Api',
        ], function () {
            //busca empenhos via ajax
            Route::get('empenho', 'EmpenhoController@index');
            Route::get('empenho/{id}', 'EmpenhoController@show');
        });

// if not otherwise configured, setup the dashboard routes
        if (config('backpack.base.setup_dashboard_routes')) {
            Route::get('inicio', 'AdminController@index')->name('backpack.inicio');
            Route::get('/', 'AdminController@redirect')->name('backpack');
            Route::get('/dashboard', 'AdminController@redirect')->name('backpack');
        }

        Route::get('/storage/contrato/{pasta}/{file}', 'DownloadsController@contrato');





        Route::group([
            'prefix' => 'admin',
            'namespace' => 'Admin',
        ], function () {
            CRUD::resource('activitylog', 'ActivitylogCrudController');
            CRUD::resource('usuario', 'UsuarioCrudController');
            CRUD::resource('orgaosuperior', 'OrgaoSuperiorCrudController');
            CRUD::resource('orgao', 'OrgaoCrudController');
            CRUD::resource('unidade', 'UnidadeCrudController');
            CRUD::resource('codigo', 'CodigoCrudController');
            CRUD::resource('sfcertificado', 'SfcertificadoCrudController');
            CRUD::resource('justificativafatura', 'JustificativafaturaCrudController');
            CRUD::resource('tipolistafatura', 'TipolistafaturaCrudController');
            CRUD::resource('catmatseratualizacao', 'CatmatseratualizacaoCrudController');

            // Download apropriação
            Route::get('downloadapropriacao/{type}', 'ExportController@downloadapropriacao')
                ->name('apropriacao.download');

            Route::group(['prefix' => 'codigo/{codigo_id}'], function () {
                CRUD::resource('codigoitem', 'CodigoitemCrudController');
            });
        });

        Route::group([
            'prefix' => 'gescon',
            'namespace' => 'Gescon',
        ], function () {

            CRUD::resource('contrato', 'ContratoCrudController');
            CRUD::resource('meus-contratos', 'MeucontratoCrudController');
            CRUD::resource('fornecedor', 'FornecedorCrudController');

            Route::group(['prefix' => 'contrato/{contrato_id}'], function () {
                CRUD::resource('responsaveis', 'ContratoresponsavelCrudController');
                CRUD::resource('garantias', 'ContratogarantiaCrudController');
                CRUD::resource('arquivos', 'ContratoarquivoCrudController');
                CRUD::resource('empenhos', 'ContratoempenhoCrudController');
                CRUD::resource('historico', 'ContratohistoricoCrudController');
                CRUD::resource('cronograma', 'ContratocronogramaCrudController');
                CRUD::resource('instrumentoinicial', 'InstrumentoinicialCrudController');
                CRUD::resource('aditivos', 'AditivoCrudController');
                CRUD::resource('apostilamentos', 'ApostilamentoCrudController');
                CRUD::resource('itens', 'ContratoitemCrudController');
                Route::get('extrato', 'ContratoCrudController@extratoPdf');
            });

            Route::group(['prefix' => 'meus-contratos/{contrato_id}'], function () {
                CRUD::resource('terceirizados', 'ContratoterceirizadoCrudController');
                CRUD::resource('ocorrencias', 'ContratoocorrenciaCrudController');
                CRUD::resource('faturas', 'ContratofaturaCrudController');
            });

        });

        Route::group([
            'prefix' => 'execfin',
            'namespace' => 'Execfin',
        ], function () {

            CRUD::resource('empenho', 'EmpenhoCrudController');
            CRUD::resource('situacaosiafi', 'ExecsfsituacaoCrudController');
            CRUD::resource('rhsituacao', 'RhsituacaoCrudController');
            CRUD::resource('rhrubrica', 'RhrubricaCrudController');

            Route::get('/migracaoempenhos', 'EmpenhoCrudController@migracaoEmpenho');

            Route::group(['prefix' => 'empenho/{empenho_id}'], function () {
                CRUD::resource('empenhodetalhado', 'EmpenhodetalhadoCrudController');
            });
        });

    });
});
