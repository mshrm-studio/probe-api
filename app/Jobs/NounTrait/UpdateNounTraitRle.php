<?php

namespace App\Jobs\NounTrait;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\NounTrait;
use Illuminate\Support\Facades\Storage;
use Exception;
use SimpleXMLElement;

class UpdateNounTraitRle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $nounTrait;

    /**
     * Create a new job instance.
     */
    public function __construct(NounTrait $nounTrait)
    {
        $this->nounTrait = $nounTrait;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check if SVG exists
        if (!Storage::exists($this->nounTrait->svg_path)) {
            throw new Exception("SVG file does not exist at path: {$this->nounTrait->svg_path}");
        }

        try {
            $svgContent = Storage::get($this->nounTrait->svg_path);
            $rleData = $this->encodeSvgToRle($svgContent);

            $this->nounTrait->update(['rle_data' => $rleData]);
        } catch (Exception $e) {
            \Log::error("Failed to generate RLE for NounTrait ID {$this->nounTrait->id}: " . $e->getMessage());
        }
    }

    /**
     * Encode SVG content to RLE format compatible with `buildSVG`.
     *
     * @param string $svgContent
     * @return string
     */
    protected function encodeSvgToRle(string $svgContent): string
    {
        $rleData = [];
        $colorPalette = [];
        $colorIndex = 0;

        $xml = new SimpleXMLElement($svgContent);

        // Iterate through each SVG element
        foreach ($xml->children() as $element) {
            if ($element->getName() === 'rect') {
                $x = isset($element['x']) ? (int)$element['x'] : 0;
                $y = isset($element['y']) ? (int)$element['y'] : 0;
                $width = isset($element['width']) ? (int)$element['width'] : 0;
                $height = isset($element['height']) ? (int)$element['height'] : 0;
                $color = isset($element['fill']) ? (string)$element['fill'] : '#000000';

                // Assign a color index if it’s not already in the palette
                if (!isset($colorPalette[$color])) {
                    $colorPalette[$color] = $colorIndex;
                    $colorIndex++;
                }
                $colorIndexForRle = $colorPalette[$color];

                // Add each row of the rectangle as a separate RLE entry
                for ($row = 0; $row < $height; $row++) {
                    $rleData[] = [
                        'x' => $x,
                        'y' => $y + $row,
                        'length' => $width,
                        'colorIndex' => $colorIndexForRle,
                    ];
                }
            }
            // Additional SVG elements like circles, ellipses, and lines are ignored to meet `buildSVG` expectations
        }

        // Combine RLE instructions into a single format string
        return json_encode([
            'bounds' => [
                'left' => 0,
                'top' => 0,
                'right' => !empty(array_column($rleData, 'x')) 
                    ? max(array_column($rleData, 'x')) + $width 
                    : $width,
                'bottom' => !empty(array_column($rleData, 'y')) 
                    ? max(array_column($rleData, 'y')) + $height 
                    : $height,
            ],
            'rects' => array_map(function ($rect) {
                return [$rect['length'], $rect['colorIndex']];
            }, $rleData),
            'palette' => $colorPalette,
        ]);
    }
}
