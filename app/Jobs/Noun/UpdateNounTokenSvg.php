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
use Illuminate\Support\Str;

class UpdateNounTokenSvg implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $noun;

    /**
     * Create a new job instance.
     */
    public function __construct(Noun $noun)
    {
        $this->noun = $noun;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $noun = Noun::findOrFail($this->noun->id);

        // Remove the prefix and decode the base64 string
        $jsonB64String = Str::after($noun->token_uri, 'data:application/json;base64,');
        $decodedJson = base64_decode($jsonB64String);

        // Decode the JSON
        $jsonData = json_decode($decodedJson, true);

        // Extract SVG data
        $svgData = $jsonData['image'];

        // Check if SVG data is base64 encoded and decode it
        if (Str::startsWith($svgData, "data:image/svg+xml;base64,")) {
            $base64Svg = explode(",", $svgData)[1];
            $svgContent = base64_decode($base64Svg);
        } else {
            $svgContent = $svgData;
        }

        $filePath = config('app.env') == 'production'
            ? 'nouns/svgs/' . $noun->token_id . '.svg'
            : 'staging/nouns/svgs/' . $noun->token_id . '.svg';

        Storage::put($filePath, $svgContent);

        $noun->update(['svg_path' => $filePath]);
    }
}
