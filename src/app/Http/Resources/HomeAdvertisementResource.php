<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Lib\Helper;

class HomeAdvertisementResource extends JsonResource
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
            'id'                            => $this->id,
            'name'                          => $this->name,
            'description'                   => $this->description,
            'images'                        => $this->images->pluck('name'),
            'image_ids'                     => $this->images->pluck('id'),
            'category_id'                   => $this->category_id,
            'currency_id'                   => $this->currency_id,
            'category_name'                 => $this->category->name,
            'category_image_url'            => $this->category->image_url,
            'category_tariff'               => $this->category->tariff,
            'url'                           => $this->url,
            'weight'                        => $this->weight,
            'quantity'                      => 1,
            'price'                         => $this->price,
            'reward'                        => $this->reward,
            'with_box'                      => $this->with_box,
            'basePrice'                     => $this->price,
            'basePriceCurrency'             => ($this->currency) ? $this->currency->symbol : '',
            'baseRewardPrice'               => $this->reward,
            'baseRewardPriceCurrency'       => ($this->currency) ? $this->currency->symbol : '',
            'otherPrice'                    => $this->currency->id ? Helper::convertCurrency($this->currency_id, auth()->user()->currency->id, $this->price) : '',
            'otherPriceCurrency'            => auth()->user()->currency->symbol,
            'otherRewardPrice'              => $this->currency->id ? Helper::convertCurrency($this->currency_id, auth()->user()->currency->id, $this->reward) : '',
            'otherRewardPriceCurrency'      => auth()->user()->currency->symbol,
            'is_doorstep_delivery'          => true
        ];
    }
}
