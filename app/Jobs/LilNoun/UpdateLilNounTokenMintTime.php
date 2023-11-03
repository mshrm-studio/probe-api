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
use Carbon\Carbon;

class UpdateLilNounTokenMintTime implements ShouldQueue
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

        if (is_null($lilNoun->block_number)) {
            throw new \Exception('attempting to update token mint time for token without block number.');
        }

        $block = $service->getBlock($lilNoun->block_number);

        $blockTimestamp = $block['timestamp'] ?? null;
        
        if (is_null($blockTimestamp)) {
            throw new \Exception('Timestamp not found in block for token: ' . $lilNoun->token_id);
        }

        $mintedAt = Carbon::createFromTimestamp(hexdec($blockTimestamp));

        $lilNoun->minted_at = $mintedAt;
        
        $lilNoun->save();
    }
}
