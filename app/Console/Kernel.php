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
        // $schedule->call(function () {
        //     logger('one min');
        // })->everyMinute();

        $schedule->command('fetch:record')
                 ->timezone('Asia/Taipei')
                 ->everyMinute()
                 ->withoutOverlapping();

        $schedule->command('fetch:lass-analysis')
                 ->timezone('Asia/Taipei')
                 ->everyTenMinutes();

        $schedule->command('fetch:lass-prediction')
                 ->timezone('Asia/Taipei')
                 ->hourly();

        // $schedule->command('record:update-json')
        //          ->timezone('Asia/Taipei')
        //          ->everyFiveMinutes();

        $schedule->command('record:clear-expired')
                 ->timezone('Asia/Taipei')
                 ->hourlyAt(3);
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
}
