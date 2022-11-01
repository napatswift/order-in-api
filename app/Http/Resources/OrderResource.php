<?php

namespace App\Http\Resources;

use App\Http\Resources\Order\OrderDescriptionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number' => $this->id,
            'order_description' => OrderDescriptionResource::collection($this->whenLoaded('orderDescription')),
        ];
    }
}
