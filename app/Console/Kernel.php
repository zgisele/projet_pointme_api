<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    // protected function schedule(Schedule $schedule): void
    // {
    //     // $schedule->command('inspire')->hourly();
    //      $schedule->command('qr:generate-daily')->dailyAt('00:00');
    // }
    protected function schedule(Schedule $schedule)
    {
        // génère un token chaque jour à 00:00 (serveur timezone)
        // ou utiliser ->dailyAt('00:00') pour horaire précis.
        $schedule->command('generate:qr-token')->dailyAt('00:00');
        // $schedule->command('generate:qr-token')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
