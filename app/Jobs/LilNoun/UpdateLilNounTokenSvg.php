<?php

namespace App\Jobs\LilNoun;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LilNoun;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateLilNounTokenSvg implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lilNoun;

    /**
     * Create a new job instance.
     */
    public function __construct(LilNoun $lilNoun)
    {
        $this->lilNoun = $lilNoun;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lilNoun = LilNoun::findOrFail($this->lilNoun->id);

        // Remove the prefix and decode the base64 string
        $jsonB64String = Str::after($lilNoun->token_uri, 'data:application/json;base64,');
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
            ? 'lils/svgs/' . $lilNoun->token_id . '.svg'
            : 'staging/lils/svgs/' . $lilNoun->token_id . '.svg';

        Storage::put($filePath, $svgContent);

        $lilNoun->update(['svg_path' => $filePath]);
    }
}
