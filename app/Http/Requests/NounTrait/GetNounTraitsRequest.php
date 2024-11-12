<?php

namespace App\Http\Requests\NounTrait;

use Illuminate\Foundation\Http\FormRequest;

class GetNounTraitsRequest extends FormRequest
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
            'layer' => [
                'sometimes',
                'in:body,accessory,glasses,head,background'
            ],
            'per_page' => [
                'sometimes',
                'integer',
                'min:1',
                'max:500'
            ],
            'search' => [
                'sometimes',
                'string',
                'max:191'
            ],
            'sort_property' => [
                'sometimes',
                'in:name',
            ],
            'sort_method' => [
                'sometimes',
                'in:asc,desc'
            ]
        ];
    }
}
