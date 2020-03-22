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
            Route::get('unidade', 'UnidadeController@index');
            Route::get('unidade/{id}', 'UnidadeController@show');
            Route::get('catmatsergrupo', 'CatmatsergrupoController@index');
            Route::get('empecatmatsergrupo/{id}', 'CatmatsergrupoController@show');
            Route::get('catmatseritem', 'CatmatseritemController@index');
            Route::get('empecatmatseritem/{id}', 'CatmatseritemController@show');
            Route::get('orgaosubcategoria', 'OrgaosubcategoriaController@index');
            Route::get('orgaosubcategoria/{id}', 'OrgaosubcategoriaController@show');
            Route::get('ocorrenciaconcluida', 'OcorrenciaconcluidaController@index');
            Route::get('ocorrenciaconcluida/{id}', 'OcorrenciaconcluidaController@show');
        });

// if not otherwise configured, setup the dashboard routes
        if (config('backpack.base.setup_dashboard_routes')) {
            Route::get('inicio', 'AdminController@index')->name('backpack.inicio');
            Route::get('/', 'AdminController@redirect')->name('backpack');
            Route::get('/dashboard', 'AdminController@redirect')->name('backpack');
        }


        Route::get('/storage/comunica/anexos/{file}', 'DownloadsController@anexoscomunica');


        Route::get('/mensagens', 'AdminController@listaMensagens');
        Route::get('/mensagem/{id}', 'AdminController@lerMensagem');

//        Route::get('/admin/phpinfo', 'AdminController@phpInfo');


        Route::group([
            'prefix' => 'painel',
            'namespace' => 'Painel'
        ], function (){
            Route::get('financeiro', 'FinanceiroController@index')->name('painel.financeiro');
            Route::get('orcamentario', 'OrcamentarioController@index')->name('painel.orcamentario');
        });

        Route::group([
            'prefix' => 'admin',
            'namespace' => 'Admin',
        ], function () {
            CRUD::resource('activitylog', 'ActivitylogCrudController');
            CRUD::resource('usuario', 'UsuarioCrudController');
            CRUD::resource('usuarioorgao', 'UsuarioOrgaoCrudController');
            CRUD::resource('usuariounidade', 'UsuarioUnidadeCrudController');
            CRUD::resource('orgaosuperior', 'OrgaoSuperiorCrudController');
            CRUD::resource('orgao', 'OrgaoCrudController');
            CRUD::resource('unidade', 'UnidadeCrudController');
            CRUD::resource('administradorunidade', 'UnidadeAdministradorUnidadeCrudController');
            CRUD::resource('codigo', 'CodigoCrudController');
            CRUD::resource('sfcertificado', 'SfcertificadoCrudController');
            CRUD::resource('justificativafatura', 'JustificativafaturaCrudController');
            CRUD::resource('tipolistafatura', 'TipolistafaturaCrudController');
            CRUD::resource('catmatseratualizacao', 'CatmatseratualizacaoCrudController');
            CRUD::resource('comunica', 'ComunicaCrudController');

            // Exportações Downloads
            Route::get('downloadapropriacao/{type}', 'ExportController@downloadapropriacao')
                ->name('apropriacao.download');

            Route::get('downloadexecucaoporempenho/{type}', 'ExportController@downloadExecucaoPorEmpenho')
                ->name('execucaoporempenho.download');

            Route::get('downloadlistatodoscontratos/{type}', 'ExportController@downloadListaTodosContratos')
                ->name('listatodoscontratos.download');

            Route::get('downloadlistacontratosorgao/{type}', 'ExportController@downloadListaContratosOrgao')
                ->name('listacontratosorgao.download');

            Route::get('downloadlistacontratosug/{type}', 'ExportController@downloadListaContratosUg')
                ->name('listacontratosug.download');

            Route::group(['prefix' => 'codigo/{codigo_id}'], function () {
                CRUD::resource('codigoitem', 'CodigoitemCrudController');
            });

            Route::group(['prefix' => 'unidade/{unidade_id}'], function () {
                CRUD::resource('configuracao', 'UnidadeconfiguracaoCrudController');
            });

            Route::group(['prefix' => 'orgao/{orgao_id}'], function () {
                CRUD::resource('subcategorias', 'OrgaoSubcategoriaCrudController');
                CRUD::resource('configuracao', 'OrgaoconfiguracaoCrudController');
            });

            Route::get('migracaoconta/{orgaoconfiguracao_id}', 'MigracaoSistemaContaController@index');

            Route::get('/rotinaalertamensal', 'UnidadeCrudController@executaRotinaAlertaMensal');

            Route::get('/atualizaorgaosuperior', 'OrgaoSuperiorCrudController@executaAtualizacaoCadastroOrgaoSuperior');
            Route::get('/atualizaorgao', 'OrgaoCrudController@executaAtualizacaoCadastroOrgao');
            Route::get('/atualizaunidade', 'UnidadeCrudController@executaAtualizacaoCadastroUnidade');

        });

        Route::group([
            'prefix' => 'gescon',
            'namespace' => 'Gescon',
        ], function () {

            CRUD::resource('contrato', 'ContratoCrudController');
            CRUD::resource('subrogacao', 'SubrogacaoCrudController');
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
                CRUD::resource('prepostos', 'ContratoprepostoCrudController');
                Route::get('extrato', 'ContratoCrudController@extratoPdf');
            });

            Route::group(['prefix' => 'contratohistorico/{contratohistorico_id}'], function () {
                CRUD::resource('itens', 'SaldohistoricoitemCrudController');
            });

            Route::get('/saldohistoricoitens/carregaritens/{tipo}/{contratohistorico_id}', 'SaldohistoricoitemCrudController@carregarItens');


            Route::group(['prefix' => 'meus-contratos/{contrato_id}'], function () {
                CRUD::resource('terceirizados', 'ContratoterceirizadoCrudController');
                CRUD::resource('ocorrencias', 'ContratoocorrenciaCrudController');
                CRUD::resource('faturas', 'ContratofaturaCrudController');
            });

