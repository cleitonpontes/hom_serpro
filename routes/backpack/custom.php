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
    'prefix' => config('backpack.base.route_prefix', ''),
    'middleware' => ['web', config('backpack.base.middleware_key', '')],
    'namespace' => 'App\Http\Controllers',
], function () { // custom admin routes

// if not otherwise configured, setup the dashboard routes
    if (config('backpack.base.setup_dashboard_routes')) {
        Route::get('inicio', 'AdminController@index')->name('backpack.inicio');
        Route::get('/', 'AdminController@redirect')->name('backpack');
        Route::get('/dashboard', 'AdminController@redirect')->name('backpack');
    }

    CRUD::resource('activitylog', 'ActivitylogCrudController');
});

Route::prefix('admin')->group(function (){
    Route::group([
        'prefix' => config('backpack.base.route_prefix', 'admin'),
        'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
        'namespace' => 'App\Http\Controllers\Admin',
    ], function () { // custom admin routes

        CRUD::resource('usuario', 'UsuarioCrudController');
        CRUD::resource('orgaosuperior', 'OrgaoSuperiorCrudController');
        CRUD::resource('orgao', 'OrgaoCrudController');
        CRUD::resource('unidade', 'UnidadeCrudController');
        CRUD::resource('codigo', 'CodigoCrudController');

        Route::group(['prefix' => 'codigo/{codigo_id}'], function()
        {
            CRUD::resource('codigoitem', 'CodigoitemCrudController');
        });
    });
});

Route::prefix('gescon')->group(function (){
    Route::group([
        'prefix' => config('backpack.base.route_prefix', ''),
        'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
        'namespace' => 'App\Http\Controllers\Gescon',
    ], function () { // custom admin routes

        CRUD::resource('contrato', 'ContratoCrudController');
        CRUD::resource('fornecedor', 'FornecedorCrudController');
        Route::group(['prefix' => 'contrato/{contrato_id}'], function()
        {
            CRUD::resource('responsaveis', 'ContratoresponsavelCrudController');
        });
        Route::group(['prefix' => 'contrato/{contrato_id}'], function()
        {
            CRUD::resource('garantias', 'ContratogarantiaCrudController');
        });
    });
});
