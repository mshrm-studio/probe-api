<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\LilNounsService;
use App\Models\LilNoun;
use App\Jobs\CreateLilNounWithIndex;

class SyncLilNounTotalSupply implements ShouldQueue
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
    public function handle(LilNounsService $service): void
    {
        $totalSupply = $service->getTotalSupply();

        // \Log::info('SyncTotalSupply handle() $totalSupply: ' . $totalSupply);

        $existingIndices = LilNoun::pluck('index')->all();

        // \Log::info('SyncTotalSupply handle(): ', ['existingIndices' => $existingIndices]);

        $allIndices = range(0, $totalSupply);

        $missingIndices = array_diff($allIndices, $existingIndices);

        // \Log::info('SyncTotalSupply handle(): ', ['missingIndices' => $missingIndices]);

        $numberOfMissingIndicesToProcess = 25;

        $missingIndicesToProcess = array_slice($missingIndices, 0, $numberOfMissingIndicesToProcess);

        // \Log::info('SyncTotalSupply handle(): ', ['missingIndicesToProcess' => $missingIndicesToProcess]);

        foreach ($missingIndicesToProcess as $index) {
            // \Log::info('SyncTotalSupply handle() dispatch: ' . $index);

            CreateLilNounWithIndex::dispatch($index);
        }        
    }
}
