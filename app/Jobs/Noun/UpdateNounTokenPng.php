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

class UpdateNounTokenPng implements ShouldQueue
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

        $imagick = new \Imagick();

        $svgContent = Storage::get($noun->svg_path);
        
        $imagick->readImageBlob($svgContent);
        
        $imagick->setImageFormat('png');

        $filePath = config('app.env') == 'production'
            ? 'nouns/pngs/' . $noun->token_id . '.png'
            : 'staging/nouns/pngs/' . $noun->token_id . '.png';

        Storage::put($filePath, $imagick->getImageBlob(), 'public');

        $noun->update(['png_path' => $filePath]);

        $imagick->clear();
    }
}
