<?php

namespace App\Jobs\LilNoun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\LilNoun\UpdateLilNounTokenURI;
use App\Jobs\LilNoun\UpdateLilNounTokenSvg;
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
            ->limit(50)
            ->get();

        foreach ($lilNouns as $lilNoun) {
            UpdateLilNounTokenURI::dispatch($lilNoun)->onQueue('lils');
        }

        $lilNounsWithoutSvg = LilNoun::query()
            ->whereNull('svg_path')
            ->whereNotNull('token_uri')
            ->limit(50)
            ->get();

        foreach ($lilNounsWithoutTokenUri as $lilNoun) {
            UpdateLilNounTokenSvg::dispatch($lilNoun)->onQueue('lils');
        }
    }
}
