<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'food_name' => $this->food_name,
            'food_type' => $this->food_type,
            'food_price' => $this->food_price,
            'food_detail' => $this->food_detail,
            'food_allergy' => $this->food_allergy,
            'cooking_time' => $this->cooking_time
        ];
    }
}
