<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\LilNounsTraitService;
use Illuminate\Validation\Rule;

class GetLilNounsRequest extends FormRequest
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
        $traitService = new LilNounsTraitService();

        $traits = $traitService->getItems();

        return [
            'accessory' => [
                'sometimes',
                Rule::in($traits->where('layer', 'accessory')->pluck('name'))
            ],
            'background' => [
                'sometimes',
                Rule::in($traits->where('layer', 'background')->pluck('name'))
            ],
            'body' => [
                'sometimes',
                Rule::in($traits->where('layer', 'body')->pluck('name'))
            ],
            'glasses' => [
                'sometimes',
                Rule::in($traits->where('layer', 'glasses')->pluck('name'))
            ],
            'head' => [
                'sometimes',
                Rule::in($traits->where('layer', 'head')->pluck('name'))
            ],
            'search' => [
                'sometimes',
                'string',
                'max:191'
            ]
        ];
    }
}
