<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Noun;
use App\Jobs\Noun\UpdateNounTokenID;

class SyncNounTokenIdentities implements ShouldQueue
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
            ->where(function ($query) {
                $query
                    ->whereNull('token_id')
                    ->orWhereNull('token_id_last_synced_at')
                    ->orWhere('token_id_last_synced_at', '<', now()->subWeek());
            })
            ->whereNotNull('index')
            ->limit(50)
            ->get();

        foreach ($nouns as $noun) {
            UpdateNounTokenID::dispatch($noun)->onQueue('nouns');
        }
    }
}
