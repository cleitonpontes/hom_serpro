<?php

namespace App\Console;

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
        $this->executarJobs();
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

        //agendamentos
        $this->criarJobAtualizarND();
        $this->criarJobMigrarEmpenhos();
        $this->criarJobAtualizarSaldoDeEmpenhos();
        $this->criarJobEnviarEmailsAlertas();
        $this->criarJobLimparActivityLogs();
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
            ->at('08:00');
    }

    protected function criarJobMigrarEmpenhos()
    {
        $this->schedule->call(
            'App\Http\Controllers\Execfin\EmpenhoCrudController@executaMigracaoEmpenho'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('08:30');
    }

    protected function criarJobAtualizarSaldoDeEmpenhos()
    {
        $this->schedule->call(
            'App\Http\Controllers\Execfin\EmpenhoCrudController@executaAtualizaSaldosEmpenhos'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('09:30');
    }

    protected function criarJobAtualizacaoSiasgContratos()
    {
        $this->schedule->call('App\Http\Controllers\Gescon\Siasg\SiasgcontratoCrudController@executaJobAtualizacaoSiasgContratos')
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->everyFifteenMinutes();
    }

    protected function criarJobAtualizacaoSiasgCompras()
    {
        $this->schedule->call('App\Http\Controllers\Gescon\Siasg\SiasgcompraCrudController@executaJobAtualizacaoSiasgCompras')
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->everyFifteenMinutes();
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

    // ************************************************************
    // Comprasnet
    // ************************************************************
    protected function executarJobDefault()
    {
        $this->executaCommandCron('default', '3', 900, 1, '*', '7-22', '*', '*', '1-5');
    }

    protected function executarJobMigracaoSistemaConta()
    {
        $this->executaCommand('migracaosistemaconta', '23:00', 3, 7200);
    }

    protected function executarJobAtualizacaoND()
    {
        $this->executaCommand('atualizacaond', '08:05', 3, 900);
    }

    protected function executarJobMigracaoEmpenho()
    {
        $this->executaCommand('migracaoempenho', '08:40', 10, 3600);
    }

    protected function executarJobAtualizaSaldoEmpenho()
    {
        $this->executaCommand('atualizasaldone', '09:40', 20, 300, 3);
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
        $this->executaCommandCron('siafialteradh', '1', 900, 3, '*', '7-22', '*', '*', '1-5');
    }

    // ************************************************************
    // SIASG
    // ************************************************************
    protected function executarJobSiasgContrato()
    {
        $this->executaCommandCron('siasgcontrato', '5', 300, 20, '0,15,30,45', '7-22', '*', '*', '1-5');
    }

    protected function executarJobSiasgCompra()
    {
        $this->executaCommandCron('siasgcompra', '5', 300, 10, '0,15,30,45', '7-22', '*', '*', '1-5');
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
                // ->weekdays() // Pode ser diário. Se não houver fila, nada será executado!
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
                // ->weekdays() // Pode ser diário. Se não houver fila, nada será executado!
                ->cron("$minuto $hora $diasmes $meses $diassemana")
                ->runInBackground();
        }
    }

}
