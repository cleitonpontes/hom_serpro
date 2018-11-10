<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'sc'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    CRUD::resource('orgaosuperior', 'OrgaosuperiorCrudController');
    CRUD::resource('orgao', 'OrgaoCrudController');
    CRUD::resource('unidade', 'UnidadeCrudController');
    CRUD::resource('logactivity', 'LogactivityCrudController');
}); // this should be the absolute last line of this file
