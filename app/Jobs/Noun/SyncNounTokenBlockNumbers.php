<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Noun;
use App\Jobs\LilNoun\UpdateNounTokenBlockNumber;

class SyncNounTokenBlockNumbers implements ShouldQueue
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
        $nouns = Noun::query()
            ->whereNull('block_number')
            ->whereNotNull('token_id')
            ->limit(25)
            ->get();

        foreach ($nouns as $noun) {
            UpdateNounTokenBlockNumber::dispatch($noun)->onQueue('nouns');
        }
    }
}
