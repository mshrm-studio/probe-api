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
            $imgContent = Storage::get($this->nounTrait->png_path);
            $imagick = new \Imagick();
            $imagick->readImageBlob($imgContent);

            $width = $imagick->getImageWidth();
            $height = $imagick->getImageHeight();

            $hexData = '0x';

            // Iterate through each row to generate hex color data
            for ($y = 0; $y < $height; $y++) {
                $prevColor = null;
                $runLength = 0;

                for ($x = 0; $x < $width; $x++) {
                    // Get color at current pixel
                    $color = $imagick->getImagePixelColor($x, $y)->getColor();
                    $hexColor = sprintf("%02x%02x%02x", $color['r'], $color['g'], $color['b']);

                    // Build the RLE hex data
                    if ($hexColor === $prevColor) {
                        $runLength++;
                    } else {
                        // Finish the previous color run if applicable
                        if ($prevColor !== null) {
                            $hexData .= sprintf('%02x%s', $runLength, $prevColor);
                        }
                        // Start a new color
                        $prevColor = $hexColor;
                        $runLength = 1;
                    }
                }

                // End of row: finalize any ongoing run
                if ($prevColor !== null) {
                    $hexData .= sprintf('%02x%s', $runLength, $prevColor);
                }
            }

            // Update the model with the hex data
            $this->nounTrait->update(['rle_data' => $hexData]);

        } catch (Exception $e) {
            \Log::error("Failed to generate RLE for NounTrait ID {$this->nounTrait->id}: " . $e->getMessage());
        }
    }
}
