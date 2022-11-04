<?php

namespace App\Http\Resources;

use App\Http\Resources\Order\OrderDescriptionResource as ODResource;
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
            'id'                => $this->id,
            'order_description' => ODResource::collection($this->whenLoaded('orderDescription')),
        ];
    }
}
