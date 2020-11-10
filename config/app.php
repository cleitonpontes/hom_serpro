<?php

return [

    'google_analytics' => env('GOOGLE_ANALYTICS',''),

    //contas contábeis empenhodetalhado
    'contas_contabeis_empenhodetalhado_exercicioatual' => [
        'empaliquidar' => '622920101', // 6.2.2.9.2.01.01 = EMPENHOS A LIQUIDAR
        'empemliquidacao' => '622920102', // 6.2.2.9.2.01.02 = EMPENHOS EM LIQUIDACAO
        'empliquidado' => '622920103', // 6.2.2.9.2.01.03 = EMPENHOS LIQUIDADOS A PAGAR
        'emppago' => '622920104', // 6.2.2.9.2.01.04 = EMPENHOS PAGOS
        'empaliqrpnp' => '622920105', // 6.2.2.9.2.01.05 = EMPENHOS A LIQUIDAR INSCRITOS EM RPNP
        'empemliqrpnp' => '622920106', // 6.2.2.9.2.01.06 = EMPENHOS EM LIQUIDACAO INSCRITOS EM RPNP
        'emprpp' => '622920107', // 6.2.2.9.2.01.07 = EMPENHOS LIQUIDADOS A PAGAR INSCRITO EM RPP
    ],

    'conta_contabil_credito_disponivel' => '622110000',

    'modalidades_sem_exigencia' => [
        'Inexigibilidade',
        'Não se Aplica'
    ],

    'contas_contabeis_empenhodetalhado_exercicioanterior' => [
        'rpnpaliquidinsc' => '531110100', // 5.3.1.1.1.01.00 = RP NAO PROCESSADOS A LIQUIDAR INSCRITOS
        'rpnpemliquidinsc' => '531110200', // 5.3.1.1.1.02.00 = RP NAO PROCESSADOS EM LIQUIDACAO INSCRITOS
        'reinscrpnpaliquidbloq' => '531210000', // 5.3.1.2.1.00.00 = REINSCRICAO RPNP A LIQUIDAR/BLOQUEADOS
        'reinscrpnpemliquid' => '531220000', // 5.3.1.2.2.00.00 = REINSCRICAO RP NAO PROCESSADO EM LIQUIDACAO
        'rpnprestab' => '531300000', // 5.3.1.3.0.00.00 = RP NAO PROCESSADOS RESTABELECIDOS
        'rpnpaliquidtransfdeb' => '531610000', // 5.3.1.6.1.00.00 = RPNP A LIQUIDAR RECEBIDO POR TRANSFERENCIA
        'rpnpaliquidemliquidtransfdeb' => '531620000', // 5.3.1.6.2.00.00 = RPNP A LIQ EM LIQ RECEBIDO POR TRANSFERENCIA
        'rpnpliquidapgtransfdeb' => '531630000', // 5.3.1.6.3.00.00 = RPNP LIQ A PAGAR RECEBIDOS POR TRANSFERENCIA
        'rpnpbloqtransfdeb' => '531640000', // 5.3.1.6.4.00.00 = RPNP BLOQUEADOS RECEBIDOS POR TRANSFERENCIA
        'rppinsc' => '532100000', // 5.3.2.1.0.00.00 = RP PROCESSADOS - INSCRITOS
        'rppexecant' => '532200000', // 5.3.2.2.0.00.00 = RP PROCESSADOS - EXERCICIOS ANTERIORES
        'rpptrasf' => '532600000', // 5.3.2.6.0.00.00 = RP PROCESSADOS RECEBIDOS POR TRANSFERENCIA
        'rpnpaliquidar' => '631100000', // 6.3.1.1.0.00.00 = RP NAO PROCESSADOS A LIQUIDAR
        'rpnpaliquidaremliquidacao' => '631200000', // 6.3.1.2.0.00.00 = RP NAO PROCESSADOS A LIQUIDAR EM LIQUIDACAO
        'rpnpliquidado' => '631300000', // 6.3.1.3.0.00.00 = RP NAO PROCESSADOS LIQUIDADOS A PAGAR
        'rpnppago' => '631400000', // 6.3.1.4.0.00.00 = RP NAO PROCESSADOS PAGO
        'rpnpaliquidarbloq' => '631510000', // 6.3.1.5.1.00.00 = RPNP A LIQUIDAR BLOQUEADOS POR DECRETO
        'rpnpaliquidaremliquidbloq' => '631520000', // 6.3.1.5.2.00.00 = RPNP A LIQUIDAR EM LIQUIDACAO BLOQUEADO -DEC
        'rpnpaliquidtransfcred' => '631610000', // 6.3.1.6.1.00.00 = RPNP A LIQUIDAR TRANSFERIDO
        'rpnpaliquidemliquidtransfcred' => '631620000', // 6.3.1.6.2.00.00 = RPNP A LIQUIDAR EM LIQUIDACAO TRANSFERIDO
        'rpnpliquidapgtransfcred' => '631630000', // 6.3.1.6.3.00.00 = RPNP LIQUIDADOS A PAGAR TRANSFERIDOS
        'rpnpbloqtransfcred' => '631640000', // 6.3.1.6.4.00.00 = RPNP BLOQUEADOS TRANSFERIDOS
        'rpnpcancelado' => '631910000', // 6.3.1.9.1.00.00 = CANCELAMENTOS POR INSUFICIENCIA DE RECURSOS
        'rpnpoutrocancelamento' => '631980000', // 6.3.1.9.8.00.00 = OUTROS CANCELAMENTOS DE RPNP
        'rpnpemliqoutrocancelamento' => '631990000', // 6.3.1.9.9.00.00 = OUTROS CANCELAMENTOS DE RPNP EM LIQUIDACAO
        'rppliquidado' => '632100000', // 6.3.2.1.0.00.00 = RP PROCESSADOS A PAGAR
        'rpppago' => '632200000', // 6.3.2.2.0.00.00 = RP PROCESSADOS PAGOS
        'rpptransffusao' => '632600000', // 6.3.2.6.0.00.00 = RPP TRANSFERIDOS POR FUSAO/CISAO/EXTINCAO
        'rppcancelado' => '632910100', // 6.3.2.9.1.01.00 = CANCELAMENTO DE RP PROCESSADOS - NE
        'ajusterpexecant' => '632910200', // 6.3.2.9.1.02.00 =  AJUSTE DE CONTROLE RP DE EXERC ANTERIORES
    ],


    //Apropriação Folha de Pagamento
    'conta_contabil' => 622920101,
    'situacao_pco' => 'PCO',
    'situacao_despesa_anular' => 'DESPESA_ANULAR',
    'erro_permissao' => 'Acesso negado - você não possui a permissão necessária para acessar esta página.<br \> <a href="javascript:history.back()" \'="">Voltar</a>',
    'erro_token' => 'Acesso negado - token informado está errado.<br \> <a href="javascript:history.back()" \'="">Voltar</a>',
    'carga_inicial_siasg_token' => env('CARGA_INICIAL_SIASG_TOKEN'),

    'situacao_fatura' => [
        "PEN" => 'Pendente',
        "PGS" => 'Pagamento Suspenso',
        "PGP" => 'Pagamento Parcial',
        "ANA" => 'Analisado',
        "PPG" => 'Pronto para Pagamento',
        "APR" => 'Apropriado Siafi',
        "PGO" => 'Pago'
    ],

    'meses_referencia_fatura' => [
        "01" => '01 - Janeiro',
        "02" => '02 - Fevereiro',
        "03" => '03 - Março',
        "04" => '04 - Abril',
        "05" => '05 - Maio',
        "06" => '06 - Junho',
        "07" => '07 - Julho',
        "08" => '08 - Agosto',
        "09" => '09 - Setembro',
        "10" => '10 - Outubro',
        "11" => '11 - Novembro',
        "12" => '12 - Dezembro',
    ],

    'anos_referencia_fatura' => [
        '2010' => '2010',
        '2011' => '2011',
        '2012' => '2012',
        '2013' => '2013',
        '2014' => '2014',
        '2015' => '2015',
        '2016' => '2016',
        '2017' => '2017',
        '2018' => '2018',
        '2019' => '2019',
        '2020' => '2020',
        '2021' => '2021',
        '2022' => '2022',
        '2023' => '2023',
        '2024' => '2024',
        '2025' => '2025',
        '2026' => '2026',
        '2027' => '2027',
        '2028' => '2028',
        '2029' => '2029',
        '2030' => '2030',
        '2031' => '2031',
        '2032' => '2032',
        '2033' => '2033',
        '2034' => '2034',
        '2035' => '2035',
        '2036' => '2036',
        '2037' => '2037',
        '2038' => '2038',
        '2039' => '2039',
        '2040' => '2040',
        '2041' => '2041',
        '2042' => '2042',
        '2043' => '2043',
        '2044' => '2044',
        '2045' => '2045',
        '2046' => '2046',
        '2047' => '2047',
        '2048' => '2048',
        '2049' => '2049',
        '2050' => '2050',
    ],

    'abas' => [
        "CREDITO" => "Créditos",
        "DEDUCAO" => "Deduções",
        "DESPESA_ANULAR" => "Despesa a Anular",
        "ENCARGO" => "Encargos",
        "PCO" => "Principal com Orçamento",
        "PSO" => "Principal sem Orçamento",
        "OUTROSLANCAMENTOS" => "Outros Lançamentos",
    ],

    'aba_x_categoria_ddp' => [
        "1" => "PCO",
        "2" => "DESPESA_ANULAR",
        "3" => "DEDUCAO",
        "4" => "PSO",
        "6" => "ENCARGO",
        "7" => "OUTROSLANCAMENTOS",
        "8" => "CREDITO",
    ],

    'tipo_rubrica' => [
        "AMBAS" => "AMBAS",
        "DESCONTO" => "DESCONTO",
        "RENDIMENTO" => "RENDIMENTO",
    ],

    'situacao_rubrica' => [
        "ATIVA" => "ATIVA",
        "INATIVA" => "INATIVA",
    ],

    'ddp_nivel' => [
        "A" => "A - Ativo Civil",
        "B" => "B - Aposentado e Pensionista Civil e Militar",
        "C" => "C - Ativo Militar",
        "D" => "D - Inativo Militar",
        "E" => "E - Ativo CLT",
    ],


    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'pt_br',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */
        geekcom\ValidatorDocs\ValidatorProvider::class,
        Kris\LaravelFormBuilder\FormBuilderServiceProvider::class,
        Bootstrapper\BootstrapperL5ServiceProvider::class,
        Yajra\DataTables\DataTablesServiceProvider::class,
        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'FormBuilder' => Kris\LaravelFormBuilder\Facades\FormBuilder::class,
        'Icon' => Bootstrapper\Facades\Icon::class,
        'Button' => Bootstrapper\Facades\Button::class,
        'DropdownButton' => Bootstrapper\Facades\DropdownButton::class,
        'DataTables' => Yajra\DataTables\Facades\DataTables::class,
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,

    ],

];
