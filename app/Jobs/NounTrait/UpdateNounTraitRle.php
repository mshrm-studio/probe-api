<?php

namespace App\Jobs\NounTrait;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\NounTrait;
use Illuminate\Support\Facades\Storage;

class UpdateNounTraitRle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $nounTrait;

    public function __construct(NounTrait $nounTrait)
    {
        $this->nounTrait = $nounTrait;
    }

    public function handle(): void
    {
        if (!Storage::exists($this->nounTrait->svg_path)) {
            return;
        }

        try {
            $imgContent = Storage::get($this->nounTrait->png_path);
            $imagick = new \Imagick();
            $imagick->readImageBlob($imgContent);
            $imagick->setImageFormat("png");
            $imagick->resizeImage(32, 32, Imagick::FILTER_POINT, 1);

            $width = $imagick->getImageWidth();
            $height = $imagick->getImageHeight();

            // Initialize variables
            $colorPalette = [];
            $colorIndex = 0;
            $rleHex = '';
            $minX = $width;
            $minY = $height;
            $maxX = 0;
            $maxY = 0;

            // Determine bounds around non-transparent pixels
            for ($y = 0; $y < $height; $y++) {
                for ($x = 0; $x < $width; $x++) {
                    $color = $imagick->getImagePixelColor($x, $y)->getColor();
                    $alpha = $imagick->getImagePixelColor($x, $y)->getColorValue(\Imagick::COLOR_ALPHA);

                    if ($alpha < 1) {  // Non-transparent pixel
                        $hexColor = sprintf("#%02x%02x%02x", $color['r'], $color['g'], $color['b']);

                        // Track bounds
                        $minX = min($minX, $x);
                        $minY = min($minY, $y);
                        $maxX = max($maxX, $x);
                        $maxY = max($maxY, $y);

                        // Assign palette index
                        if (!isset($colorPalette[$hexColor])) {
                            $colorPalette[$hexColor] = $colorIndex++;
                        }
                    }
                }
            }

            // RLE Encode within the bounds
            for ($y = $minY; $y <= $maxY; $y++) {
                $prevColorIndex = null;
                $runLength = 0;

                for ($x = $minX; $x <= $maxX; $x++) {
                    $color = $imagick->getImagePixelColor($x, $y)->getColor();
                    $alpha = $imagick->getImagePixelColor($x, $y)->getColorValue(\Imagick::COLOR_ALPHA);

                    // Handle transparency as index 0 in palette
                    $currentColorIndex = $alpha < 1 ? $colorPalette[sprintf("#%02x%02x%02x", $color['r'], $color['g'], $color['b'])] : 0;

                    // Perform RLE by counting consecutive pixels of the same color
                    if ($currentColorIndex === $prevColorIndex) {
                        $runLength++;
                    } else {
                        if ($prevColorIndex !== null) {
                            $rleHex .= sprintf('%02x%02x', $runLength, $prevColorIndex);
                        }
                        $prevColorIndex = $currentColorIndex;
                        $runLength = 1;
                    }
                }
                if ($prevColorIndex !== null) {
                    $rleHex .= sprintf('%02x%02x', $runLength, $prevColorIndex);
                }
            }

            // Construct the final hex format (including bounds and palette index)
            $paletteIndexHex = '00';
            $boundsHex = sprintf('%02x%02x%02x%02x', $minY, $maxX - $minX + 1, $maxY - $minY + 1, $minX);
            $hexData = '0x' . $paletteIndexHex . $boundsHex . $rleHex;

            // Update model with encoded hex data
            $this->nounTrait->update(['rle_data' => $hexData]);

        } catch (\Exception $e) {
            \Log::error("Failed to generate RLE for NounTrait ID {$this->nounTrait->id}: " . $e->getMessage());
        }
    }
}
