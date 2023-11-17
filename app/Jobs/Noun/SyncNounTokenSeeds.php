<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Noun;
use App\Jobs\Noun\UpdateNounTokenSeeds;

class SyncNounTokenSeeds implements ShouldQueue
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
            ->whereNotNull('token_id')
            ->where(function ($query) {
                $query
                    ->whereNull('background_index')
                    ->orWhereNull('body_index')
                    ->orWhereNull('accessory_index')
                    ->orWhereNull('head_index')
                    ->orWhereNull('glasses_index');
            })
            ->limit(25)
            ->get();

        foreach ($nouns as $noun) {
            UpdateNounTokenSeeds::dispatch($noun)->onQueue('nouns');
        } 
    }
}
