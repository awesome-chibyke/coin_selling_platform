<?php

namespace App\Console;

use App\Jobs\BulkMailSender;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();coin:market-update
        $schedule->command('coin:market-update')->everyFiveMinutes();
        $schedule->command('update:banks')->everySixHours();
        $schedule->command('resend:transfers')->everyFiveMinutes();
        $schedule->command('referal:transfer')->everyFiveMinutes();
        $schedule->command('process:partial-payments')->everyTenMinutes();

        //schedule jopbs
        //$schedule->job(new BulkMailSender())->everyTwoMinutes();
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
