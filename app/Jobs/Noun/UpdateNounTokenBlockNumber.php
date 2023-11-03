<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Noun;
use App\Services\NounsService;

class UpdateNounTokenBlockNumber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $noun;

    /**
     * Create a new job instance.
     */
    public function __construct(Noun $noun)
    {
        $this->noun = $noun;
    }

    /**
     * Execute the job.
     */
    public function handle(NounsService $service): void
    {
        $noun = Noun::findOrFail($this->noun->id);

        if (is_null($noun->token_id)) {
            throw new \Exception('attempting to update token block number for token without ID.');
        }

        $mintEvent = $service->getMintEvent($noun->token_id);

        $blockNumber = $mintEvent['blockNumber'] ?? null;
        
        if (is_null($blockNumber)) {
            throw new \Exception('Block number not found in mint event for token ID ' . $noun->token_id);
        }

        $noun->block_number = $blockNumber;
        
        $noun->save();
    }
}
