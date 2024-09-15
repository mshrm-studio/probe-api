<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Noun;
use App\Jobs\Noun\UpdateNounTokenMintTime;

class SyncNounTokenMintTimes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::info('SyncNounTokenMintTimes');

        $nouns = Noun::query()
            ->whereNull('minted_at')
            ->whereNotNull('block_number')
            ->limit(50)
            ->get();

        foreach ($nouns as $noun) {
            UpdateNounTokenMintTime::dispatch($noun)->onQueue('nouns');
        }
    }
}
