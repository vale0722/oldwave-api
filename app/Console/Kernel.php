<?php

namespace App\Console;

use App\Console\Commands\ConsultRatingItems;
use App\Console\Commands\ResolvePaymentsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(ConsultRatingItems::class)->everyTenMinutes();
        $schedule->command(ResolvePaymentsCommand::class)->everyTenMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
