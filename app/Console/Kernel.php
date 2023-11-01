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
        if (config('app.env') == 'local') {
            $schedule->job(new SyncLilNounTotalSupply)->everyMinute();
            $schedule->job(new SyncLilNounTokenIdentities)->everyMinute();
            $schedule->job(new SyncLilNounTokenImages)->everyMinute();
            $schedule->job(new SyncLilNounTokenSeeds)->everyMinute();
            $schedule->job(new SyncLilNounTokenSeedNames)->everyMinute();
        } else {
            $schedule->job(new SyncLilNounTotalSupply)->every5Minutes();
            $schedule->job(new SyncLilNounTokenIdentities)->every5Minutes();
            $schedule->job(new SyncLilNounTokenImages)->every5Minutes();
            $schedule->job(new SyncLilNounTokenSeeds)->every5Minutes();
            $schedule->job(new SyncLilNounTokenSeedNames)->every5Minutes();
        }
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
