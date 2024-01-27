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

        foreach ($nouns as $noun) {
            $imagick = new \Imagick();

            $svgContent = Storage::get($noun->svg_path);
            
            $imagick->readImageBlob($svgContent);
            
            $imagick->setImageFormat('png24');

            // Get background colour to ignore
            $backgroundColor = $this->getBackgroundColor($svgContent);
                                
            // Process the noun to get histogram/area
            $histogram = $imagick->getImageHistogram();

            // Get area
            $area = $this->calculateArea($histogram, $backgroundColor);
            
            // Get the weight
            $weight = $this->calculateWeight($histogram, $backgroundColor);

            // Build response object and return
            $nounStats = [];
            $nounStats['histogram'] = $this->formatHistogram($histogram);
            $nounStats['area'] = $area;
            $nounStats['weight'] = $weight;

            // Release memory
            $imagick->clear();
            $imagick->destroy();
            
            \Log::info('***********');
            \Log::info('Noun ' . $noun->id);
            \Log::info('area');
            \Log::info($nounStats['area']);
            \Log::info('weight');
            \Log::info($nounStats['weight']);
            \Log::info('histogram');
            \Log::info($nounStats['histogram']);
        }   
    }

    private function calculateArea(array $histogram, string $backgroundColor): int
    {
        $area = 0;

        $backgroundColorRGB = $this->convertHexToRGB($backgroundColor);
        
        // Iterate over histogram and add all pixels that aren't background color
        foreach ($histogram as $pixel) {
            $colorRGB = $pixel->getColor();
            
            if (
                $colorRGB['r'] != $backgroundColorRGB['r'] && 
                $colorRGB['g'] != $backgroundColorRGB['g'] && 
                $colorRGB['b'] != $backgroundColorRGB['b']
            ) {
                $area += $pixel->getColorCount();
            }
        }
        
        return $area;
    }

    private function calculateWeight(array $histogram, string $backgroundColor): int
    {
        $weight = 0;

        $backgroundColorRGB = $this->convertHexToRGB($backgroundColor);

        // Iterate over histogram and add all pixels that aren't background color
        foreach ($histogram as $pixel) {
            $colorRGB = $pixel->getColor();

            if (
                $colorRGB['r'] != $backgroundColorRGB['r'] && 
                $colorRGB['g'] != $backgroundColorRGB['g'] && 
                $colorRGB['b'] != $backgroundColorRGB['b']
            ) {
                // Get avg pixel (grey scale) 
                $greyScalePixel = ($colorRGB['r'] + $colorRGB['g'] + $colorRGB['b']) / 3;

                // Times the pixel count for the color by the grey scale pixel (mass for color)
                $weight += ($pixel->getColorCount() * $greyScalePixel);
            }
        }
    
        return $weight;
    }

    private function getBackgroundColor(string $svg): string 
    {
        // LoadXML
        $xml = simplexml_load_string($svg);

        // Get 100% fill color
        $backgroundElement = $xml->rect[0];

        return $backgroundElement["fill"];
    }

    private function convertHexToRGB(string $hexColor): array
    {
        sscanf($hexColor, "rgb(%d,%d,%d)", $r, $g, $b);

        return ['r' => $r, 'g' => $g, 'b' => $b];
    }
    
    private function formatHistogram(array $histogram): array
    {
        $formattedHistogram = [];
        
        foreach($histogram as $pixel) {
            $colors = $pixel->getColor();

            $key = sprintf("#%02x%02x%02x", $colors['r'], $colors['g'], $colors['b']);
                   
            $formattedHistogram[$key] = $pixel->getColorCount();
            $formattedHistogram['rgb(' . $colors['r'] . ',' . $colors['g'] . ',' . $colors['b'] . ')'] = $pixel->getColorCount();
        }
    
        return $formattedHistogram;
    }
}


        // foreach ($nouns as $noun) {
            
        //     // Read the SVG file content
        //     $svgContent = Storage::get($noun->svg_path);
        //     $xml = simplexml_load_string($svgContent);
        //     $namespaces = $xml->getNamespaces(true);
        
        //     $colors = [];

        //     $width = (string) $xml['width'];
        //     $height = (string) $xml['height'];
                    
        //     // Specify the namespace if needed
        //     $svgNamespace = $namespaces[''] ?? null;
        
        //     // Loop through rect elements
        //     foreach ($xml->children($svgNamespace)->rect as $rect) {
        //         $attributes = $rect->attributes();
        
        //         if (isset($attributes->fill)) {
        //             $color = (string) $attributes->fill;
        //             $colors[] = $color; // Collect colors
        //         }
        //     }
        
        //     \Log::info('**************************');
        //     \Log::info("Token ID: $noun->token_id");
        //     \Log::info("Width: $width, Height: $height");
        //     $colorCount = count($colors);
        //     \Log::info("Color count: $colorCount");
        //     \Log::info($colors);
        // }     