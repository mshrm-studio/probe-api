<?php

namespace App\Jobs\LilNoun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LilNoun;
use App\Services\LilNounsTraitService;

class SyncLilNounTokenSeedNames implements ShouldQueue
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
    public function handle(LilNounsTraitService $traitService): void
    {
        $lilNouns = LilNoun::query()
            ->whereNull('background_name')
            ->orWhereNull('body_name')
            ->orWhereNull('accessory_name')
            ->orWhereNull('head_name')
            ->orWhereNull('glasses_name')
            ->limit(25)
            ->get();
            
        $traits = $traitService->getItems();

        foreach ($lilNouns as $lilNoun) {
            if (is_null($lilNoun->head_name) && is_int($lilNoun->head_index)) {
                $headTrait = $traits
                    ->where('layer', 'head')
                    ->where('seed_id', $lilNoun->head_index)
                    ->first();
                
                if (!empty($headTrait)) {
                    $lilNoun->update(['head_name' => $headTrait['name']]);
                } else {
                    throw new \Exception('Unable to find head trait in collection, head index: ' . $lilNoun->head_index);
                }
            }

            if (is_null($lilNoun->background_name) && is_int($lilNoun->background_index)) {
                $backgroundTrait = $traits
                    ->where('layer', 'background')
                    ->where('seed_id', $lilNoun->background_index)
                    ->first();
                
                if (!empty($backgroundTrait)) {
                    $lilNoun->update(['background_name' => $backgroundTrait['name']]);
                } else {
                    throw new \Exception('Unable to find background trait in collection, background index: ' . $lilNoun->background_index);
                }
            }

            if (is_null($lilNoun->accessory_name) && is_int($lilNoun->accessory_index)) {
                $accessoryTrait = $traits
                    ->where('layer', 'accessory')
                    ->where('seed_id', $lilNoun->accessory_index)
                    ->first();
                
                if (!empty($accessoryTrait)) {
                    $lilNoun->update(['accessory_name' => $accessoryTrait['name']]);
                } else {
                    throw new \Exception('Unable to find accessory trait in collection, accessory index: ' . $lilNoun->accessory_index);
                }
            }

            if (is_null($lilNoun->body_name) && is_int($lilNoun->body_index)) {
                $bodyTrait = $traits
                    ->where('layer', 'body')
                    ->where('seed_id', $lilNoun->body_index)
                    ->first();
                
                if (!empty($bodyTrait)) {
                    $lilNoun->update(['body_name' => $bodyTrait['name']]);
                } else {
                    throw new \Exception('Unable to find body trait in collection, body index: ' . $lilNoun->body_index);
                }
            }

            if (is_null($lilNoun->glasses_name) && is_int($lilNoun->glasses_index)) {
                $glassesTrait = $traits
                    ->where('layer', 'glasses')
                    ->where('seed_id', $lilNoun->glasses_index)
                    ->first();
                
                if (!empty($glassesTrait)) {
                    $lilNoun->update(['glasses_name' => $glassesTrait['name']]);
                } else {
                    throw new \Exception('Unable to find glasses trait in collection, glasses index: ' . $lilNoun->glasses_index);
                }
            }
        }
    }
}
