<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LilNounResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data['png_url'] = $this->png_path
            ? Storage::temporaryUrl($this->png_path, now()->addMinutes(60))
            : null;

        $data['svg_url'] = $this->svg_path
            ? Storage::temporaryUrl($this->svg_path, now()->addMinutes(60))
            : null;

        return $data;
    }
}
