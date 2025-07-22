<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Noun;

class UpdateNounTokenOwner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Noun $noun,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {       
        // TODO

        $query = <<<'GRAPHQL'
        {
            nouns(first: 1000) {
                id
                owner {
                    id
                }
            }
        }
        GRAPHQL;

        $endpoint = 'https://api.thegraph.com/subgraphs/id/' . config('services.nouns.subgraph_id');

        $response = Http::withToken(config('services.subgraph.api_key'))->post($endpoint, [
            'query' => $query,
        ]);

        dd($response);
    }
}
