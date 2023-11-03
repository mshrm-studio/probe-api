<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\NounsTraitService;
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
        $traitService = new NounsTraitService();

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
            ],
            'sort_property' => [
                'sometimes',
                'in:minted_at,token_id',
            ],
            'sort_method' => [
                'sometimes',
                'in:asc,desc'
            ]
        ];
    }
}
