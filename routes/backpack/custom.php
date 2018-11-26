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
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace' => 'App\Http\Controllers',
], function () { // custom admin routes

// if not otherwise configured, setup the dashboard routes
    if (config('backpack.base.setup_dashboard_routes')) {
        Route::get('inicio', 'AdminController@index')->name('backpack.inicio');
        Route::get('/', 'AdminController@redirect')->name('backpack');
        Route::get('/dashboard', 'AdminController@redirect')->name('backpack');
    }
});

Route::prefix('admin')->group(function (){
    Route::group([
        'prefix' => config('backpack.base.route_prefix', 'admin'),
        'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
        'namespace' => 'App\Http\Controllers\Admin',
    ], function () { // custom admin routes

        CRUD::resource('usuario', 'UsuarioCrudController');
        CRUD::resource('activitylog', 'ActivitylogCrudController');

    });

});