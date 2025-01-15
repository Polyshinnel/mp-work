<?php

namespace App\Console;

use App\Console\Commands\Ozon\GetNewOzonPacking;
use App\ScheduledJobs\GetHourlyPayment;
use App\ScheduledJobs\GetHourlyReturning;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('ozon:get-new-ozon-packing')->before(Log::info(''))->everyFiveMinutes();
        $schedule->command('ozon:update-ozon-order-status')->everyTwoMinutes();
        $schedule->command('ozon:update-ozon-order-site-info')->everyTenMinutes();
        $schedule->command('ozon:check-ozon-old-orders')->everySixHours();
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
