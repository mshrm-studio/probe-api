<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\UpdateLilNounTokenURI;
use App\Models\LilNoun;

class SyncLilNounTokenImages implements ShouldQueue
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
            ->whereNull('token_uri')
            ->whereNotNull('token_id')
            ->limit(25)
            ->get();

        \Log::info('SyncTokenImages handle() for count(lilNouns): ' . count($lilNouns));

        foreach ($lilNouns as $lilNoun) {
            UpdateLilNounTokenURI::dispatch($lilNoun);
        }
    }
}
