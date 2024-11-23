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
        return [
            'accessory_seed_id' => [
                'required',
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
                'required',
                Rule::exists('noun_traits', 'seed_id')->where(function ($query) {
                    $query->where('layer', 'body');
                }),
            ],
            'dreamer' => [
                'required',
                'string',
                'size:42',
                'regex:/^0x[a-fA-F0-9]{40}$/',
            ],
            'glasses_seed_id' => [
                'required',
                Rule::exists('noun_traits', 'seed_id')->where(function ($query) {
                    $query->where('layer', 'glasses');
                }),
            ],
            'head_seed_id' => [
                'required',
                Rule::exists('noun_traits', 'seed_id')->where(function ($query) {
                    $query->where('layer', 'head');
                }),
            ]
        ];
    }
}
