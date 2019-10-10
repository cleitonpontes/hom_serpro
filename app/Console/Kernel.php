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
        $schedule->call('App\Http\Controllers\Execfin\EmpenhoCrudController@executaMigracaoEmpenho')->dailyAt('08:40');

        $schedule->call('App\Http\Controllers\Execfin\EmpenhoCrudController@executaAtualizaSaldosEmpenhos')->dailyAt('08:50');

//        $schedule->job(new AlertaContratoJob)->dailyAt('08:00');
        $schedule->job(new AlertaContratoJob)->everyFiveMinutes();
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
