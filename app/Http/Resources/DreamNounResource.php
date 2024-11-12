<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\NounTraitResource;

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
        $data['glasses'] = new NounTraitResource($this->whenLoaded('glasses'));
        $data['head'] = new NounTraitResource($this->whenLoaded('head'));


        return $data;
    }
}
