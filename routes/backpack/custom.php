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
            Route::get('unidadecomorgao', 'UnidadeComOrgaoController@index');
            Route::get('unidadecomorgao/{id}', 'UnidadeComOrgaoController@show');
            Route::get('fornecedor', 'FornecedorController@index');
            Route::get('fornecedor/{id}', 'FornecedorController@show');
            Route::get('comprasiasg', 'ComprasiasgController@index');
            Route::get('comprasiasg/{id}', 'ComprasiasgController@show');
            Route::get('catmatsergrupo', 'CatmatsergrupoController@index');
            Route::get('empecatmatsergrupo/{id}', 'CatmatsergrupoController@show');
            Route::get('catmatseritem', 'CatmatseritemController@index');
            Route::get('empecatmatseritem/{id}', 'CatmatseritemController@show');
            Route::get('orgaosubcategoria', 'OrgaosubcategoriaController@index');
            Route::get('orgaosubcategoria/{id}', 'OrgaosubcategoriaController@show');
//            Route::get('ocorrenciaconcluida', 'OcorrenciaconcluidaController@index');
//            Route::get('ocorrenciaconcluida/{id}', 'OcorrenciaconcluidaController@show');
            Route::get('municipios', 'MunicipioController@index');
            Route::get('amparolegal', 'AmparoLegalController@index');
            Route::get('amparolegal/{id}', 'AmparoLegalController@show');
            Route::get('atualizasaldos/unidade/{cod_unidade}', 'SaldoContabilController@atualizaSaldosPorUnidade')->name('atualiza.saldos.unidade');
            Route::get('atualizasaldos/linha/{saldo_id}', 'SaldoContabilController@atualizaSaldosPorLinha')->name('atualiza.saldos.linha');
            Route::get('pupula/tabelas/siafi/{minuta_id}', 'MinutaEmpenhoController@populaTabelasSiafi')->name('popula.tabelas.siafi');
            Route::get('inserir/celula/modal/{cod_unidade}/{contacorrente}', 'SaldoContabilController@inserirCelulaOrcamentaria')->name('saldo.inserir.modal');
            Route::get('carrega/saldos/unidade/{cod_unidade}', 'SaldoContabilController@carregaSaldosPorUnidadeSiasg')->name('carrega.saldos.unidade');
            Route::get('minutaempenho', 'MinutaEmpenhoController@index');
            Route::get('novoempenho/{minuta_id}', 'MinutaEmpenhoController@novoEmpenhoMesmaCompra')->name('novo.empenho.compra');


        });

        // if not otherwise configured, setup the dashboard routes
        if (config('backpack.base.setup_dashboard_routes')) {
            Route::get('inicio', 'AdminController@index')->name('backpack.inicio');
            Route::get('/', 'AdminController@redirect')->name('backpack');
            Route::get('/dashboard', 'AdminController@redirect')->name('backpack');
        }

        Route::get('/storage/comunica/anexos/{file}', 'DownloadsController@anexoscomunica');
        Route::get('/storage/ocorrencia/{path}/{file}', 'DownloadsController@anexosocorrencia');


        Route::get('/storage/importacao/{path}/{file}', 'DownloadsController@importacao');


        Route::get('/mensagens', 'AdminController@listaMensagens');
        Route::get('/mensagem/{id}', 'AdminController@lerMensagem');

