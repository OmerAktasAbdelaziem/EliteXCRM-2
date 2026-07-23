<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\GetAssets::class,
        \App\Console\Commands\HandleDemo::class,
        \App\Console\Commands\CheckSubscription::class,
        
        
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('handle:demo')->dailyAt('00:00');
        $schedule->command('capture:leads')->everyMinute()->withoutOverlapping();
        // $schedule->command('capture:leads')->everyFiveMinutes();
        //remove following line when go online
        //$schedule->command('calculate:pnl')->withoutOverlapping();
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
