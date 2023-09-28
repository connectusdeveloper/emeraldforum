<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected function schedule(Schedule $schedule)
    {
        if (demo()) {
            $schedule->command('data:reset')->hourly()->withoutOverlapping(5);
        }
        $schedule->command('backup:clean')->dailyAt('01:00');
        $schedule->command('app:generate-sitemap')->dailyAt('03:00');
        // $schedule->command('activitylog:clean')->dailyAt('23:00');
        $schedule->command('backup:run --only-db')->dailyAt('02:00');
        $schedule->command('notifications:clear')->daily()->at('23:15');
    }
}
