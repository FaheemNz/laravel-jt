<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelerTripResource extends JsonResource
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
        $totalOffer =  $this->offers->count();
        return [
            'id'                          => $this->id,
            'arrival_date'                => $this->arrival_date,
            'status'                      => $this->status,
            'from_city_id'                => $this->from_city_id,
            'from_city'                   => new UserCityResource($this->sourceCity),
            'destination_city_id'         => $this->detination_city_id,
            'destination_city'            => new UserCityResource($this->destinationCity),
            'completeSourceAddress'       => $this->sourceCity->name ?? '',
            'completeDestinationAddress'  => $this->destinationCity->name ?? '',
            'totalOffer'                  => $totalOffer,
            'accepterOffers'              => ($totalOffer) ? $this->offers()->where('status', 'accepted')->count() : 0,
            'disputedOffers'              => ($totalOffer) ? $this->offers()->whereHas('order', function ($q) {
                $q->where('is_disputed', true);
            })->count() : 0
        ];
    }
}
