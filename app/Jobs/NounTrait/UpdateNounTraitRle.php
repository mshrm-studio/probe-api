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

            // Prepare RLE and color palette data
            $rleData = [];
            $colorPalette = [];
            $colorIndex = 0;

            // Iterate through each row to create RLE data
            for ($y = 0; $y < $height; $y++) {
                $rowRLE = [];
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
                            // End previous run
                            if (!isset($colorPalette[$prevColor])) {
                                $colorPalette[$prevColor] = $colorIndex++;
                            }
                            $rowRLE[] = [
                                'length' => $runLength,
                                'colorIndex' => $colorPalette[$prevColor]
                            ];
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
                    $rowRLE[] = [
                        'length' => $runLength,
                        'colorIndex' => $colorPalette[$prevColor]
                    ];
                }

                // Add row to RLE data
                $rleData = array_merge($rleData, $rowRLE);
            }

            // Prepare output format for storage
            $output = json_encode([
                'bounds' => [
                    'left' => 0,
                    'top' => 0,
                    'right' => $width,
                    'bottom' => $height,
                ],
                'rects' => $rleData,
                'palette' => $colorPalette,
            ]);

            // Save the RLE data to your model or database field
            $this->nounTrait->update(['rle_data' => $output]);
        } catch (Exception $e) {
            \Log::error("Failed to generate RLE for NounTrait ID {$this->nounTrait->id}: " . $e->getMessage());
        }
    }
}
