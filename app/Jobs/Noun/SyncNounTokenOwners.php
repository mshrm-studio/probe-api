<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Noun;
use App\Jobs\Noun\UpdateNounTokenOwner;

class SyncNounTokenOwners implements ShouldQueue
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
        // ->where(function ($query) {
            //     $query
            //         ->whereNull('owner_address')
            //         ->orWhereNull('token_id_last_synced_at')
            //         ->orWhere('token_id_last_synced_at', '<', now()->subDays(3));
            // })
            
        $nouns = Noun::query()
            ->orderByDesc('id')
            ->limit(25)
            ->get();

        foreach ($nouns as $noun) {
            UpdateNounTokenOwner::dispatch($noun)->onQueue('nouns');
        }
    }
}
