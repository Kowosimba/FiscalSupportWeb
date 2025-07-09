<?php

use Illuminate\Console\Scheduling\Schedule;

class Kernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tickets:remind-technicians')->hourly();
         $schedule->command('queue:work --stop-when-empty')
             ->everyMinute()
             ->withoutOverlapping();
    }

}
