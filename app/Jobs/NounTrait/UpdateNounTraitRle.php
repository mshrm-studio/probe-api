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
        if (!Storage::exists($this->nounTrait->png_path)) {
            return;
        }

        try {
            // Load image into Imagick
            $imgContent = Storage::get($this->nounTrait->png_path);
            $imagick = new \Imagick();
            $imagick->readImageBlob($imgContent);

            $width = $imagick->getImageWidth();
            $height = $imagick->getImageHeight();

            $colorPalette = [];
            $colorIndex = 0;
            $rleHex = '';

            // Palette Index - Set as '00' for single-palette scenarios
            $paletteIndexHex = '00';

            // Bounds information
            $boundsHex = sprintf(
                '%02x%02x%02x%02x',
                0, // top
                $width, // right
                $height, // bottom
                0 // left
            );

            // Iterate through each row to generate RLE hex
            for ($y = 0; $y < $height; $y++) {
                $prevColor = null;
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

                    // Build RLE by checking if we’re continuing a run
                    if ($prevColor === $currentColorIndex) {
                        $runLength++;
                    } else {
                        // Close the previous run and start a new one
                        if ($prevColor !== null) {
                            $rleHex .= sprintf('%02x%02x', $runLength, $prevColor);
                        }
                        $prevColor = $currentColorIndex;
                        $runLength = 1;
                    }
                }
                // End of row: finalize any ongoing run
                if ($prevColor !== null) {
                    $rleHex .= sprintf('%02x%02x', $runLength, $prevColor);
                }
            }

            // Combine all parts into final hex format
            $hexData = '0x' . $paletteIndexHex . $boundsHex . $rleHex;

            // Update the model with the hex data and palette
            $this->nounTrait->update([
                'hex_data' => $hexData,
                'palette' => json_encode($colorPalette)
            ]);

        } catch (Exception $e) {
            \Log::error("Failed to generate RLE for NounTrait ID {$this->nounTrait->id}: " . $e->getMessage());
        }
    }
}
