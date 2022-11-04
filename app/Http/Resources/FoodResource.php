<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
            'id'            => $this->id,
            'food_name'     => $this->food_name,
            'food_price'    => $this->food_price,
            'food_detail'   => $this->food_detail,
            'cooking_time'  => $this->cooking_time,
            'images'        => $this->when(!is_null($this->getMedia()), $this->getImage()),
            'categories'    => CategoryResource::collection($this->whenLoaded('categories')),
            'promotion'     => PromotionResource::collection($this->whenLoaded('promotion')),
            'foodAllergies' => FoodAllergyResource::collection($this->whenLoaded('foodAllergies')),
            'restaurant'    => new RestaurantResource($this->whenLoaded('restaurant')),
        ];
    }
}
