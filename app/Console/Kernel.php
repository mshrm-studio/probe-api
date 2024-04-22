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
use App\Jobs\LilNoun\SyncLilNounTokenColors;
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
            // $schedule->job(new SyncLilNounTotalSupply, 'lils')->everyMinute();
            // $schedule->job(new SyncLilNounTokenIdentities, 'lils')->everyMinute();
            // $schedule->job(new SyncLilNounTokenImages, 'lils')->everyMinute();
            // $schedule->job(new SyncLilNounTokenSeeds, 'lils')->everyMinute();
            // $schedule->job(new SyncLilNounTokenSeedNames, 'lils')->everyMinute();
            // $schedule->job(new SyncLilNounTokenBlockNumbers, 'lils')->everyMinute();
            // $schedule->job(new SyncLilNounTokenMintTimes, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenColors, 'lils')->everyMinute();

            // $schedule->job(new SyncNounTotalSupply, 'nouns')->everyMinute();
            // $schedule->job(new SyncNounTokenIdentities, 'nouns')->everyMinute();
            // $schedule->job(new SyncNounTokenImages, 'nouns')->everyMinute();
            // $schedule->job(new SyncNounTokenSeeds, 'nouns')->everyMinute();
            // $schedule->job(new SyncNounTokenSeedNames, 'nouns')->everyMinute();
            // $schedule->job(new SyncNounTokenBlockNumbers, 'nouns')->everyMinute();
            // $schedule->job(new SyncNounTokenMintTimes, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenColors, 'nouns')->everyMinute();
        }
        else if (config('app.env') == 'staging') 
        {
            $schedule->job(new SyncLilNounTotalSupply, 'lils')->hourly();
            $schedule->job(new SyncLilNounTokenIdentities, 'lils')->hourly();
            $schedule->job(new SyncLilNounTokenImages, 'lils')->hourly();
            $schedule->job(new SyncLilNounTokenSeeds, 'lils')->hourly();
            $schedule->job(new SyncLilNounTokenSeedNames, 'lils')->hourly();
            $schedule->job(new SyncLilNounTokenBlockNumbers, 'lils')->hourly();
            $schedule->job(new SyncLilNounTokenMintTimes, 'lils')->hourly();
            $schedule->job(new SyncLilNounTokenColors, 'lils')->hourly();

            $schedule->job(new SyncNounTotalSupply, 'nouns')->everyTenMinutes();
            $schedule->job(new SyncNounTokenIdentities, 'nouns')->everyTenMinutes();
            $schedule->job(new SyncNounTokenImages, 'nouns')->everyTenMinutes();
            $schedule->job(new SyncNounTokenSeeds, 'nouns')->everyTenMinutes();
            $schedule->job(new SyncNounTokenSeedNames, 'nouns')->everyTenMinutes();
            $schedule->job(new SyncNounTokenBlockNumbers, 'nouns')->everyTenMinutes();
            $schedule->job(new SyncNounTokenMintTimes, 'nouns')->everyTenMinutes();
            $schedule->job(new SyncNounTokenColors, 'nouns')->everyTenMinutes();
        }
        else if (config('app.env') == 'production') 
        {
            $schedule->job(new SyncLilNounTotalSupply, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenIdentities, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenImages, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenSeeds, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenSeedNames, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenBlockNumbers, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenMintTimes, 'lils')->everyMinute();
            $schedule->job(new SyncLilNounTokenColors, 'lils')->everyMinute();

            $schedule->job(new SyncNounTotalSupply, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenIdentities, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenImages, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenSeeds, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenSeedNames, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenBlockNumbers, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenMintTimes, 'nouns')->everyMinute();
            $schedule->job(new SyncNounTokenColors, 'nouns')->everyMinute();
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
