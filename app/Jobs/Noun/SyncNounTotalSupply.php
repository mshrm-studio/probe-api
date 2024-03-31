<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\NounsService;
use App\Models\Noun;
use App\Jobs\Noun\CreateNounWithIndex;

class SyncNounTotalSupply implements ShouldQueue
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
    public function handle(NounsService $service): void
    {
        $totalSupply = $service->getTotalSupply();

        if ($totalSupply > 0) {
            Noun::where('index', '>', $totalSupply - 1)->delete();

            $existingIndices = Noun::pluck('index')->all();

            // -1
            // ... total supply of 7166
            // ... do not want to get tokenByIndex of 7166
            // ... does not exist
            $allIndices = range(0, $totalSupply - 1);
    
            $missingIndices = array_diff($allIndices, $existingIndices);
    
            $numberOfMissingIndicesToProcess = 25;
    
            $missingIndicesToProcess = array_slice($missingIndices, 0, $numberOfMissingIndicesToProcess);
    
            foreach ($missingIndicesToProcess as $index) {
                if (config('app.env') == 'production') {
                    CreateNounWithIndex::dispatch($index)->onQueue('nouns');
                } else if ($index < 150) {
                    CreateNounWithIndex::dispatch($index)->onQueue('nouns');
                }
            }       
        }  
    }
}
