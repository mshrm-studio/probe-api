<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\Noun\UpdateNounTokenURI;
use App\Jobs\Noun\UpdateNounTokenSvg;
use App\Models\Noun;

class SyncNounTokenImages implements ShouldQueue
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
        $nounsWithoutTokenUri = Noun::query()
            ->whereNull('token_uri')
            ->whereNotNull('token_id')
            ->limit(50)
            ->get();

        foreach ($nounsWithoutTokenUri as $noun) {
            UpdateNounTokenURI::dispatch($noun)->onQueue('nouns');
        }

        $nounsWithoutSvg = Noun::query()
            ->whereNull('svg_path')
            ->whereNotNull('token_uri')
            ->limit(50)
            ->get();

        foreach ($nounsWithoutSvg as $noun) {
            UpdateNounTokenSvg::dispatch($noun)->onQueue('nouns');
        }
    }
}
