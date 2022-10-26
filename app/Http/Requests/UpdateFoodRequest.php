<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFoodRequest extends FormRequest
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
        $method = $this->method();

        if ($method == 'PUT') {
            return [
                'food_name' => ['required'],
                'food_type' => ['required'],
                'food_price' => ['required'],
                'food_detail' => ['required'],
                'food_allergy' => ['required'],
                'cooking_time' => ['required'],
            ];
        } else {
            return [
                'food_name' => ['sometimes', 'required'],
                'food_type' => ['sometimes', 'required'],
                'food_price' => ['sometimes', 'required'],
                'food_detail' => ['sometimes', 'required'],
                'food_allergy' => ['sometimes', 'required'],
                'cooking_time' => ['sometimes', 'required'],
            ];
        }
    }
}
