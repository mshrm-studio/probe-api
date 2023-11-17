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
                if (is_null($noun->token_id)) {
                    $noun->update([
                        'token_id' => $tokenId,
                        'token_id_last_synced_at' => now()
                    ]);
                } else if ($noun->token_id != $tokenId) {
                    $noun->update([
                        'background_index' => null,
                        'background_name' => null,
                        'accessory_index' => null,
                        'accessory_name' => null,
                        'glasses_index' => null,
                        'glasses_name' => null,
                        'head_index' => null,
                        'head_name' => null,
                        'body_index' => null,
                        'body_name' => null,
                        'token_id' => $tokenId,
                        'token_id_last_synced_at' => now()
                    ]);
                }
            } else {
                throw new \Exception('getTokenByIndex() job has not returned a numeric value.');
            }
        }
    }
}
