<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LilNoun;
use App\Services\LilNounsService;

class UpdateLilNounTokenURI implements ShouldQueue
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

        if (is_null($lilNoun->token_uri) && is_int($lilNoun->token_id)) {
            \Log::info('UpdateLilNounTokenURI handle(): ', ['$lilNoun->token_id' => $lilNoun->token_id]);

            $tokenUri = $service->getTokenURI($lilNoun->token_id);

            if (is_string($tokenUri)) {
                $lilNoun->update(['token_uri' => $tokenUri]);
            } else {
                throw new \Exception('getTokenURI() job has not returned a string value.');
            }
        }
    }
}
