<?php

namespace App\Http\Resources;

use App\Lib\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                            => $this->id,
            'thumbnail'                     => $this->thumbnail,
            'name'                          => $this->name,
            'description'                   => $this->description,
            'images'                        => $this->images->pluck('name'),
            'cateory_id'                    => $this->category_id,
            'category'                      => $this->category,
            'url'                           => $this->url,
            'weight'                        => $this->weight,
            'price'                         => $this->price,
            'currency_id'                   => $this->currency_id,
            'currency'                      => $this->currency,
            'reward'                        => $this->reward,
            'with_box'                      => $this->with_box,
            'needed_by'                     => $this->needed_by,
            'from_city_id'                  => $this->from_city_id,
            'destination_city_id'           => $this->destination_city_id,
            'status'                        => $this->status,
            'customer_rating'               => $this->customer_rating,
            'customer_review'               => $this->customer_review,
            'traveler_rating'               => $this->travel_rating,
            'traveler_review'               => $this->travel_review,
            'is_disputed'                   => $this->is_disputed,
            'user_id'                       => $this->user_id,
            'createdBy'                     => $this->createdBy,
            'completeSourceAddress'         => $this->complete_source_address,
            'completeDestinationAddress'    => $this->complete_destination_address,
            'totalOffers'                   => $this->offers->count(),
            'basePrice'                     => $this->price,
            'basePriceCurrency'             => ($this->currency) ? $this->currency->symbol : '',
            'otherPrice'                    => ($this->currency && $this->createdBy->currency) ? Helper::convertCurrency($this->currency->id,$this->createdBy->currency->id,$this->price) : '',
            'otherPriceCurrency'            => ($this->createdBy->currency) ? $this->createdBy->currency->symbol : '',
            'baseRewardPrice'               => $this->reward,
            'baseRewardPriceCurrency'       => ($this->currency) ? $this->currency->symbol : '',
            'otherRewardPrice'              => ($this->currency && $this->createdBy->currency) ? Helper::convertCurrency($this->currency->id,$this->createdBy->currency->id,$this->reward) : '',
            'otherRewardPriceCurrency'      => ($this->createdBy->currency) ? $this->createdBy->currency->symbol : '',
            'offers'                        => CustomerOrderOffersResource::collection($this->offers()->where('expiry_date', '>=',date("Y-m-d"))->get())
        ];
    }
}
