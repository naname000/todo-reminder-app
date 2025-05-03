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
        $schedule->command('app:notify-today-operation-to-slack')->dailyAt('08:50')->timezone('Asia/Tokyo');
        $schedule->command('app:notify-next-business-day-operation-to-slack')->dailyAt('17:50')->timezone('Asia/Tokyo');
        $schedule->command('app:notify-ten-minutes-from-now-operation-to-slack')->everyMinute()->timezone('Asia/Tokyo');
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
