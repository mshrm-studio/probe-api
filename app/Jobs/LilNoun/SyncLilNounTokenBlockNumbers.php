<?php

namespace App\Jobs\LilNoun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LilNoun;
use App\Jobs\LilNoun\UpdateLilNounTokenBlockNumber;

class SyncLilNounTokenBlockNumbers implements ShouldQueue
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
            ->whereNull('block_number')
            ->whereNotNull('token_id')
            ->limit(50)
            ->get();

        foreach ($lilNouns as $lilNoun) {
            UpdateLilNounTokenBlockNumber::dispatch($lilNoun)->onQueue('lils');
        }
    }
}
