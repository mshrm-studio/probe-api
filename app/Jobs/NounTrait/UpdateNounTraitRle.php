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
use Imagick;

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
        if (!Storage::exists($this->nounTrait->svg_path)) {
            return;
        }

        try {
            // Retrieve image content directly using Storage
            $imgContent = Storage::get($this->nounTrait->png_path);
            $imagick = new Imagick();
            $imagick->readImageBlob($imgContent);
            $imagick->resizeImage(32, 32, Imagick::FILTER_POINT, 1);

            $width = $imagick->getImageWidth();
            $height = $imagick->getImageHeight();

            $colorPalette = [];
            $colorIndex = 0;
            $rleHex = '';

            // Bounds information
            $paletteIndexHex = '00';  // Set as '00' for single-palette scenarios
            $boundsHex = sprintf(
                '%02x%02x%02x%02x',
                0,          // top
                $width,     // right
                $height,    // bottom
                0           // left
            );

            // Iterate through each row to generate RLE hex
            for ($y = 0; $y < $height; $y++) {
                $prevColorIndex = null;
                $runLength = 0;

                for ($x = 0; $x < $width; $x++) {
                    // Get color at current pixel
                    $color = $imagick->getImagePixelColor($x, $y)->getColor();
                    $hexColor = sprintf("#%02x%02x%02x", $color['r'], $color['g'], $color['b']);

                    // Register the color in the palette if it’s not already there
                    if (!isset($colorPalette[$hexColor])) {
                        $colorPalette[$hexColor] = $colorIndex++;
                    }
                    $currentColorIndex = $colorPalette[$hexColor];

                    // RLE encoding: check if we’re continuing a run of the same color
                    if ($currentColorIndex === $prevColorIndex) {
                        $runLength++;
                    } else {
                        // Close the previous color run and start a new one
                        if ($prevColorIndex !== null) {
                            $rleHex .= sprintf('%02x%02x', $runLength, $prevColorIndex);
                        }
                        $prevColorIndex = $currentColorIndex;
                        $runLength = 1;
                    }
                }
                // End of row: finalize any ongoing color run
                if ($prevColorIndex !== null) {
                    $rleHex .= sprintf('%02x%02x', $runLength, $prevColorIndex);
                }
            }

            // Combine all parts into final hex format
            $hexData = '0x' . $paletteIndexHex . $boundsHex . $rleHex;

            // Update the model with hex data only, if color index-based RLE is sufficient
            $this->nounTrait->update(['rle_data' => $hexData]);

        } catch (Exception $e) {
            \Log::error("Failed to generate RLE for NounTrait ID {$this->nounTrait->id}: " . $e->getMessage());
        }
    }
}
