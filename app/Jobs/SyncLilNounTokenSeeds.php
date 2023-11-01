<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LilNoun;
use App\Jobs\UpdateLilNounTokenSeeds;

class SyncLilNounTokenSeeds implements ShouldQueue
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
            ->whereNotNull('token_id')
            ->whereNull('background_index')
            ->orWhereNull('body_index')
            ->orWhereNull('accessory_index')
            ->orWhereNull('head_index')
            ->orWhereNull('glasses_index')
            ->limit(25)
            ->get();

        // \Log::info('SyncTokenSeeds handle() for count(lilNouns): ' . count($lilNouns));

        foreach ($lilNouns as $lilNoun) {
            UpdateLilNounTokenSeeds::dispatch($lilNoun);
        } 
    }
}
