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

class UpdateNounTokenID implements ShouldQueue
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

        if (is_int($noun->index)) {
            $tokenId = $service->getTokenByIndex($noun->index);

            if (is_int($tokenId)) {
                $noun->update([
                    'token_id' => $tokenId,
                    'token_id_last_synced_at' => now()
                ]);
            } else {
                throw new \Exception('getTokenByIndex() job has not returned a numeric value.');
            }
        }
    }
}
