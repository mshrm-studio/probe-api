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

            // Get image dimensions
            $width = $imagick->getImageWidth();
            $height = $imagick->getImageHeight();

            // Initialize palette and RLE hex string
            $colorPalette = [];
            $colorIndex = 0;
            $hexData = sprintf(
                "0x%02X%02X%02X%02X", // Bounds in hexadecimal (left, top, right, bottom)
                0, // left bound
                0, // top bound
                $width, // right bound
                $height // bottom bound
            );

            // Iterate through each row to create RLE hex data
            for ($y = 0; $y < $height; $y++) {
                $prevColor = null;
                $runLength = 0;

                for ($x = 0; $x < $width; $x++) {
                    // Get color at current pixel
                    $color = $imagick->getImagePixelColor($x, $y)->getColor();
                    $hexColor = sprintf("#%02x%02x%02x", $color['r'], $color['g'], $color['b']);

                    // Check if this color is part of a run or new
                    if ($prevColor === $hexColor) {
                        $runLength++;
                    } else {
                        if ($prevColor !== null) {
                            // End previous run: encode length and color index
                            if (!isset($colorPalette[$prevColor])) {
                                $colorPalette[$prevColor] = $colorIndex++;
                            }
                            $hexData .= sprintf(
                                "%02X%02X", // Run length and color index in hex
                                $runLength,
                                $colorPalette[$prevColor]
                            );
                        }
                        // Start new run
                        $prevColor = $hexColor;
                        $runLength = 1;
                    }
                }

                // Finalize the last run of the row
                if ($prevColor !== null) {
                    if (!isset($colorPalette[$prevColor])) {
                        $colorPalette[$prevColor] = $colorIndex++;
                    }
                    $hexData .= sprintf(
                        "%02X%02X",
                        $runLength,
                        $colorPalette[$prevColor]
                    );
                }
            }

            // Prepare the final output format with palette
            $output = [
                'hex_data' => $hexData,
                'palette' => $colorPalette,
            ];

            // Save the hex RLE data to your model or database field
            $this->nounTrait->update(['rle_data' => json_encode($output)]);
        } catch (Exception $e) {
            \Log::error("Failed to generate RLE for NounTrait ID {$this->nounTrait->id}: " . $e->getMessage());
        }
    }
}
