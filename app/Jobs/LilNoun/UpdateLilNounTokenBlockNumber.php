<?php

namespace App\Jobs\LilNoun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LilNoun;
use App\Services\LilNounsService;

class UpdateLilNounTokenBlockNumber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lilNoun;

    /**
     * Create a new job instance.
     */
    public function __construct(LilNoun $lilNoun)
    {
        $this->lilNoun = $lilNoun;
    }

    /**
     * Execute the job.
     */
    public function handle(LilNounsService $service): void
    {
        $lilNoun = LilNoun::findOrFail($this->lilNoun->id);

        if (is_null($lilNoun->token_id)) {
            throw new \Exception('attempting to update token block number for token without ID.');
        }

        $mintEvent = $service->getMintEvent($lilNoun->token_id);

        $blockNumber = $mintEvent['blockNumber'] ?? null;
        
        if (is_null($blockNumber)) {
            throw new \Exception('Block number not found in mint event for token ID ' . $lilNoun->token_id);
        }

        $lilNoun->block_number = $blockNumber;
        
        $lilNoun->save();
    }
}
