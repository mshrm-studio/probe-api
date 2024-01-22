<?php

namespace App\Jobs\Noun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Noun;
use Illuminate\Support\Facades\Storage;

class SyncNounTokenColors implements ShouldQueue
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
            ->whereNotNull('svg_path')
            ->limit(50)
            ->get();

        // TODO: check for emptiness of color column when migrated

        foreach ($nouns as $noun) {
            
            // Read the SVG file content
            $svgContent = Storage::get($noun->svg_path);
            $xml = simplexml_load_string($svgContent);
            $namespaces = $xml->getNamespaces(true);
        
            $colors = [];

            $width = (string) $xml['width'];
            $height = (string) $xml['height'];
                    
            // Specify the namespace if needed
            $svgNamespace = $namespaces[''] ?? null;
        
            // Loop through rect elements
            foreach ($xml->children($svgNamespace)->rect as $rect) {
                $attributes = $rect->attributes();
        
                if (isset($attributes->fill)) {
                    $color = (string) $attributes->fill;
                    $colors[] = $color; // Collect colors
                }
            }
        
            \Log::info('**************************');
            \Log::info("Token ID: $noun->token_id");
            \Log::info("Width: $width, Height: $height");
            $colorCount = count($colors);
            \Log::info("Color count: $colorCount");
            \Log::info($colors);
        }        
    }
}
