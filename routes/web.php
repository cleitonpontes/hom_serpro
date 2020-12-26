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
  //teste
//Route::get('/tratardadosmigracaotseagu', 'MigracaotseaguController@tratardadosmigracaotseagu')->name('tratardadosmigracaotseagu');

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

Route::group([
    'prefix' => 'acessogov',
    'namespace' => 'Acessogov',
], function () {
    Route::get('/autorizacao', 'LoginAcessoGov@autorizacao')->name('acessogov.autorizacao');
    Route::get('/tokenacesso', 'LoginAcessoGov@tokenAcesso')->name('acessogov.tokenacesso');
});

Route::group([
    'prefix' => 'publicacao',
    'namespace' => 'Publicacao',
], function () {
//    Route::get('/imprensa', 'SoapController@consulta')->name('so.imprensa');
    Route::get('/consulta-feriado', 'DiarioOficialClass@consultaTodosFeriado')->name('soap.consulta.feriado');
    Route::get('/consulta-situacao', 'DiarioOficialClass@executaJobAtualizaSituacaoPublicacao')->name('soap.consulta.situacao');
    Route::get('/oficio-preview/{contrato_id}', 'DiarioOficialClass@oficioPreview')->name('soap.oficio.preview');
    Route::get('/oficio-preview-novo/{contrato_id?}', 'DiarioOficialClass@oficioPreviewNovo')->name('soap.oficio.preview');
});


Route::get('/storage/contrato/{pasta}/{file}', 'DownloadsController@contrato');

Route::get('/test/job', 'TestController@contrato');



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
            'namespace' => 'Apropriacao',
            'middleware' => 'auth',
            // 'middleware' = 'permission:apropriacao_fatura'
        ], function () {
            Route::get('/fatura', 'FaturaController@index')->name('apropriacao.faturas');                                               // Pronto
            Route::get('/contrato/{contrato}/fatura/{fatura}/nova', 'FaturaController@create')->name('apropriacao.fatura.create');      // Pronto
            Route::put('/fatura/novas', 'FaturaController@createMany')->name('apropriacao.fatura.create.bulk');                         // Pronto
            Route::delete('/fatura/{apropriacaoFatura}', 'FaturaController@destroy');                                                   // Pronto
            Route::get('/fatura/{id}', 'FaturaController@show')->name('apropriacao.fatura');                                            // ...
            Route::get('/fatura/{id}/manual', 'FaturaController@editar')->name('apropriacao.fatura.editar');                            // ...
            Route::get('/fatura/{apropriacaoFatura}/dochabil', 'FaturaController@documentoHabil')->name('apropriacao.fatura.dochabil'); // Pronto
        });

        // Módulo Empenho
        Route::group([
            'prefix' => 'empenho',
            'namespace' => 'Empenho\\',
            'as' => 'empenho.',
            'middleware' => ['auth', 'permission:empenho_minuta_acesso', 'verify.step.empenho'],
        ], function () {

            /**
             *
             * Minuta Empenho - Genéricos
             *
             **/

            CRUD::resource('/minuta', 'MinutaEmpenhoCrudController');

            Route::get('minuta/{minuta_id}/atualizarsituacaominuta', 'MinutaEmpenhoCrudController@executarAtualizacaoSituacaoMinuta')
                ->name('minuta.atualizar.situacao');

            //passo 1
            Route::get('buscacompra', 'CompraSiasgCrudController@create')
                ->name('minuta.etapa.compra');

            Route::post('buscacompra', 'CompraSiasgCrudController@store');

            //passo 2
            Route::get('fornecedor/{minuta_id}', 'FornecedorEmpenhoController@index')
                ->name('minuta.etapa.fornecedor');

            //passo 3
            Route::get('item/{minuta_id}/{fornecedor_id}', 'FornecedorEmpenhoController@item')
                ->name('minuta.etapa.item');

            Route::post('item', 'FornecedorEmpenhoController@store')
                ->name('minuta.etapa.item.store');

            Route::put('item', 'FornecedorEmpenhoController@update')
                ->name('minuta.etapa.item.update');

            //passo 4
            Route::get('saldo/{minuta_id}', 'SaldoContabilMinutaController@index')
                ->name('minuta.etapa.saldocontabil');

            Route::get('saldo/gravar/{minuta_id}', 'SaldoContabilMinutaController@store')
                ->name('minuta.gravar.saldocontabil');

            Route::post('saldo/gravar/saldo/minuta', 'SaldoContabilMinutaController@atualizaMinuta')
                ->name('minuta.atualizar.saldo');

            Route::post('saldo/inserir/celula', 'SaldoContabilMinutaController@inserirCelulaOrcamentaria')
                ->name('saldo.inserir.modal');

            //passo 5
            Route::get('subelemento/{minuta_id}', 'SubelementoController@index')
                ->name('minuta.etapa.subelemento');
            /*Route::get('subelemento/{minuta_id}/edit', 'SubelementoController@index')
                ->name('minuta.etapa.subelemento.edit');*/
            Route::post('subelemento', 'SubelementoController@store')
                ->name('subelemento.store');
            Route::put('subelemento', 'SubelementoController@update')
                ->name('subelemento.update');

            //passo 6

            Route::post('minuta/inserir/fornecedor', 'MinutaEmpenhoCrudController@inserirFornecedorModal')
                ->name('minuta.inserir.fornecedor');

            //passo 7
            CRUD::resource('passivo-anterior', 'ContaCorrentePassivoAnteriorCrudController', ['except' => ['create', 'show']]);

            Route::get('passivo-anterior/{minuta_id}', 'ContaCorrentePassivoAnteriorCrudController@create')
                ->name('minuta.etapa.passivo-anterior');

            //alteracao minuta
            Route::group(['prefix' => 'minuta/{minuta_id}'], function () {
                CRUD::resource('alteracao', 'MinutaAlteracaoCrudController');
                Route::get('alteracao-dt', 'MinutaAlteracaoCrudController@ajax')->name('crud.alteracao.ajax');
            });

        });

        Route::get('/tags', function () {
            return view('tags');
        });
        Route::get('/tags/find', 'Select2Ajax\TagController@find');
    }
);
