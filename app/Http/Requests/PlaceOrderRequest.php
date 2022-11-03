<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'order_items' => ['required', 'array'],
            'order_items.*.food_id' => ['required', 'integer'],
            'order_items.*.order_quantity' => ['required', 'integer', 'min:1'],
            'order_items.*.order_request' => ['sometimes', 'string',]
        ];
    }
}
