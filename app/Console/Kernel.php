<?php

namespace App\Console;

use App\Jobs\AlertaContratoJob;
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
        $schedule->call('App\Http\Controllers\Execfin\EmpenhoCrudController@executaAtualizacaoNd')
            ->weekdays()
            ->timezone('America/Sao_Paulo')
            ->at('08:30');

        $schedule->call('App\Http\Controllers\Execfin\EmpenhoCrudController@executaMigracaoEmpenho')
            ->weekdays()
            ->timezone('America/Sao_Paulo')
            ->at('08:40');

        $schedule->call('App\Http\Controllers\Execfin\EmpenhoCrudController@executaAtualizaSaldosEmpenhos')
            ->weekdays()
            ->timezone('America/Sao_Paulo')
            ->at('08:50');

        $schedule->call('App\Http\Controllers\Gescon\ContratosfpadraoCrudController@executaJobAtualizacaoSfPadrao')
            ->weekdays()
            ->timezone('America/Sao_Paulo')
            ->everyMinute();

        $schedule->call('App\Jobs\LimpaActivityLogJob@handle')
            ->weekdays()
            ->timezone('America/Sao_Paulo')
            ->at('09:00');

        $schedule->job(new AlertaContratoJob)
            ->timezone('America/Sao_Paulo')
            ->dailyAt('08:00');

        $schedule->call('App\Http\Controllers\Gescon\Siasg\SiasgcompraCrudController@executaJobAtualizacaoSiasgCompras')
//            ->weekdays()
            ->timezone('America/Sao_Paulo')
            ->everyMinute();

        $schedule->call('App\Http\Controllers\Gescon\Siasg\SiasgcontratoCrudController@executaJobAtualizacaoSiasgContratos')
            ->weekdays()
            ->timezone('America/Sao_Paulo')
            ->everyMinute();

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
}
