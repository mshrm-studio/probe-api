<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetNounsRequest extends FormRequest
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
            'accessory' => [
                'sometimes',
                'exists:noun_traits,name'
            ],
            'background' => [
                'sometimes',
                'exists:noun_traits,name'
            ],
            'body' => [
                'sometimes',
                'exists:noun_traits,name'
            ],
            'glasses' => [
                'sometimes',
                'exists:noun_traits,name'
            ],
            'head' => [
                'sometimes',
                'exists:noun_traits,name'
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
                'in:minted_at,token_id,weight',
            ],
            'sort_method' => [
                'sometimes',
                'in:asc,desc'
            ]
        ];
    }
}
