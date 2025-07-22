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

class SyncNounTokenOwners implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $start = microtime(true);
        $hasMore = true;
        $skip = 0;
        $limit = 1000;
        
        $apiKey = config('services.subgraph.api_key');
        $subgraphId = config('services.nouns.subgraph_id');
        $endpoint = 'https://gateway.thegraph.com/api/subgraphs/id/' . $subgraphId;

        while ($hasMore) {
            $query = <<<GRAPHQL
            {
                nouns(first: $limit, skip: $skip) {
                    id
                    owner {
                        id
                    }
                }
            }
            GRAPHQL;

            $response = Http::withToken($apiKey)->post($endpoint, [
                'query' => $query,
            ]);

            if (!$response->ok()) {
                throw new \Exception("GraphQL request failed with status {$response->status()} at skip $skip.");
                break;
            }

            $nouns = $response->json('data.nouns');
            if (empty($nouns)) {
                $hasMore = false;
                break;
            }

            \Log::info('Number of nouns fetched: ' . count($nouns));

            foreach ($nouns as $noun) {
                $nounTokenId = $noun['id'] ?? null;
                $ownerAddress = $noun['owner']['id'] ?? null;

                if (!empty($nounTokenId) && !empty($ownerAddress)) {
                    // \Log::info("Syncing owner for Noun Token ID: $nounTokenId, Owner Address: $ownerAddress");

                    Noun::query()
                        ->where('token_id', $nounTokenId)
                        ->where('owner_address', '!=', $ownerAddress)
                        ->update(['owner_address' => $ownerAddress]);
                }
            }

            if (count($nouns) < $limit) {
                $hasMore = false;
            } else {
                $skip += $limit;
            }
        }

        $elapsed = microtime(true) - $start;
        \Log::info("SyncNounTokenOwners completed in {$elapsed} seconds");
    }
}
