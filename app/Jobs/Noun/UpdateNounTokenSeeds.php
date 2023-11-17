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

class UpdateNounTokenSeeds implements ShouldQueue
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
            throw new \Exception('attempting to update token seeds for token without ID. DB ID is ' . $noun->id);
        }

        $hasAtLeastOneEmptySeed = (
            is_null($noun->background_index) ||
            is_null($noun->body_index) ||
            is_null($noun->accessory_index) ||
            is_null($noun->head_index) ||
            is_null($noun->glasses_index)
        );

        if ($hasAtLeastOneEmptySeed) {
            $seeds = $service->getSeeds($noun->token_id);

            if (count($seeds) > 0) {
                $noun->update([
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
