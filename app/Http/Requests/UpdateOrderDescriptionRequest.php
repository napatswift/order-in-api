<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderDescriptionRequest extends FormRequest
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
                'food_id' => ['required'],
                'order_quantity' => ['required'],
                'order_status' => ['required'],
                'order_request' => ['required'],
                'order_price' => ['required'],
            ];
        } else {
            return [
                'food_id' => ['sometimes', 'required'],
                'order_quantity' => ['sometimes', 'required'],
                'order_status' => ['sometimes', 'required'],
                'order_request' => ['sometimes', 'required'],
                'order_price' => ['sometimes', 'required'],
            ];
        }
    }
}
