<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/inicio');
});

Route::get('/home', function () {
    return redirect('/inicio');
});


Route::group(
    [
        'middleware' => 'web',
        'prefix' => config('backpack.base.route_prefix'),
    ],
    function () {
        // if not otherwise configured, setup the auth routes
        if (config('backpack.base.setup_auth_routes')) {
            // Authentication Routes...
            Route::get('login', 'Auth\LoginController@showLoginForm')->name('backpack.auth.login');
            Route::post('login', 'Auth\LoginController@login');
            Route::get('logout', 'Auth\LoginController@logout')->name('backpack.auth.logout');
            Route::post('logout', 'Auth\LoginController@logout');

            // Registration Routes...
            Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('backpack.auth.register');
            Route::post('register', 'Auth\RegisterController@register');

            // Password Reset Routes...
            Route::get('password/reset',
                'Auth\ForgotPasswordController@showLinkRequestForm')->name('backpack.auth.password.reset');
            Route::post('password/reset', 'Auth\ResetPasswordController@reset');
            Route::get('password/reset/{token}',
                'Auth\ResetPasswordController@showResetForm')->name('backpack.auth.password.reset.token');
            Route::post('password/email',
                'Auth\ForgotPasswordController@sendResetLinkEmail')->name('backpack.auth.password.email');
        }

        // if not otherwise configured, setup the dashboard routes
        if (config('backpack.base.setup_dashboard_routes')) {
            Route::get('dashboard', function () {
                return redirect('/inicio');
            });
//            Route::get('dashboard', 'AdminController@dashboard')->name('backpack.dashboard');
            Route::get('/', 'AdminController@redirect')->name('backpack');
        }

        // if not otherwise configured, setup the "my account" routes
        if (config('backpack.base.setup_my_account_routes')) {

            //meus dados
            Route::get('/meus-dados', 'AdminController@meusdados')->name('inicio.meusdados');
            Route::put('/meus-dados/atualiza', 'AdminController@meusdadosatualiza')->name('inicio.meusdados.atualiza');

            Route::get('/mudar-ug', 'AdminController@mudarug')->name('inicio.mudarug');
            Route::put('/mudaug', 'AdminController@mudaug')->name('inicio.mudaug');

            Route::get('edit-account-info', function () {
                return redirect('/meus-dados');
            });
//            Route::get('edit-account-info',
//                'Auth\MyAccountController@getAccountInfoForm')->name('backpack.account.info');
//            Route::post('edit-account-info', 'Auth\MyAccountController@postAccountInfoForm');
            Route::get('alterar-senha',
                'Auth\MyAccountController@getChangePasswordForm')->name('alterar.senha');
            Route::post('alterar-senha', 'Auth\MyAccountController@postChangePasswordForm');
        }
    });
