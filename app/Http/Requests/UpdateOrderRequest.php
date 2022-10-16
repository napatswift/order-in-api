<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
                'order_number' => ['required'],
                'order_description' => ['required'],
            ];
        } else {
            return [
                'order_number' => ['sometimes', 'required'],
                'order_description' => ['sometimes', 'required'],
            ];
        }
    }
}
