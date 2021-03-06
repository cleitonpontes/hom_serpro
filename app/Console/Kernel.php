<?php

namespace App\Console;

use App\Models\Feriado;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $schedule = null;

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];
    protected $path;

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->schedule = $schedule;
        $this->path = env('APP_PATH');

        $this->criarJobs();
//        $this->executarJobs();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected function criarJobs()
    {
        //minutos
        $this->criarJobAtualizarSfPadrao();
        $this->criarJobAtualizacaoSiasgContratos();
        $this->criarJobAtualizacaoSiasgCompras();
        $this->criarJobAtualizaStatusPublicacao();
        $this->criarJobMinutasEmProcessamento();
        $this->enviaPublicaoesProximoDiaUtil();
//        $this->executaConsumoWsSiafiEmpenho();

        //agendamentos
        $this->criarJobAtualizarND();
        $this->criarJobMigrarEmpenhos();
        $this->criarJobAtualizarSaldoDeEmpenhos();
        $this->criarJobEnviarEmailsAlertas();
        $this->criarJobLimparActivityLogs();
        $this->criarJobContratoEmpenho();
    }

    protected function executarJobs()
    {
        //minutos
        $this->executarJobSfPadrao();
        $this->executarJobDefault();
        $this->executarJobSiasgCompra();
        $this->executarJobSiasgContrato();
        $this->executarJobAlteraDocumentoHabil();

        //agendamentos
        $this->executarJobAtualizacaoND();
        $this->executarJobMigracaoEmpenho();
        $this->executarJobAtualizaSaldoEmpenho();
        $this->executarJobMigracaoSistemaConta();
//        $this->executarJobSiasgCargaCompra();
        $this->executarJobEmailDiario();
        $this->executarJobEmailMensal();
    }

    protected function criarJobAtualizarSfPadrao()
    {
        $this->schedule->call(
            'App\Http\Controllers\Gescon\ContratosfpadraoCrudController@executaJobAtualizacaoSfPadrao'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->everyMinute();
    }

    protected function executaConsumoWsSiafiEmpenho()
    {
        $this->schedule->call(
            'App\Http\Controllers\Execfin\EmpenhoCrudController@incluirEmpenhoSiafi'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->everyMinute()
            ->between('9:00', '19:30');
    }

    protected function criarJobEnviarEmailsAlertas()
    {
        $this->schedule->call(
            'App\Http\Controllers\Admin\AlertaContratoController@enviaEmails'
        )
            ->timezone('America/Sao_Paulo')
            ->dailyAt('08:00');
    }

    protected function criarJobAtualizarND()
    {
        $this->schedule->call(
            'App\Http\Controllers\Execfin\EmpenhoCrudController@executaAtualizacaoNd'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('03:00');
    }

    protected function criarJobMigrarEmpenhos()
    {
        $this->schedule->call(
            'App\Http\Controllers\Execfin\EmpenhoCrudController@executaMigracaoEmpenho'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('01:00');
    }

    protected function criarJobAtualizaStatusPublicacao()
    {
        $this->schedule->call(
            'App\Http\Controllers\Publicacao\DiarioOficialClass@executaJobAtualizaSituacaoPublicacao'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->between('08:00', '09:00');
    }

    protected function enviaPublicaoesProximoDiaUtil()
    {
        $this->schedule->call(
            'App\Http\Controllers\Publicacao\DiarioOficialClass@enviaPublicacoesViaKernel'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('18:15');
    }


    protected function criarJobAtualizarSaldoDeEmpenhos()
    {
        $this->schedule->call(
            'App\Http\Controllers\Execfin\EmpenhoCrudController@executaAtualizaSaldosEmpenhos'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('03:00');
    }

    protected function criarJobMinutasEmProcessamento()
    {
        $this->schedule->call(
            'App\Http\Controllers\Empenho\Minuta\ProcessaNovamenteController@index'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->everyFifteenMinutes()
            ->between('09:15', '19:15')
            ->skip(function () {
                $date = date('Y-m-d');
                $feriados = Feriado::all()->pluck('data')->toArray();
                if(in_array($date, $feriados)){
                    return true;
                }
                return false;
            });
    }



    protected function criarJobAtualizacaoSiasgContratos()
    {
        $this->schedule->call('App\Http\Controllers\Gescon\Siasg\SiasgcontratoCrudController@executaJobAtualizacaoSiasgContratos')
            ->timezone('America/Sao_Paulo')
//            ->weekdays()
            ->everyMinute()
            ->between('7:00', '22:00');
    }

    protected function criarJobAtualizacaoSiasgCompras()
    {
        $this->schedule->call('App\Http\Controllers\Gescon\Siasg\SiasgcompraCrudController@executaJobAtualizacaoSiasgCompras')
            ->timezone('America/Sao_Paulo')
//            ->weekdays()
            ->everyMinute()
            ->between('7:00', '22:00');
    }

    protected function criarJobLimparActivityLogs()
    {
        $this->schedule->call(
            'App\Jobs\LimpaActivityLogJob@handle'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('23:30');
    }

    protected function criarJobContratoEmpenho()
    {
        $this->schedule->call(
            'App\Jobs\ContratoEmpenhoJob@handle'
        )
            ->timezone('America/Sao_Paulo')
            ->dailyAt('23:30');
    }

    // ************************************************************
    // Comprasnet
    // ************************************************************
    protected function executarJobDefault()
    {
        $this->executaCommandCron('default', '5', 150, 1, '*', '7-22', '*', '*', '1-5');
    }

    protected function executarJobMigracaoSistemaConta()
    {
        $this->executaCommand('migracaosistemaconta', '23:00', 3, 7200);
    }

    protected function executarJobAtualizacaoND()
    {
        $this->executaCommand('atualizacaond', '08:05', 3, 3600);
    }

    protected function executarJobMigracaoEmpenho()
    {
        $this->executaCommand('migracaoempenho', '08:40', 10, 7200);
    }

    protected function executarJobAtualizaSaldoEmpenho()
    {
        $this->executaCommand('atualizasaldone', '09:40', 20, 7200, 3);
    }

    // ************************************************************
    // SIAFI
    // ************************************************************
    protected function executarJobSfPadrao()
    {
        $this->executaCommandCron('sfpadrao', '1', 300, 1, '*', '7-22', '*', '*', '1-5');
    }


    protected function executarJobAlteraDocumentoHabil()
    {
        $this->executaCommandCron('siafialteradh', '1', 300, 1, '*', '7-22', '*', '*', '1-5');
    }

    // ************************************************************
    // SIASG
    // ************************************************************
    protected function executarJobSiasgContrato()
    {
        $this->executaCommandCron('siasgcontrato', '20', 300, 1, '*', '7-22', '*', '*', '1-5');
    }

    protected function executarJobSiasgCompra()
    {
        $this->executaCommandCron('siasgcompra', '5', 300, 1, '*', '7-22', '*', '*', '1-5');
    }

//    protected function executarJobSiasgCargaCompra()
//    {
//        $this->executaCommandCron('cargasiasgcompra', '1', 300, 1, '*', '7-22', '*', '*', '1-5');
//    }


    // ************************************************************
    // Emails
    // ************************************************************
    protected function executarJobEmailDiario()
    {
        $this->executaCommand('email_diario', '10:20', 5, 600);
    }

    protected function executarJobEmailMensal()
    {
        $this->executaCommand('email_mensal', '10:30', 5, 600);
    }

    private function executaCommand($fila, $horario = '09:00', $quantidadeExecucoes = 1, $timeout = 600, $tries = 1)
    {
        for ($i = 1; $i <= $quantidadeExecucoes; $i++) {
            $this->schedule->exec(
                "php $this->path" . "artisan queue:work --queue=$fila --stop-when-empty --timeout=$timeout --tries=$tries"
            )
                ->timezone('America/Sao_Paulo')
                // ->weekdays() // Pode ser di??rio. Se n??o houver fila, nada ser?? executado!
                ->at($horario)
                ->runInBackground();
        }
    }

    private function executaCommandCron($fila, $quantidadeExecucoes = 1, $timeout = 600, $tries = 1, $minuto = '*', $hora = '*', $diasmes = '*', $meses = '*', $diassemana = '*')
    {
        for ($i = 1; $i <= $quantidadeExecucoes; $i++) {
            $this->schedule->exec(
                "php $this->path" . "artisan queue:work --queue=$fila --stop-when-empty --timeout=$timeout --tries=$tries"
            )
                ->timezone('America/Sao_Paulo')
                // ->weekdays() // Pode ser di??rio. Se n??o houver fila, nada ser?? executado!
                ->cron("$minuto $hora $diasmes $meses $diassemana")
                ->runInBackground();
        }
    }

}
