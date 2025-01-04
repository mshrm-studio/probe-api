<?php

namespace App\Http\Resources;

use App\Http\Resources\NounTraitResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DreamNounResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data['accessory'] = new NounTraitResource($this->whenLoaded('accessory'));
        $data['background'] = new NounTraitResource($this->whenLoaded('background'));
        $data['body'] = new NounTraitResource($this->whenLoaded('body'));
        $data['custom_trait_image_url'] = !empty($this->custom_trait_image)
            ? Storage::url($this->custom_trait_image)
            : null;
        $data['glasses'] = new NounTraitResource($this->whenLoaded('glasses'));
        $data['head'] = new NounTraitResource($this->whenLoaded('head'));


        return $data;
    }
}
