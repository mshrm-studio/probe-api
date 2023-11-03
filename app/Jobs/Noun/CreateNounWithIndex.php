<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Noun;

class CreateNounWithIndex implements ShouldQueue
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
        if (! Noun::where('index', $this->index)->exists()) {
            $noun = Noun::create(['index' => $this->index]);
        }
    }
}
