<?php

namespace App\Jobs\LilNoun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LilNoun;

class CreateLilNounWithIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $index;

    /**
     * Create a new job instance.
     */
    public function __construct(int $index)
    {
        $this->index = $index;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (! LilNoun::where('index', $this->index)->exists()) {
            $lilNoun = LilNoun::create(['index' => $this->index]);
        }
    }
}
