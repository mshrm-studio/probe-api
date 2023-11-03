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
use Carbon\Carbon;

class UpdateNounTokenMintTime implements ShouldQueue
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

        if (is_null($noun->block_number)) {
            throw new \Exception('attempting to update token mint time for token without block number.');
        }

        $block = $service->getBlock($noun->block_number);

        $blockTimestamp = $block['timestamp'] ?? null;
        
        if (is_null($blockTimestamp)) {
            throw new \Exception('Timestamp not found in block for token: ' . $noun->token_id);
        }

        $mintedAt = Carbon::createFromTimestamp(hexdec($blockTimestamp));

        $noun->minted_at = $mintedAt;
        
        $noun->save();
    }
}
