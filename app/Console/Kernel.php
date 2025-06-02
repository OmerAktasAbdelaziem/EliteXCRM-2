<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\GetAssets::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('capture:leads')->everyFiveMinutes();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    
    // protected function schedule(Schedule $schedule)
    // {
    //     $schedule->command('assets:cache')->everySecond();
    // }
}
