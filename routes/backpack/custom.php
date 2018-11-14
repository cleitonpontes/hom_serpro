<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'sc'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    CRUD::resource('orgaosuperior', 'OrgaosuperiorCrudController')->middleware('permission:administracao_orgaosuperior_acesso');
    CRUD::resource('orgao', 'OrgaoCrudController');
    CRUD::resource('unidade', 'UnidadeCrudController');
    CRUD::resource('logactivity', 'LogactivityCrudController');
    CRUD::resource('usuario', 'UsuarioCrudController');
}); // this should be the absolute last line of this file

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'sc'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace' => 'App\Http\Controllers',
], function () { // custom admin routes

// if not otherwise configured, setup the dashboard routes
    if (config('backpack.base.setup_dashboard_routes')) {
        Route::get('inicio', 'AdminController@index')->name('backpack.inicio');
        Route::get('/', 'AdminController@redirect')->name('backpack');
        Route::get('/dashboard', 'AdminController@redirect')->name('backpack');
    }
}); // this should be the absolute last line of this file