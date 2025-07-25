<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Noun;
use App\Jobs\Noun\UpdateNounTokenSettler;

class SyncNounTokenSettlers implements ShouldQueue
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
            ->whereNull('settled_by_address')
            ->where('minted_at', '<=', now()->subDays(1))
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        foreach ($nouns as $noun) {
            UpdateNounTokenSettler::dispatch($noun)->onQueue('nouns');
        }
    }
}
