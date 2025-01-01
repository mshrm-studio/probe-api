<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Noun;
use App\Services\NounsService;
use Exception;
use Illuminate\Support\Facades\Cache;

class UpdateNounTokenSettler implements ShouldQueue
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
        \Log::info('Updating token settler for noun token ' . $this->noun->token_id);
        
        $noun = Noun::findOrFail($this->noun->id);

        // Ensure required fields exist
        if (is_null($noun->block_number) || is_null($noun->token_id)) {
            throw new Exception('Block number or token ID is missing for noun token ' . $noun->token_id);
        }

        try {
            // Fetch logs for this block
            $logs = $service->getAuctionLogs($noun->token_id, $noun->block_number);

            \Log::info('Auction logs: ' . json_encode($logs));

            // Example Auction logs: [
            //     {
            //         "address":"0x830bd73e4184cef73443c15111a1df14e495c706",
            //         "blockHash":"0x605ec5bbe4ede1308680dc80df79e8493b9a6aa46e1ca14b2a092c7723b17f1e",
            //         "blockNumber":"0x1484a40",
            //         "data":"0x00000000000000000000000029b612755a3617108a060c5b55b3b559c1a2afd4000000000000000000000000000000000000000000000000250dbeda8e4b0000",
            //         "logIndex":"0x73",
            //         "removed":false,
            //         "topics":[
            //             "0xc9f72b276a388619c6d185d146697036241880c36654b1a3ffdad07c24038d99",
            //             "0x0000000000000000000000000000000000000000000000000000000000000555"
            //         ],
            //         "transactionHash":"0x67c19d0f89fa80b6f01fde8a321b6fdd1799c54d270e54090f708f4f54e9c0fe",
            //         "transactionIndex":"0x1d"
            //     },
            //     {
            //         "address":"0x830bd73e4184cef73443c15111a1df14e495c706",
            //         "blockHash":"0x605ec5bbe4ede1308680dc80df79e8493b9a6aa46e1ca14b2a092c7723b17f1e",
            //         "blockNumber":"0x1484a40",
            //         "data":"0x",
            //         "logIndex":"0x74",
            //         "removed":false,
            //         "topics":[
            //             "0xf445afb110f5e782fc78bf23e7066d3c5a95f7b57bd25fb718a29ad0287db2b9",
            //             "0x0000000000000000000000000000000000000000000000000000000000000555",
            //             "0x0000000000000000000000000000000000000000000000000000000000000009"
            //         ],
            //         "transactionHash":"0x67c19d0f89fa80b6f01fde8a321b6fdd1799c54d270e54090f708f4f54e9c0fe",
            //         "transactionIndex":"0x1d"
            //     }
            // ]

            if (is_array($logs) && count($logs) > 0) {
                $auctionEvent = $logs[0]; // tx hashes assumed to be the same for all logs.

                \Log::info('Auction event: ' . json_encode($auctionEvent));

                if ($auctionEvent && isset($auctionEvent['transactionHash'])) {
                    \Log::info('Transaction hash: ' . $auctionEvent['transactionHash']);
                    // Get transaction details
                    $transaction = $service->getTransaction($auctionEvent['transactionHash']);

                    \Log::info('Transaction details: ' . json_encode($transaction));

                    if (isset($transaction['from'])) {
                        // Update settled address
                        $noun->update(['settled_by_address' => $transaction['from']]);
                        Cache::forget('Noun-Settlers');
                    } else {
                        throw new Exception('Transaction "from" address not found.');
                    }
                }
            } else {
                throw new Exception('No auction logs found for block number ' . $noun->block_number);
            }
        } catch (Exception $e) {
            throw new Exception('Failed to fetch "Settled" auction events: ' . $e->getMessage());
        }
    }
}
