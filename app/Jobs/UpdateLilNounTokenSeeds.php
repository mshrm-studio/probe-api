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
use Illuminate\Support\Collection;

class UpdateLilNounTokenSeeds implements ShouldQueue
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
            throw new \Exception('attempting to update token seeds for token without ID.');
        }

        $hasAtLeastOneEmptySeed = (
            is_null($lilNoun->background_index) ||
            is_null($lilNoun->body_index) ||
            is_null($lilNoun->accessory_index) ||
            is_null($lilNoun->head_index) ||
            is_null($lilNoun->glasses_index)
        );

        if ($hasAtLeastOneEmptySeed) {
            // \Log::info('UpdateLilNounTokenSeeds handle(): ', ['$lilNoun->token_id' => $lilNoun->token_id]);

            $seeds = $service->getSeeds($lilNoun->token_id);

            // \Log::info('UpdateLilNounTokenSeeds handle(): ', ['$seeds' => $seeds]);

            if (count($seeds) > 0) {
                $lilNoun->update([
                    'background_index' => is_int($seeds['background']) ? $seeds['background'] : null,
                    'body_index' => is_int($seeds['body']) ? $seeds['body'] : null,
                    'accessory_index' => is_int($seeds['accessory']) ? $seeds['accessory'] : null,
                    'head_index' => is_int($seeds['head']) ? $seeds['head'] : null,
                    'glasses_index' => is_int($seeds['glasses']) ? $seeds['glasses'] : null
                ]);
            } else {
                throw new \Exception('getSeeds() job has returned an empty seeds array.');
            }
        }
    }
}
