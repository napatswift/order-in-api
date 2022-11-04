<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
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
                'name' => ['required', 'string'],
                'image' => ['required', 'image']
            ];
        } else {
            return [
                'name' => ['somtimes', 'string'],
                'image' => ['somtimes', 'image']
            ];
        }
    }
}
