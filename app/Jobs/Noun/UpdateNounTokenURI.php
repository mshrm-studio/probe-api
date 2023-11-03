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

class UpdateNounTokenURI implements ShouldQueue
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

        if (is_null($noun->token_uri) && is_int($noun->token_id)) {
            $tokenUri = $service->getTokenURI($noun->token_id);

            if (is_string($tokenUri)) {
                $noun->update(['token_uri' => $tokenUri]);
            } else {
                throw new \Exception('getTokenURI() job has not returned a string value.');
            }
        }
    }
}
