<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePromotionRequest extends FormRequest
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
            'promotion_code'      => ['required', 'string'],
            'name'                => ['required', 'string'],
            'description'         => ['required', 'string'],
            'discount_amount'     => ['required', 'integer'],
            // 'max_discount_amount' => ['required', 'integer'],
            'begin_useable_date'  => ['required', 'date_format:Y-m-d'],
            'end_useable_date'    => ['required', 'date_format:Y-m-d'],
            'image'               => ['required', 'image']
        ];
    }
}
