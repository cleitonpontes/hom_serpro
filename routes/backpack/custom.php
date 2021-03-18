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

            Route::get('executadou/{datapub}', 'ExecutaDouController@executaRotinaEnviaDou');

            //busca empenhos via ajax
            Route::get('empenho', 'EmpenhoController@index');
            Route::get('empenho/{id}', 'EmpenhoController@show');

            Route::get('codigoitemAmparoLegal', 'CodigoitemController@index');    // amparo legal

            Route::get('unidade', 'UnidadeController@index');
            Route::get('unidade/{id}', 'UnidadeController@show');
            Route::get('contratohistorico', 'ContratohistoricoController@index');
            Route::get('contratohistorico/{id}', 'ContratohistoricoController@show');
            Route::get('unidadecomorgao', 'UnidadeComOrgaoController@index');
            Route::get('unidadecomorgao/{id}', 'UnidadeComOrgaoController@show');
            Route::get('fornecedor', 'FornecedorController@index');
            Route::get('suprido', 'FornecedorController@suprido');
            Route::get('fornecedor/{id}', 'FornecedorController@show');
            Route::get('planointerno', 'PlanointernoController@index');
            Route::get('planointerno/{id}', 'PlanointernoController@show');
            Route::get('naturezadespesa', 'NaturezadespesaController@index');
            Route::get('naturezadespesa/{id}', 'NaturezadespesaController@show');
            Route::get('comprasiasg', 'ComprasiasgController@index');
            Route::get('comprasiasg/{id}', 'ComprasiasgController@show');
            Route::get('catmatsergrupo', 'CatmatsergrupoController@index');
            Route::get('empecatmatsergrupo/{id}', 'CatmatsergrupoController@show');
            Route::get('catmatseritem/buscarportipo/{tipo_id}', 'CatmatseritemController@itemPorTipo')->name('busca.catmatseritens.portipo');
            Route::get('empecatmatseritem/{id}', 'CatmatseritemController@show')->name('busca.catmatseritens.id');
            Route::get('orgaosubcategoria', 'OrgaosubcategoriaController@index');
            Route::get('orgaosubcategoria/{id}', 'OrgaosubcategoriaController@show');
