<?php

namespace App\Jobs\LilNoun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LilNoun;
use App\Jobs\LilNoun\UpdateLilNounTokenMintTime;

class SyncLilNounTokenMintTimes implements ShouldQueue
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
        $lilNouns = LilNoun::query()
            ->whereNull('minted_at')
            ->whereNotNull('block_number')
            ->limit(50)
            ->get();

        foreach ($lilNouns as $lilNoun) {
            UpdateLilNounTokenMintTime::dispatch($lilNoun)->onQueue('lils');
        }
    }
}
