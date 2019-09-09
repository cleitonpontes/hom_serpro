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
        $schedule->job(new MigracaoempenhoJob)->dailyAt('08:45');
        $schedule->job(new MigracaoempenhoJob)->everyFiveMinutes();
//        $schedule->job(new MigracaoempenhoJob)->everyMinute();
        $schedule->call('App\Jobs\MigracaoempenhoJob@atualizaSaldosEmpenhos')->dailyAt('08:50');
//        $schedule->call('App\Jobs\MigracaoempenhoJob@atualizaSaldosEmpenhos')->everyFiveMinutes();
        $schedule->job(new AlertaContratoJob)->dailyAt('08:00');
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
