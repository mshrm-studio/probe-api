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

class UpdateLilNounTokenPng implements ShouldQueue
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

        $imagick = new \Imagick();

        $svgContent = Storage::get($lilNoun->svg_path);
        
        $imagick->readImageBlob($svgContent);
        
        $imagick->setImageFormat('png');

        $filePath = config('app.env') == 'production'
            ? 'lils/pngs/' . $lilNoun->token_id . '.png'
            : 'staging/lils/pngs/' . $lilNoun->token_id . '.png';

        Storage::put($filePath, $imagick->getImageBlob(), 'public');

        $lilNoun->update(['png_path' => $filePath]);

        $imagick->clear();
    }
}
