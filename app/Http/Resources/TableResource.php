<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $customer = $this->customers->sortByDesc('created_at')->first();
        $includeCustomer = false;
        $customer_data = null;
        
        try {
            $customer_data = $customer->load(['order', 'table'])
            ->only(['id', 'order', 'table']);
            $includeCustomer = true;
        } catch (\Throwable $tb){
            $includeCustomer = false;
        }

        return [
            'id'           => $this->id,
            'table_number' => $this->table_number,
            'available'    => $this->available,
            'current_customer' => $customer_data
        ];
    }
}
