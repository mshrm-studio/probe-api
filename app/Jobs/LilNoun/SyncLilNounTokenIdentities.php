<?php

namespace App\Jobs\LilNoun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LilNoun;
use App\Jobs\LilNoun\UpdateLilNounTokenID;

class SyncLilNounTokenIdentities implements ShouldQueue
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
            ->whereNull('token_id')
            ->whereNotNull('index')
            ->limit(25)
            ->get();

        foreach ($lilNouns as $lilNoun) {
            UpdateLilNounTokenID::dispatch($lilNoun)->onQueue('lils');
        }
    }
}
