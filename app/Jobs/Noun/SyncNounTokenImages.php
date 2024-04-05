<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\Noun\UpdateNounTokenURI;
use App\Jobs\Noun\UpdateNounTokenPng;
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
        \Log::info('SyncNounTokenImages job started.');

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

        $nounsWithoutPng = Noun::query()
            ->whereNull('png_path')
            ->whereNotNull('svg_path')
            ->limit(50)
            ->get();

        \Log::info('nounsWithoutPng:', count($nounsWithoutPng));

        foreach ($nounsWithoutPng as $noun) {
            UpdateNounTokenPng::dispatch($noun)->onQueue('nouns');
        }
    }
}