//            Route::get('ocorrenciaconcluida', 'OcorrenciaconcluidaController@index');
//            Route::get('ocorrenciaconcluida/{id}', 'OcorrenciaconcluidaController@show');
            Route::get('municipios', 'MunicipioController@index');
            Route::get('amparolegal', 'AmparoLegalController@index');
            Route::get('amparolegal/{id}', 'AmparoLegalController@show');
            Route::get('qualificacao', 'TermoAditivoController@index');
            Route::get('atualizasaldos/unidade/{cod_unidade}', 'SaldoContabilController@atualizaSaldosPorUnidade')->name('atualiza.saldos.unidade');
            Route::get('atualizasaldos/linha/{saldo_id}', 'SaldoContabilController@atualizaSaldosPorLinha')->name('atualiza.saldos.linha');
            Route::get('pupula/tabelas/siafi/{minuta_id}', 'MinutaEmpenhoController@populaTabelasSiafi')->name('popula.tabelas.siafi');
            Route::get('pupula/tabelas/siafi/{minuta_id}/{remessa?}', 'MinutaEmpenhoController@populaTabelasSiafiAlteracao')->name('popula.tabelas.siafi.alt');
            Route::get('inserir/celula/modal/{cod_unidade}/{contacorrente}', 'SaldoContabilController@inserirCelulaOrcamentaria')->name('saldo.inserir.modal');
            Route::get('carrega/saldos/unidade/{cod_unidade}', 'SaldoContabilController@carregaSaldosPorUnidadeSiasg')->name('carrega.saldos.unidade');
            Route::get('minutaempenhoparacontrato', 'MinutaEmpenhoController@minutaempenhoparacontrato');
            Route::get('novoempenho/{minuta_id}', 'MinutaEmpenhoController@novoEmpenhoMesmaCompra')->name('novo.empenho.compra');
            Route::get('atualiza-credito-orcamentario/{minuta_id}', 'MinutaEmpenhoController@atualizaCreditoOrcamentario')->name('atualiza.credito.orcamentario');
            Route::get('contrato/numero', 'ContratoController@index');
            Route::get('inserir/item/modal/{tipo_id}/{contacorrente}', 'ContratoItensMinutaController@inserirIten')->name('item.inserir.modal');
            Route::get('buscar/itens/modal/{minutas_id}', 'ContratoItensMinutaController@buscarItensModal')->name('buscar.itens.modal');
            Route::get('buscar/itens/instrumentoinicial/{minutas_id}', 'ContratoItensMinutaController@buscarItensDeMinutaParaTelaInstrumentoInicial')->name('buscar.itens.instrumentoinicial');
            Route::get('buscar/campos/contrato/empenho/{id}', 'ContratoController@buscarCamposParaCadastroContratoPorIdEmpenho')->name('buscar.campos.contrato.empenho');
            Route::get(
                '/saldo-historico-itens/{id}',
                'SaldoHistoricoItemController@retonaSaldoHistoricoItens'
            )->name('saldo.historico.itens');

            Route::group([
                'prefix' => 'empenho',
            ], function () {
                Route::put('/sem/contrato/e/{empenho}/f/{fornecedor}/c/{contrato}', 'EmpenhoController@gravaContratoEmpenho');
            });
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
            CRUD::resource('feriado', 'FeriadoCrudController');
            CRUD::resource('failedjobs', 'FailedjobsCrudController');
            CRUD::resource('jobs', 'JobsCrudController');
            CRUD::resource('amparolegal', 'AmparoLegalCrudController');
            CRUD::resource('padroespublicacao', 'PadroespublicacaoCrudController');
            CRUD::resource('publicacoes', 'ContratoPublicacaoAdminCrudController');
            CRUD::resource('ajusteminuta', 'AjusteMinutasCrudController');

            Route::get('ajusteminuta/{minuta_id}/remessa/{id_remessa}/atualizaritemcompracontrato', 'AjusteMinutasCrudController@atualizaritemcompracontrato')
                ->name('ajusteminuta.atualizar.contrato.compra');

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

            Route::get('/retryfailedjob/{id}', function ($id) {
                $path = env('APP_PATH');
                exec('php ' . $path . 'artisan queue:retry ' . $id);
                return redirect(url('/admin/failedjobs'));
            });
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


            Route::get(
                '/buscar-contrato-itens/{contrato_id}',
                'ContratoitemCrudController@retonaContratoItem'
            )->name('contrato.item');

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
                //rota de teste para atualização do contrato
                Route::get('/atualiza-contrato', 'SiasgcontratoCrudController@executaJobAtualizacaoSiasgContratos');
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
                CRUD::resource('publicacao', 'ContratoPublicacaoCrudController');
                Route::get(
                    '/publicacao/{id}/atualizarsituacaopublicacao',
                    'ContratoPublicacaoCrudController@executarAtualizacaoSituacaoPublicacao'
                );

                Route::get('/publicacao/{publicacao_id}/deletarpublicacao', 'ContratoPublicacaoCrudController@deletarPublicacao');
                Route::get('/publicacao/{publicacao_id}/enviarpublicacao', 'ContratoPublicacaoCrudController@enviarPublicacao');
                Route::get('/publicacao/{publicacao_id}/consultarpublicacao', 'ContratoPublicacaoCrudController@consultarPublicacao');

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
            Route::group(
                ['prefix' => 'meus-servicos/{contrato_id}/{contratoitem_servico_id}'],
                function () {
                    CRUD::resource('indicadores', 'ContratoItemServicoIndicadorCrudController');
                    Route::group(['prefix' => '{cisi_id}'], function () {
                        CRUD::resource('glosas', 'GlosaCrudController');
                    });
                }
            );

