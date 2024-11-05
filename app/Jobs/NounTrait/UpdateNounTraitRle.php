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
        try {
            $imgContent = Storage::get($this->nounTrait->png_path);
            $imagick = new \Imagick();
            $imagick->readImageBlob($imgContent);
            $imagick->resizeImage(32, 32, Imagick::FILTER_POINT, 1);

            $width = $imagick->getImageWidth();
            $height = $imagick->getImageHeight();

            $colorPalette = [];
            $colorIndex = 0;
            $rleHex = '';

            // Bounds: Placeholder for single-palette scenarios
            $paletteIndexHex = '00';
            $boundsHex = sprintf('%02x%02x%02x%02x',
                0, // top
                $width, // right
                $height, // bottom
                0 // left
            );

            // Iterate over each row for RLE hex generation
            for ($y = 0; $y < $height; $y++) {
                $prevColorIndex = null;
                $runLength = 0;

                for ($x = 0; $x < $width; $x++) {
                    // Get color at current pixel
                    $color = $imagick->getImagePixelColor($x, $y)->getColor();
                    $hexColor = sprintf("#%02x%02x%02x", $color['r'], $color['g'], $color['b']);

                    // Assign a color index if not yet in palette
                    if (!isset($colorPalette[$hexColor])) {
                        $colorPalette[$hexColor] = $colorIndex++;
                    }
                    $currentColorIndex = $colorPalette[$hexColor];

                    // Create RLE by tracking color runs
                    if ($currentColorIndex === $prevColorIndex) {
                        $runLength++;
                    } else {
                        // Close previous run
                        if ($prevColorIndex !== null) {
                            $rleHex .= sprintf('%02x%02x', $runLength, $prevColorIndex);
                        }
                        $prevColorIndex = $currentColorIndex;
                        $runLength = 1;
                    }
                }

                // Finalize any ongoing run at the row end
                if ($prevColorIndex !== null) {
                    $rleHex .= sprintf('%02x%02x', $runLength, $prevColorIndex);
                }
            }

            // Construct the final hex format
            $hexData = '0x' . $paletteIndexHex . $boundsHex . $rleHex;

            // Save the RLE data to the model
            $this->nounTrait->update(['rle_data' => $hexData]);

        } catch (Exception $e) {
            \Log::error("Failed to generate RLE for NounTrait ID {$this->nounTrait->id}: " . $e->getMessage());
        }
    }
}
