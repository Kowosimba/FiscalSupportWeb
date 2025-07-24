<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('tickets:remind-technicians')->hourly();
          // Send daily unassigned jobs report every day at 8:00 AM
        $schedule->command('jobs:send-daily-report')
                 ->dailyAt('06:00')
                 ->timezone(config('app.timezone', 'UTC'))
                 ->emailOutputOnFailure(config('mail.from.address'))
                 ->onFailure(function () {
                     \Log::error('Daily unassigned jobs report failed to run');
                 });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');

          $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }


    

   
}
