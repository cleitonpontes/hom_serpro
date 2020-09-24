<?php

namespace App\Console;

use App\Jobs\MigracaoempenhoJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
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
        $this->criarJobs($schedule);
        $this->executarJobs($schedule);
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

    protected function criarJobs(Schedule $schedule)
    {
//        $this->jobAtualizarSfPadrao($schedule);
        $this->jobEnviarEmailsAlertas($schedule);
//        $this->jobAtualizarEmpenhos($schedule);
//        $this->jobMigrarEmpenhos($schedule);
//        $this->jobAtualizarSaldoDeEmpenhos($schedule);
//        $this->jobLimparActivityLogs($schedule);
    }

    protected function executarJobs(Schedule $schedule)
    {
//        $this->jobAtualizarSfPadrao($schedule);
//        $this->jobEnviarEmailsAlertas($schedule);
//        $this->jobAtualizarEmpenhos($schedule);
//        $this->jobMigrarEmpenhos($schedule);
//        $this->jobAtualizarSaldoDeEmpenhos($schedule);
//        $this->jobLimparActivityLogs($schedule);
    }

    protected function jobAtualizarSfPadrao(Schedule $schedule)
    {
        $schedule->call(
            'App\Http\Controllers\Gescon\ContratosfpadraoCrudController@executaJobAtualizacaoSfPadrao'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->everyMinute();
    }

    protected function jobEnviarEmailsAlertas(Schedule $schedule)
    {
        $schedule->call(
            'App\Http\Controllers\Admin\AlertaContratoController@enviaEmails'
        )
            ->timezone('America/Sao_Paulo')
            // ->dailyAt('08:00')
            ->everyMinute();
    }

    protected function jobAtualizarEmpenhos(Schedule $schedule)
    {
        $schedule->call(
            'App\Http\Controllers\Execfin\EmpenhoCrudController@executaAtualizacaoNd'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('08:30');
    }

    protected function jobMigrarEmpenhos(Schedule $schedule)
    {
        $schedule->call(
            'App\Http\Controllers\Execfin\EmpenhoCrudController@executaMigracaoEmpenho'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('08:40');
    }

    protected function jobAtualizarSaldoDeEmpenhos(Schedule $schedule)
    {
        $schedule->call(
            'App\Http\Controllers\Execfin\EmpenhoCrudController@executaAtualizaSaldosEmpenhos'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('08:50');
    }

    protected function jobLimparActivityLogs(Schedule $schedule)
    {
        $schedule->call(
            'App\Jobs\LimpaActivityLogJob@handle'
        )
            ->timezone('America/Sao_Paulo')
            ->weekdays()
            ->at('09:00');
    }


}
