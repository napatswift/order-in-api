<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StoreFoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'food_name' => ['required', 'string'],
            'food_price' => ['required', 'int'],
            'food_detail' => ['required', 'string'],
            'cooking_time' => ['required', 'int'],
            'category_ids' => ['required', 'array'],
            'food_allery_ids' => ['sometimes', 'array']
        ];
    }
}