//        Route::get('/admin/phpinfo', 'AdminController@phpInfo');

        Route::group([
            'prefix' => 'painel',
            'namespace' => 'Painel'
        ], function () {
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
            CRUD::resource('importacao', 'ImportacaoCrudController');
            CRUD::resource('ipsacesso', 'IpsacessoCrudController');

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
            CRUD::resource('indicador', 'IndicadorCrudController');
            CRUD::resource('encargo', 'EncargoCrudController');

            Route::group([
                'prefix' => 'siasg',
                'namespace' => 'Siasg',
            ], function () {
                CRUD::resource('compras', 'SiasgcompraCrudController');
                CRUD::resource('contratos', 'SiasgcontratoCrudController');
                Route::get('apisiasg', 'SiasgcompraCrudController@apisiasg');
                Route::get('inserircompras', 'SiasgcompraCrudController@inserirComprasEmMassa');
                Route::get('inserircontratos', 'SiasgcontratoCrudController@verificarContratosPendentes');
                Route::get('/compras/{id}/atualizarsituacaocompra', 'SiasgcompraCrudController@executarAtualizacaoSituacaoCompra');
                Route::get('/contratos/{id}/atualizarsituacaocontrato', 'SiasgcontratoCrudController@executarAtualizacaoSituacaoContrato');
            });

            // início conta vinculada - contrato conta - mvascs@gmail.com
            Route::group(['prefix' => 'contrato/contratoconta/{contratoconta_id}'], function () {
                CRUD::resource('extratocontratoconta', 'ExtratocontratocontaCrudController');
                CRUD::resource('movimentacaocontratoconta', 'MovimentacaocontratocontaCrudController');
                CRUD::resource('depositocontratoconta', 'DepositocontratocontaCrudController');
                CRUD::resource('retiradacontratoconta', 'RetiradacontratocontaCrudController');
                CRUD::resource('funcionarioscontratoconta', 'FuncionarioscontratocontaCrudController');
                CRUD::resource('funcoescontratoconta', 'FuncoescontratocontaCrudController');
            });
            Route::group(['prefix' => 'contrato/contratoconta/{contratoconta_id}/{funcao_id}'], function () {
                CRUD::resource('repactuacaocontratoconta', 'RepactuacaocontratocontaCrudController');
            });
            Route::group(['prefix' => 'contrato/contratoconta/movimentacaocontratoconta/{movimentacaocontratoconta_id}'], function () {
                CRUD::resource('lancamento', 'LancamentoCrudController');
            });
            Route::group(['prefix' => 'contrato/contratoconta/contratoterceirizado/{contratoterceirizado_id}'], function () {
                CRUD::resource('retiradacontratoconta', 'RetiradacontratocontaCrudController');
            });
            // Route::group(['prefix' => 'movimentacao/{movimentacao_id}'], function () {
            //     Route::get('excluir', 'MovimentacaocontratocontaCrudController@excluirmovimentacao');
            // });
            Route::get('movimentacao/{movimentacao_id}/excluir', 'MovimentacaocontratocontaCrudController@excluirMovimentacao');
            // fim conta vinculada - contrato conta



            Route::group(['prefix' => 'contrato/{contrato_id}'], function () {
                CRUD::resource('contratocontas', 'ContratocontaCrudController'); // conta vinculada
                CRUD::resource('aditivos', 'AditivoCrudController');
                CRUD::resource('apostilamentos', 'ApostilamentoCrudController');
                CRUD::resource('arquivos', 'ContratoarquivoCrudController');
                CRUD::resource('cronograma', 'ContratocronogramaCrudController');
                CRUD::resource('despesaacessoria', 'ContratoDespesaAcessoriaCrudController');
                CRUD::resource('empenhos', 'ContratoempenhoCrudController');
                CRUD::resource('garantias', 'ContratogarantiaCrudController');
                CRUD::resource('historico', 'ContratohistoricoCrudController')->name('listar.historico');
                CRUD::resource('instrumentoinicial', 'InstrumentoinicialCrudController');
                CRUD::resource('itens', 'ContratoitemCrudController');
                CRUD::resource('padrao', 'ContratosfpadraoCrudController');
                CRUD::resource('prepostos', 'ContratoprepostoCrudController');
                CRUD::resource('responsaveis', 'ContratoresponsavelCrudController');
                CRUD::resource('rescisao', 'RescisaoCrudController');
                CRUD::resource('status', 'ContratostatusprocessoCrudController');
                Route::get('extrato', 'ContratoCrudController@extratoPdf');
            });

            Route::group(['prefix' => 'consulta/'], function () {
                CRUD::resource('arquivos', 'ConsultaarquivoCrudController');
                CRUD::resource('cronogramas', 'ConsultacronogramaCrudController');
                CRUD::resource('despesasacessorias', 'ConsultaDespesaAcessoriaCrudController');
                CRUD::resource('empenhos', 'ConsultaempenhoCrudController');
                CRUD::resource('faturas', 'ConsultafaturaCrudController');
                CRUD::resource('garantias', 'ConsultagarantiaCrudController');
                CRUD::resource('historicos', 'ConsultahistoricoCrudController');
                CRUD::resource('itens', 'ConsultaitemCrudController');
                CRUD::resource('ocorrencias', 'ConsultaocorrenciaCrudController');
                CRUD::resource('prepostos', 'ConsultaprepostoCrudController');
                CRUD::resource('responsaveis', 'ConsultaresponsavelCrudController');
                CRUD::resource('terceirizados', 'ConsultaterceirizadoCrudController');
            });

            Route::group(['prefix' => 'contratohistorico/{contratohistorico_id}'], function () {
                CRUD::resource('itens', 'SaldohistoricoitemCrudController');
            });

            Route::get('/saldohistoricoitens/carregaritens/{tipo}/{contratohistorico_id}', 'SaldohistoricoitemCrudController@carregarItens');

            Route::group(['prefix' => 'meus-contratos/{contrato_id}'], function () {
                CRUD::resource('faturas', 'ContratofaturaCrudController');
                CRUD::resource('ocorrencias', 'ContratoocorrenciaCrudController');
                CRUD::resource('servicos', 'ContratoServicoCrudController');
                CRUD::resource('terceirizados', 'ContratoterceirizadoCrudController');

            });
            Route::group(['prefix' => 'meus-servicos/{contrato_id}/{contratoitem_servico_id}']
                , function () {
                    CRUD::resource('indicadores', 'ContratoItemServicoIndicadorCrudController');
                    Route::group(['prefix' => '{cisi_id}'], function () {
                        CRUD::resource('glosas', 'GlosaCrudController');
                    });
                });

//            Route::get('/notificausers', 'ContratoCrudController@notificaUsers');
        });

        Route::group([
            'prefix' => 'execfin',
            'namespace' => 'Execfin',
        ], function () {

            CRUD::resource('empenho', 'EmpenhoCrudController');
            Route::get('incluirnovoempenho','EmpenhoCrudController@incluirEmpenhoSiafi');
            CRUD::resource('situacaosiafi', 'ExecsfsituacaoCrudController');
            CRUD::resource('rhsituacao', 'RhsituacaoCrudController');
            CRUD::resource('rhrubrica', 'RhrubricaCrudController');

//            Route::get('/migracaoempenhos', 'EmpenhoCrudController@executaMigracaoEmpenho');
            Route::get('/migracaoempenhos', 'EmpenhoCrudController@executaCargaEmpenhos');
            Route::get('/atualizasaldosempenhos', 'EmpenhoCrudController@executaAtualizaSaldosEmpenhos');
            Route::get('/atualizanaturezadespesas', 'EmpenhoCrudController@executaAtualizacaoNd');

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
