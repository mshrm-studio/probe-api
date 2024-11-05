<?php

namespace App\Jobs\NounTrait;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\NounTrait\UpdateNounTraitPng;
use App\Jobs\NounTrait\UpdateNounTraitRle;
use App\Models\NounTrait;

class SyncNounTraitImages implements ShouldQueue
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
        $nounTraitsWithNoPngPath = NounTrait::query()
            ->whereNull('png_path')
            ->whereNotNull('svg_path')
            ->limit(50)
            ->get();

        foreach ($nounTraitsWithNoPngPath as $nounTrait) {
            UpdateNounTraitPng::dispatch($nounTrait)->onQueue('nouns');
            UpdateNounTraitRle::dispatch($nounTrait)->onQueue('nouns');
        }
    }
}
