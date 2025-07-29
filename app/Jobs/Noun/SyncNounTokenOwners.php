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
        // \Log::info("Starting SyncNounTokenOwners job...");

        $hasMore = true;
        $skip = 0;
        $limit = 1000;

        // $totalProcessed = 0;
        // $totalUpdated = 0;
        
        $apiKey = config('services.subgraph.api_key');
        $subgraphId = config('services.nouns.subgraph_id');
        $endpoint = 'https://gateway.thegraph.com/api/subgraphs/id/' . $subgraphId;

        while ($hasMore) {
            // \Log::info("Fetching nouns batch: skip=$skip, limit=$limit");

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
                \Log::error("GraphQL request failed with status {$response->status()} at skip $skip.");
                throw new \Exception("GraphQL request failed with status {$response->status()} at skip $skip.");
                break;
            }

            $nouns = $response->json('data.nouns');

            $batchCount = is_array($nouns) ? count($nouns) : 0;

            // \Log::info("Fetched $batchCount nouns.");
            
            if (empty($nouns)) {
                \Log::warning("No nouns returned for skip $skip. Exiting loop.");
                $hasMore = false;
                break;
            }

            foreach ($nouns as $noun) {
                // $totalProcessed++;
                $nounTokenId = $noun['id'] ?? null;
                $ownerAddress = $noun['owner']['id'] ?? null;

                if ($nounTokenId !== null && !empty($ownerAddress)) {
                    $nounToUpdate = Noun::where('token_id', $nounTokenId)->first();

                    if (!empty($nounToUpdate)) {
                        if ($nounToUpdate->owner_address !== $ownerAddress) {
                            // $totalUpdated++;

                            $nounToUpdate->update([
                                'owner_address' => $ownerAddress,
                            ]);
                        }
                    } else {
                        \Log::warning("Noun with token_id $nounTokenId not found in database.");
                    }
                } else {
                    \Log::warning("Malformed noun data: " . json_encode($noun));
                }
            }

            if (count($nouns) < $limit) {
                // \Log::info("Last batch received. Exiting pagination.");
                $hasMore = false;
            } else {
                $skip += $limit;
            }
        }

        // \Log::info("SyncNounTokenOwners job finished. Processed: $totalProcessed, Updated: $totalUpdated.");
    }
}
