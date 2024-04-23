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
            ->whereNull('area')
            ->whereNull('color_histogram')
            ->whereNull('weight')
            ->limit(100)
            ->get();

        foreach ($nouns as $noun) {          
            $imagick = new \Imagick();

            $svgContent = Storage::get($noun->svg_path);
            
            $imagick->readImageBlob($svgContent);

            $imagick->setImageFormat('png24');

            \Log::info('resizeImage(32, 32, \Imagick::FILTER_POINT, 1)');

            $imagick->resizeImage(32, 32, \Imagick::FILTER_POINT, 1);
            
            $backgroundColorHex = $this->getBackgroundColorHex($svgContent);
                                
            $histogram = $imagick->getImageHistogram();

            $area = $this->calculateArea($histogram, $backgroundColorHex);
            
            $weight = $this->calculateWeight($histogram, $backgroundColorHex);

            $formattedHistogram = $this->formatHistogram($histogram);

            // Release memory
            $imagick->clear();
            $imagick->destroy();

            $noun->update([
                'area' => $area, // out of 102,400
                'color_histogram' => $formattedHistogram,
                'weight' => $weight, // out of 26,112,000
            ]);
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

    private function getBackgroundColorHex(string $svg): string 
    {
        // LoadXML
        $xml = simplexml_load_string($svg);

        // Get 100% fill color
        $backgroundElement = $xml->rect[0];

        // EG #e1d7d5
        return $backgroundElement["fill"];
    }

    private function convertHexToRGB(string $hexColor): array
    {
        // Strip '#' if it's present
        $hexColor = ltrim($hexColor, '#');

        // Parse the hex color string
        if (strlen($hexColor) == 6) {
            list($r, $g, $b) = sscanf($hexColor, "%02x%02x%02x");
        } else if (strlen($hexColor) == 3) {
            // In case of a 3-character hex code
            list($r, $g, $b) = sscanf($hexColor, "%01x%01x%01x");

            // Double the digits for shorthand hex color
            $r = $r * 17;
            $g = $g * 17;
            $b = $b * 17;
        } else {
            throw new \Exception('SyncNounTokenColors, convertHexToRGB(): Invalid hex color');
        }

        return ['r' => $r, 'g' => $g, 'b' => $b];
    }
    
    private function formatHistogram(array $histogram): array
    {
        $formattedHistogram = [];
        
        foreach($histogram as $pixel) {
            $colors = $pixel->getColor();

            $key = sprintf("#%02x%02x%02x", $colors['r'], $colors['g'], $colors['b']);
                   
            $formattedHistogram[$key] = $pixel->getColorCount();
        }
    
        return $formattedHistogram;
    }
}