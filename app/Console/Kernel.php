<?php

namespace App\Console;

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
        'App\Console\Commands\ClearExpiredRecord',
        'App\Console\Commands\FetchDatasource',
        'App\Console\Commands\FetchLassAnalysis',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->call(function () {
        //     logger('one min');
        // })->everyMinute();

        $schedule->command('fetch:record')
                 ->everyFiveMinutes();

        $schedule->command('fetch:lass-analysis')
                 ->everyFiveMinutes();

        // $schedule->command('fetch:geocoding')
        //         ->hourly();

        $schedule->command('record:clear-expired')
                 ->daily();
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
