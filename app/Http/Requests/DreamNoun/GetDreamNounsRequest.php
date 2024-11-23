<?php

namespace App\Http\Requests\DreamNoun;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetDreamNounsRequest extends FormRequest
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
                'sometimes',
                Rule::exists('noun_traits', 'seed_id')->where(function ($query) {
                    $query->where('layer', 'accessory');
                }),
            ],
            'background_seed_id' => [
                'sometimes',
                Rule::exists('noun_traits', 'seed_id')->where(function ($query) {
                    $query->where('layer', 'background');
                }),
            ],
            'body_seed_id' => [
                'sometimes',
                Rule::exists('noun_traits', 'seed_id')->where(function ($query) {
                    $query->where('layer', 'body');
                }),
            ],
            'glasses_seed_id' => [
                'sometimes',
                Rule::exists('noun_traits', 'seed_id')->where(function ($query) {
                    $query->where('layer', 'glasses');
                }),
            ],
            'head_seed_id' => [
                'sometimes',
                Rule::exists('noun_traits', 'seed_id')->where(function ($query) {
                    $query->where('layer', 'head');
                }),
            ],
            'per_page' => [
                'sometimes',
                'integer',
                'min:1',
                'max:300'
            ],
            'search' => [
                'sometimes',
                'string',
                'max:191'
            ],
            'sort_property' => [
                'sometimes',
                'in:id,created_at,updated_at',
            ],
            'sort_method' => [
                'sometimes',
                'in:asc,desc'
            ]
        ];
    }
}
