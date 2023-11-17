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

class UpdateLilNounTokenID implements ShouldQueue
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

        if (is_int($lilNoun->index)) {
            $tokenId = $service->getTokenByIndex($lilNoun->index);

            if (is_int($tokenId)) {
                $lilNoun->update([
                    'token_id' => $tokenId,
                    'token_id_last_synced_at' => now()
                ]);
            } else {
                throw new \Exception('getTokenByIndex() job has not returned a numeric value.');
            }
        }
    }
}
