<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Noun;
use App\Models\NounTrait;

class SyncNounTokenSeedNames implements ShouldQueue
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
        $nouns = Noun::query()
            ->whereNull('background_name')
            ->orWhereNull('body_name')
            ->orWhereNull('accessory_name')
            ->orWhereNull('head_name')
            ->orWhereNull('glasses_name')
            ->limit(50)
            ->get();
            
        $traits = NounTrait::all();

        foreach ($nouns as $noun) {
            if (is_null($noun->head_name) && is_int($noun->head_index)) {
                $headTrait = $traits
                    ->where('layer', 'head')
                    ->where('seed_id', $noun->head_index)
                    ->first();
                
                if (!empty($headTrait)) {
                    $noun->update(['head_name' => $headTrait['name']]);
                } else {
                    throw new \Exception('Unable to find head trait in collection, head index: ' . $noun->head_index);
                }
            }

            if (is_null($noun->background_name) && is_int($noun->background_index)) {
                $backgroundTrait = $traits
                    ->where('layer', 'background')
                    ->where('seed_id', $noun->background_index)
                    ->first();
                
                if (!empty($backgroundTrait)) {
                    $noun->update(['background_name' => $backgroundTrait['name']]);
                } else {
                    throw new \Exception('Unable to find background trait in collection, background index: ' . $noun->background_index);
                }
            }

            if (is_null($noun->accessory_name) && is_int($noun->accessory_index)) {
                $accessoryTrait = $traits
                    ->where('layer', 'accessory')
                    ->where('seed_id', $noun->accessory_index)
                    ->first();
                
                if (!empty($accessoryTrait)) {
                    $noun->update(['accessory_name' => $accessoryTrait['name']]);
                } else {
                    throw new \Exception('Unable to find accessory trait in collection, accessory index: ' . $noun->accessory_index);
                }
            }

            if (is_null($noun->body_name) && is_int($noun->body_index)) {
                $bodyTrait = $traits
                    ->where('layer', 'body')
                    ->where('seed_id', $noun->body_index)
                    ->first();
                
                if (!empty($bodyTrait)) {
                    $noun->update(['body_name' => $bodyTrait['name']]);
                } else {
                    throw new \Exception('Unable to find body trait in collection, body index: ' . $noun->body_index);
                }
            }

            if (is_null($noun->glasses_name) && is_int($noun->glasses_index)) {
                $glassesTrait = $traits
                    ->where('layer', 'glasses')
                    ->where('seed_id', $noun->glasses_index)
                    ->first();
                
                if (!empty($glassesTrait)) {
                    $noun->update(['glasses_name' => $glassesTrait['name']]);
                } else {
                    throw new \Exception('Unable to find glasses trait in collection, glasses index: ' . $noun->glasses_index);
                }
            }
        }
    }
}
