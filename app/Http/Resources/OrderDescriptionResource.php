<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDescriptionResource extends JsonResource
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
            'id'             => $this->id,
            'food_id'        => $this->food_id,
            'order_quantity' => $this->order_quantity,
            'order_status'   => $this->order_status,
            'order_request'  => $this->order_request,
            'order_price'    => $this->order_price,
        ];
    }
}
