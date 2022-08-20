<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelerOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id'                => $this->id,
            'description'       => $this->description,
            'status'            => $this->status,
            'price'             => $this->price,
            'reward'            => $this->reward,
            'service_charges'   => $this->services_charges,
            'basePriceCurrency' => $this->createdBy->currency->symbol,
            'trip_id'           => $this->trip_id,
            'order_id'          => $this->order_id,
            'expiry_date'       => $this->expiry_date,
        ];
    }
}
