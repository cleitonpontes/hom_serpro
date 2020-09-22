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

Route::group([
    'prefix' => 'transparencia',
    'namespace' => 'Transparencia',
], function () {
    Route::get('/', 'IndexController@index')->name('transparencia.index');
    CRUD::resource('/contratos', 'ConsultaContratosCrudController')->name('transparencia.consulta.contratos');
    CRUD::resource('/faturas', 'ConsultaFaturasCrudController')->name('transparencia.consulta.faturas');
    CRUD::resource('/terceirizados', 'ConsultaTerceirizadosCrudController')->name('transparencia.consulta.terceirizados');
});

Route::get('/storage/contrato/{pasta}/{file}', 'DownloadsController@contrato');

Route::group(
    [
        'middleware' => ['web'],
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
            // Route::get('dashboard', 'AdminController@dashboard')->name('backpack.dashboard');
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
            // Route::get('edit-account-info',
            //     'Auth\MyAccountController@getAccountInfoForm')->name('backpack.account.info');
            // Route::post('edit-account-info', 'Auth\MyAccountController@postAccountInfoForm');
            Route::get('alterar-senha',
                'Auth\MyAccountController@getChangePasswordForm')->name('alterar.senha');
            Route::post('alterar-senha', 'Auth\MyAccountController@postChangePasswordForm');
        }

        // Módulo Folha de Pagamento
        Route::group([
            'prefix' => 'folha',
            'namespace' => 'Folha\\',
            'as' => 'folha.',
        ], function () {

            /**
             *
             * Apropriação da Folha - Genéricos
             *
             */
            Route::get('/apropriacao', 'ApropriacaoController@index')
                ->name('apropriacao')
                ->middleware('permission:folha_apropriacao_acesso');
            Route::get('/apropriacao/remove', function () {
                return redirect('/folha/apropriacao');
            })
                ->name('apropriacao.excluir')
                ->middleware('permission:folha_apropriacao_excluir');
            Route::get('/apropriacao/remove/{id}', 'ApropriacaoController@remove')
                ->name('apropriacao.excluir.id')
                ->middleware('permission:folha_apropriacao_deletar');
            Route::get('/apropriacao/relatorio/{apid}', 'ApropriacaoController@relatorio')
                ->name('apropriacao.relatorio');

            /**
             *
             * Apropriação da Folha - Passos
             * {apid} = Apropriação ID
             * {id}   = Registro
             * {sit}  = Situação
             *
             */

            // Passo 1
            Route::get('/apropriacao/passo/1', 'Apropriacao\Passo1Controller@novo')
                ->name('apropriacao.passo.1')
                ->middleware('permission:folha_apropriacao_passo');
            Route::post('/apropriacao/passo/1/adiciona', 'Apropriacao\Passo1Controller@adiciona')
                ->name('apropriacao.passo.1.grava');

            // Passo 2
            Route::get('/apropriacao/passo/2/apid/{apid}', 'Apropriacao\Passo2Controller@index')
                ->name('apropriacao.passo.2')
                ->middleware('permission:folha_apropriacao_passo');
            Route::put('/apropriacao/situacao/{apid}/{id}/{sit}/{vpd}', 'Apropriacao\Passo2Controller@atualiza')
                ->name('apropriacao.passo.2.situacao.atualiza');
            Route::get('/apropriacao/passo/2/avanca/apid/{apid}', 'Apropriacao\Passo2Controller@avancaPasso')
                ->name('apropriacao.passo.2.avanca')
                ->middleware('permission:folha_apropriacao_passo');

            // Passo 3
            Route::get('/apropriacao/passo/3/apid/{apid}', 'Apropriacao\Passo3Controller@index')
                ->name('apropriacao.passo.3')
                ->middleware('permission:folha_apropriacao_passo');
            Route::put('/apropriacao/empenho/atualiza/{id}/{vr}', 'Apropriacao\Passo3Controller@atualiza')
                ->name('apropriacao.passo.3.situacao.atualiza');
            Route::get('/apropriacao/passo/3/avanca/apid/{apid}', 'Apropriacao\Passo3Controller@avancaPasso')
                ->name('apropriacao.passo.3.avanca')
                ->middleware('permission:folha_apropriacao_passo');

            // Passo 4
            Route::get('/apropriacao/passo/4/apid/{apid}', 'Apropriacao\Passo4Controller@index')
                ->name('apropriacao.passo.4')
                ->middleware('permission:folha_apropriacao_passo');
            Route::put('/apropriacao/empenho/saldo/{ug}/{ano}/{mes}/{empenho}/{subitem}', 'Apropriacao\Passo4Controller@atualiza')
                ->name('apropriacao.passo.4.empenho.saldo');
            Route::put('/apropriacao/empenho/saldo/todos/{apid}', 'Apropriacao\Passo4Controller@atualizaTodos')
                ->name('apropriacao.passo.4.empenho.saldo.todos');
            Route::get('/apropriacao/passo/4/avanca/apid/{apid}', 'Apropriacao\Passo4Controller@avancaPasso')
                ->name('apropriacao.passo.4.avanca')
                ->middleware('permission:folha_apropriacao_passo');

            // Passo 5
            Route::get('/apropriacao/passo/5/apid/{apid}', 'Apropriacao\Passo5Controller@edit')
                ->name('apropriacao.passo.5')
                ->middleware('permission:folha_apropriacao_passo');
            Route::put('/apropriacao/passo/5/salva', 'Apropriacao\Passo5Controller@update')
                ->name('apropriacao.passo.5.salva');
            Route::get('/apropriacao/passo/5/avanca/apid/{apid}', 'Apropriacao\Passo5Controller@avancaPasso')
                ->name('apropriacao.passo.5.avanca')
                ->middleware('permission:folha_apropriacao_passo');

            // Passo 6
            Route::get('/apropriacao/passo/6/apid/{apid}', 'Apropriacao\Passo6Controller@index')
                ->name('apropriacao.passo.6')
                ->middleware('permission:folha_apropriacao_passo');
            Route::get('/apropriacao/passo/6/avanca/apid/{apid}', 'Apropriacao\Passo6Controller@avancaPasso')
                ->name('apropriacao.passo.6.avanca')
                ->middleware('permission:folha_apropriacao_passo');

            Route::put('/apropriacao/persistir/{apid}/{dados}', 'Apropriacao\Passo6Controller@persistir')
                ->name('apropriacao.passo.6.situacao.persistir');

            // Passo 7
            Route::get('/apropriacao/passo/7/apid/{apid}', 'Apropriacao\Passo7Controller@gerarXml')
                ->name('apropriacao.passo.7')
                ->middleware('permission:folha_apropriacao_passo');

            // Apropriar SIAFI
            Route::get('/apropriacao/siafi/{apid}', 'ApropriacaoController@apropriaSiafi')
                ->name('apropriacao.siafi')
                ->middleware('permission:folha_apropriacao_passo');

            // Apropriar SIAFI
            Route::get('/apropriacao/siafi/dochabil/{apid}', 'ApropriacaoController@docHabilSiafi')
                ->name('apropriacao.siafi.dochabil')
                ->middleware('permission:folha_apropriacao_passo');
        });

        // Módulo Apropriação da Fatura
        Route::group([
            'prefix' => 'apropriacao',
            'namespace' => 'apropriacao',
            'middleware' => 'auth',
            // 'middleware' = 'permission:apropriacao_fatura_passos'
        ], function () {
            /**
             *
             * Apropriação da Fatura
             * {apid} = Apropriação ID
             * {id}   = Registro
             * {sit}  = Situação
             *
             */
            Route::get('/fatura', 'FaturaController@index')->name('apropriacao.faturas');
            Route::get('/fatura/{id}', 'FaturaController@show')->name('apropriacao.fatura');
            Route::get('/fatura/nova/{id}', 'FaturaController@create')->name('apropriacao.fatura.create');
            // TODO: Checar e continuar, se ok
            Route::put('/fatura/novas', 'FaturaController@create')->name('apropriacao.fatura.create');

            Route::get('/fatura/{id}/relatorio', 'FaturaController@show')->name('apropriacao.fatura.relatorio');
            // Route::delete('/fatura/{id}', 'ApropriacaoFaturaController@destroy')->name('apropriacao.fatura.excluir');
        });
    }
);
