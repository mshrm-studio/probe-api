<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\LilNoun\SyncLilNounTotalSupply;
use App\Jobs\LilNoun\SyncLilNounTokenIdentities;
use App\Jobs\LilNoun\SyncLilNounTokenImages;
use App\Jobs\LilNoun\SyncLilNounTokenSeeds;
use App\Jobs\LilNoun\SyncLilNounTokenSeedNames;
use App\Jobs\LilNoun\SyncLilNounTokenBlockNumbers;
use App\Jobs\LilNoun\SyncLilNounTokenMintTimes;
use App\Jobs\Noun\SyncNounTotalSupply;
use App\Jobs\Noun\SyncNounTokenIdentities;
use App\Jobs\Noun\SyncNounTokenImages;
use App\Jobs\Noun\SyncNounTokenSeeds;
use App\Jobs\Noun\SyncNounTokenSeedNames;
use App\Jobs\Noun\SyncNounTokenBlockNumbers;
use App\Jobs\Noun\SyncNounTokenMintTimes;
use App\Jobs\Noun\SyncNounTokenColors;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        if (config('app.env') == 'local') 
        {
            $schedule->job(new SyncLilNounTotalSupply, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenIdentities, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenImages, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenSeeds, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenSeedNames, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenBlockNumbers, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenMintTimes, 'lils')->everyMinute();

            $schedule->job(new SyncNounTotalSupply, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenIdentities, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenImages, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenSeeds, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenSeedNames, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenBlockNumbers, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenMintTimes, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenColors, 'nouns')->everyMinute();
        }
        else if (config('app.env') == 'staging')
        {
            //
        }
        else if (config('app.env') == 'production') 
        {
            $schedule->job(new SyncLilNounTotalSupply, 'lils')->everyTenMinutes();
            $schedule->job(new SyncLilNounTokenIdentities, 'lils')->everyTenMinutes();
            $schedule->job(new SyncLilNounTokenImages, 'lils')->everyTenMinutes();
            $schedule->job(new SyncLilNounTokenSeeds, 'lils')->everyTenMinutes();
            $schedule->job(new SyncLilNounTokenSeedNames, 'lils')->everyTenMinutes();
            $schedule->job(new SyncLilNounTokenBlockNumbers, 'lils')->everyTenMinutes();
            $schedule->job(new SyncLilNounTokenMintTimes, 'lils')->everyTenMinutes();

            $schedule->job(new SyncNounTotalSupply, 'nouns')->hourly();
            $schedule->job(new SyncNounTokenIdentities, 'nouns')->hourly();
            $schedule->job(new SyncNounTokenImages, 'nouns')->hourly();
            $schedule->job(new SyncNounTokenSeeds, 'nouns')->everyThreeHours();
            $schedule->job(new SyncNounTokenSeedNames, 'nouns')->hourly();
            $schedule->job(new SyncNounTokenBlockNumbers, 'nouns')->hourly();
            $schedule->job(new SyncNounTokenMintTimes, 'nouns')->hourly();
            $schedule->job(new SyncNounTokenColors, 'nouns')->hourly();
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
