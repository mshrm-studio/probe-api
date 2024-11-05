<?php

namespace App\Jobs\NounTrait;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\NounTrait;
use Illuminate\Support\Facades\Storage;
use Exception;

class UpdateNounTraitPng implements ShouldQueue
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
        if (!Storage::exists($this->nounTrait->svg_path)) {
            return;
        }

        try {
            $imagick = new \Imagick();

            $svgContent = Storage::get($this->nounTrait->svg_path);
            
            $imagick->readImageBlob($svgContent);
            
            $imagick->setImageFormat('png');
    
            $directoryMap = [
                'accessory' => 'accessories',
                'background' => 'backgrounds',
                'body' => 'bodies',
                'head' => 'heads',
                'glasses' => 'glasses',
            ];
    
            $filePath = "nouns/traits/{$directoryMap[$this->nounTrait->layer]}/pngs/{$this->nounTrait->name}.png";
    
            if (config('app.env') !== 'production') {
                $filePath = config('app.env') . '/' . $filePath;
            }
    
            Storage::put($filePath, $imagick->getImageBlob(), 'public');
    
            $this->nounTrait->update(['png_path' => $filePath]);
    
            $imagick->clear();
        } catch (Exception $e) {
            \Log::error("Failed to update PNG for NounTrait ID {$this->nounTrait->id}: " . $e->getMessage());
        }        
    }
}
