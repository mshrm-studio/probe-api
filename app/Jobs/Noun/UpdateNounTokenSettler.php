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
        $noun = Noun::findOrFail($this->noun->id);

        // Ensure required fields exist
        if (is_null($noun->block_number) || is_null($noun->token_id)) {
            throw new Exception('Block number or token ID is missing for noun ID ' . $noun->id);
        }

        try {
            // Fetch logs for this block
            $logs = $service->getAuctionLogs($noun->block_number);

            \Log::info('Auction logs', json_encode($logs));

            if (is_array($logs) && count($logs) === 1) {
                $auctionEvent = $logs[0];

                if ($auctionEvent && isset($auctionEvent['transactionHash'])) {
                    // Get transaction details
                    $transaction = $service->getTransaction($auctionEvent['transactionHash']);

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
