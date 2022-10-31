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
            'id' => $this->whenNotNull($this->id),
            'food_name' => $this->whenNotNull($this->food_name),
            'food_price' => $this->whenNotNull($this->food_price),
            'food_detail' => $this->whenNotNull($this->food_detail),
            'cooking_time' => $this->whenNotNull($this->cooking_time),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'promotion' => CategoryResource::collection($this->whenLoaded('promotion')),
            'foodAllergies' => FoodAllergyResource::collection($this->whenLoaded('foodAllergies')),
            'restaurant' => new RestaurantResource($this->whenLoaded('restaurant')),
        ];
    }
}