//            Route::get('/notificausers', 'ContratoCrudController@notificaUsers');

        });

        Route::group([
            'prefix' => 'execfin',
            'namespace' => 'Execfin',
        ], function () {

            CRUD::resource('empenho', 'EmpenhoCrudController');
            CRUD::resource('situacaosiafi', 'ExecsfsituacaoCrudController');
            CRUD::resource('rhsituacao', 'RhsituacaoCrudController');
            CRUD::resource('rhrubrica', 'RhrubricaCrudController');

            Route::get('/migracaoempenhos', 'EmpenhoCrudController@executaMigracaoEmpenho');
            Route::get('/atualizasaldosempenhos', 'EmpenhoCrudController@executaAtualizaSaldosEmpenhos');

            Route::group(['prefix' => 'empenho/{empenho_id}'], function () {
                CRUD::resource('empenhodetalhado', 'EmpenhodetalhadoCrudController');
            });
        });

        Route::group([
            'prefix' => 'relatorio',
            'namespace' => 'Relatorio',
        ], function () {
            Route::get('listatodoscontratos', 'RelContratoController@listaTodosContratos')->name('relatorio.listatodoscontratos');
            Route::get('filtrolistatodoscontratos', 'RelContratoController@filtroListaTodosContratos')->name('filtro.listatodoscontratos');

            Route::get('listacontratosorgao', 'RelContratoController@listaContratosOrgao')->name('relatorio.listacontratosorgao');
//            Route::get('filtrolistacontratosorgao', 'RelContratoController@filtroListaContratosOrgao')->name('filtro.listacontratosorgao');

            Route::get('listacontratosug', 'RelContratoController@listaContratosUg')->name('relatorio.listacontratosug');
//            Route::get('filtrolistacontratosug', 'RelContratoController@filtroListaContratosUg')->name('filtro.listacontratosug');
        });

    });
});
