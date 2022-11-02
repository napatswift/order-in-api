<?php

namespace App\Http\Resources\Order;

use App\Models\Food;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

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
        $status_names = config('order.order_status_names');
        return [
            'id'             => $this->id,
            'order_quantity' => $this->order_quantity,
            'order_status'   => $status_names[$this->order_status],
            'order_request'  => $this->whenNotNull($this->order_request),
            'order_price'    => $this->order_price,
            'food_name'      => $this->food->food_name,
            'food_image'     => $this->food->getImage(),
        ];
    }
}
