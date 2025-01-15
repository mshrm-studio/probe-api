<?php

namespace App\Http\Requests\DreamNoun;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDreamNounRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $customTraitImageProvided = $this->hasFile('custom_trait_image');
        $customTraitLayer = $this->input('custom_trait_layer');

        return [
            'accessory_seed_id' => [
                Rule::requiredIf(!($customTraitImageProvided && $customTraitLayer === 'accessory')),
                Rule::exists('noun_traits', 'seed_id')->where(function ($query) {
                    $query->where('layer', 'accessory');
                }),
            ],
            'background_seed_id' => [
                'required',
                Rule::exists('noun_traits', 'seed_id')->where(function ($query) {
                    $query->where('layer', 'background');
                }),
            ],
            'body_seed_id' => [
                Rule::requiredIf(!($customTraitImageProvided && $customTraitLayer === 'body')),
                Rule::exists('noun_traits', 'seed_id')->where(function ($query) {
                    $query->where('layer', 'body');
                }),
            ],
            'custom_trait_image' => [
                'nullable', 
                'mimes:png'
            ],
            'custom_trait_layer' => [
                Rule::requiredIf(fn () => $customTraitImageProvided),
                Rule::in(['body', 'head', 'glasses', 'accessory']),
            ],
            'dreamer' => [
                'required',
                'string',
                'size:42',
                'regex:/^0x[a-fA-F0-9]{40}$/',
            ],
            'glasses_seed_id' => [
                Rule::requiredIf(!($customTraitImageProvided && $customTraitLayer === 'glasses')),
                Rule::exists('noun_traits', 'seed_id')->where(function ($query) {
                    $query->where('layer', 'glasses');
                }),
            ],
            'head_seed_id' => [
                Rule::requiredIf(!($customTraitImageProvided && $customTraitLayer === 'head')),
                Rule::exists('noun_traits', 'seed_id')->where(function ($query) {
                    $query->where('layer', 'head');
                }),
            ]
        ];
    }
}
