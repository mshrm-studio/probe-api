<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\SyncLilNounTotalSupply;
use App\Jobs\SyncLilNounTokenIdentities;
use App\Jobs\SyncLilNounTokenImages;
use App\Jobs\SyncLilNounTokenSeeds;
use App\Jobs\SyncLilNounTokenSeedNames;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new SyncLilNounTotalSupply)->everyFiveMinutes();
        $schedule->job(new SyncLilNounTokenIdentities)->everyFiveMinutes();
        $schedule->job(new SyncLilNounTokenImages)->everyFiveMinutes();
        $schedule->job(new SyncLilNounTokenSeeds)->everyFiveMinutes();
        $schedule->job(new SyncLilNounTokenSeedNames)->everyFiveMinutes();
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
