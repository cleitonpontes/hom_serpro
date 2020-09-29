<?php

namespace App\Console;

use App\Jobs\MigracaoempenhoJob;
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

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->schedule = $schedule;

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
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected function criarJobs()
    {
        $this->criarJobAtualizarSfPadrao();
        $this->criarJobEnviarEmailsAlertas();
        $this->criarJobAtualizarEmpenhos();
        $this->criarJobMigrarEmpenhos();
        $this->criarJobAtualizarSaldoDeEmpenhos();
        $this->criarJobLimparActivityLogs();
    }

    protected function executarJobs()
    {
        $this->executarJobDefault();
        $this->executarJobMigracaoSistemaConta();
        $this->executarJobSfPadrao();
        $this->executarJobAtualizaSaldoEmpenho();
        $this->executarJobAlteraDocumentoHabil();
        $this->executarJobSiasgContrato();
        $this->executarJobSiasgCargaCompra();
        $this->executarJobSiasgCompra();
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

    protected function criarJobAtualizarEmpenhos()
    {
        $this->schedule->call(
            'App\Http\Controllers\Execfin\EmpenhoCrudController@executaAtualizacaoNd'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('08:30');
    }

    protected function criarJobMigrarEmpenhos()
    {
        $this->schedule->call(
            'App\Http\Controllers\Execfin\EmpenhoCrudController@executaMigracaoEmpenho'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('08:40');
    }

    protected function criarJobAtualizarSaldoDeEmpenhos()
    {
        $this->schedule->call(
            'App\Http\Controllers\Execfin\EmpenhoCrudController@executaAtualizaSaldosEmpenhos'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('08:50');
    }

    protected function criarJobLimparActivityLogs()
    {
        $this->schedule->call(
            'App\Jobs\LimpaActivityLogJob@handle'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('09:00');
    }

    // ************************************************************
    // Comprasnet
    // ************************************************************
    protected function executarJobDefault()
    {
        $this->executaCommand('default', '09:00', 7200, 6);
    }

    protected function executarJobMigracaoSistemaConta()
    {
        $this->executaCommand('migracaosistemaconta', '09:10', 7200);
    }

    // ************************************************************
    // SIAFI
    // ************************************************************
    protected function executarJobSfPadrao()
    {
        $this->executaCommand('sfpadrao', '09:20', 90);
    }

    protected function executarJobAtualizaSaldoEmpenho()
    {
        $this->executaCommand('atualizasaldone', '09:30', 7200, 3);
    }

    protected function executarJobAlteraDocumentoHabil()
    {
        $this->executaCommand('siafialteradh', '09:40', 720, 3);
    }

    // ************************************************************
    // SIASG
    // ************************************************************
    protected function executarJobSiasgContrato()
    {
        $this->executaCommand('siasgcontrato', '09:50', 90);
    }

    protected function executarJobSiasgCargaCompra()
    {
        $this->executaCommand('cargasiasgcompra', '10:00', 900);
    }

    protected function executarJobSiasgCompra()
    {
        $this->executaCommand('siasgcompra', '10:10', 90);
    }

    // ************************************************************
    // Emails
    // ************************************************************
    protected function executarJobEmailDiario()
    {
        $this->executaCommand('email_diario', '10:20', 600);
    }

    protected function executarJobEmailMensal()
    {
        $this->executaCommand('email_mensal', '10:30', 600);
    }

    private function executaCommand($fila, $horario = '09:00', $timeout = 600, $tries = 1)
    {
        $this->schedule->command(
            "php artisan queue:work
                 --queue=$fila
                 --stop-when-empty
                 --timeout=$timeout
                 --tries=$tries"
        )
            ->timezone('America/Sao_Paulo')
            // ->weekdays() // Pode ser diário. Se não houver fila, nada será executado!
            ->at($horario);
    }
}