//            Route::get('/notificausers', 'ContratoCrudController@notificaUsers');
        });

        Route::group([
            'prefix' => 'execfin',
            'namespace' => 'Execfin',
        ], function () {

            CRUD::resource('empenho', 'EmpenhoCrudController');
            Route::get('incluirnovoempenho', 'EmpenhoCrudController@incluirEmpenhoSiafi');
            Route::get('enviaempenhosiasg', 'EmpenhoCrudController@enviaEmpenhoSiasgTeste');
            CRUD::resource('situacaosiafi', 'ExecsfsituacaoCrudController');
            CRUD::resource('rhsituacao', 'RhsituacaoCrudController');
            CRUD::resource('rhrubrica', 'RhrubricaCrudController');

//            Route::get('/migracaoempenhos', 'EmpenhoCrudController@executaMigracaoEmpenho');
//            Route::get('/migracaoempenhos', 'EmpenhoCrudController@executaCargaEmpenhos');
//            Route::get('/atualizasaldosempenhos', 'EmpenhoCrudController@executaAtualizaSaldosEmpenhos');
            Route::get('/atualizanaturezadespesas', 'EmpenhoCrudController@executaAtualizacaoNd');

            Route::group(['prefix' => 'empenho/{empenho_id}'], function () {
                CRUD::resource('empenhodetalhado', 'EmpenhodetalhadoCrudController');
            });
        });

        // Módulo Empenho
        Route::group([
            'prefix' => 'empenho',
            'namespace' => 'Empenho',
            'as' => 'empenho.',
            'middleware' => ['verify.step.empenho'],
        ], function () {

            /**
             *
             * Minuta Empenho - Genéricos
             *
             **/


            CRUD::resource('/minuta', 'MinutaEmpenhoCrudController');

            Route::get('minuta/{minuta_id}/atualizarsituacaominuta', 'MinutaEmpenhoCrudController@executarAtualizacaoSituacaoMinuta')
                ->name('minuta.atualizar.situacao');

            Route::get('minuta/{minuta_id}/deletarminuta', 'MinutaEmpenhoCrudController@deletarMinuta')
                ->name('minuta.deletar');

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
                CRUD::resource('alteracao', 'MinutaAlteracaoCrudController', ['except' => ['show', 'edit']]);

                Route::get('/alteracao/{remessa}/show/{minuta}', 'MinutaAlteracaoCrudController@show')
                    ->name('crud.alteracao.show');
                Route::get('/alteracao/{remessa}/edit/{minuta}', 'MinutaAlteracaoCrudController@create')
                    ->name('crud.alteracao.edit');


                Route::get('alteracao-dt', 'MinutaAlteracaoCrudController@ajax')->name('crud.alteracao.ajax');

                Route::group(['prefix' => 'alteracao'], function () {

                    Route::get('/{remessa_id}/atualizarsituacaominuta', 'MinutaAlteracaoCrudController@executarAtualizacaoSituacaoMinuta')
                        ->name('minuta.alteracao.atualizar.situacao');

                    Route::get('/{remessa_id}/deletarminuta', 'MinutaAlteracaoCrudController@deletarMinuta')
                        ->name('minuta.alteracao.deletar');

                    CRUD::resource('passivo-anterior', 'MinutaAlteracaoPassivoAnteriorCrudController', ['except' => ['update','store', 'create', 'show', 'edit']]);

                    Route::get('passivo-anterior/{remessa}', 'MinutaAlteracaoPassivoAnteriorCrudController@create')
                        ->name('crud.alteracao.passivo-anterior');

                    Route::get('passivo-anterior/{remessa}/edit', 'MinutaAlteracaoPassivoAnteriorCrudController@create')
                        ->name('crud.alteracao.passivo-anterior.edit');

                    Route::post('passivo-anterior/{remessa}', 'MinutaAlteracaoPassivoAnteriorCrudController@store')
                        ->name('crud.alteracao.passivo-anterior.store');

                    Route::put('passivo-anterior/{remessa}', 'MinutaAlteracaoPassivoAnteriorCrudController@update')
                        ->name('crud.alteracao.passivo-anterior.update');
                });
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
