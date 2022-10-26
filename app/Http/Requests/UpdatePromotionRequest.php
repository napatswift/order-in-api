<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromotionRequest extends FormRequest
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
                'promotion_price' => ['required'],
                'start_date' => ['required'],
                'end_date' => ['required']
            ];
        } else {
            return [
                'promotion_price' => ['sometimes', 'required'],
                'start_date' => ['sometimes', 'required'],
                'end_date' => ['sometimes', 'required']
            ];
        }
    }
}
