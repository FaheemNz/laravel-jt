<?php

namespace App\Http\Resources;

use App\Services\Interfaces\PayableServiceInterface;
use App\Services\PayableService;
use App\Utills\Constants\OrderStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderCustomerResource;
use App\Http\Resources\TravelerOfferResource;
use App\Lib\Helper;
use App\Currency;
use Illuminate\Support\Facades\Auth;

class HomeOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $auth_currency = Auth::user()->currency;

        $PayableService = new PayableService();
        $payable_data = $PayableService->generatePayable($this->payment);


        $basePrice                     = $this->price;
        $otherPrice                    = ($this->currency && $auth_currency) ? Helper::convertCurrency($this->currency->id, $auth_currency->id, $this->price) : '';
        $baseRewardPrice               = $this->reward;
        $otherRewardPrice              = ($this->currency && $auth_currency) ? Helper::convertCurrency($this->currency->id,$auth_currency->id,$this->reward) : '';

        if($this->status != OrderStatus::NEW && $this->payment){

            $offer                         = $this->payment->offer ?: $this->payment->counterOffer->offer;
            $counterOffer                  = $this->payment->counterOffer;
            $reward                        = $counterOffer? $counterOffer->reward : $offer->reward;

            $basePrice                     = $offer->price;
            $otherPrice                    = ($this->currency && $auth_currency) ? Helper::convertCurrency($this->currency->id, $auth_currency->id, $offer->price) : '';
            $baseRewardPrice               = $reward;
            $otherRewardPrice              = ($this->currency && $auth_currency) ? Helper::convertCurrency($this->currency->id,$auth_currency->id, $reward) : '';
        }

        $order = [
            'id'                            => $this->id,
            'thumbnail'                     => $this->thumbnail,
            'name'                          => $this->name,
            'description'                   => $this->description,
            'images'                        => $this->images->pluck('name'),
            'image_ids'                     => $this->images->pluck('id'),
            'category_id'                   => $this->category_id,
            'currency_id'                   => $this->currency_id,
            'from_city_id'                  => $this->from_city_id,
            'from_city'                     => new UserCityResource($this->sourceCity),
            'destination_city_id'           => $this->destination_city_id,
            'destination_city'              => new UserCityResource($this->destinationCity),
            'category_name'                 => $this->category->name,
            'category_image_url'            => $this->category->image_url,
            'category_tariff'               => $this->category->tariff,
            'url'                           => $this->url,
            'weight'                        => $this->weight,
            'quantity'                      => $this->quantity,
            'price'                         => $this->price,
            'reward'                        => $this->reward,
            'with_box'                      => $this->with_box,
            'needed_by'                     => $this->needed_by,
            'status'                        => $this->status,
            'createdBy'                     => new OrderCustomerResource($this->createdBy),
            'completeSourceAddress'         => $this->complete_source_address,
            'completeDestinationAddress'    => $this->complete_destination_address,
            'totalOffers'                   => $this->offers()
                                                    ->where('expiry_date', '>=', date('Y-m-d'))
                                                    ->where('status', '!=', 'rejected')
                                                    ->count(),
            'basePrice'                     => $basePrice,
            'basePriceCurrency'             => ($this->currency) ? $this->currency->symbol : '',//$currency_USD->symbol,
            'otherPrice'                    => $otherPrice,
            'otherPriceCurrency'            => ($auth_currency) ? $auth_currency->symbol : '',
            'baseRewardPrice'               => $baseRewardPrice,
            'baseRewardPriceCurrency'       => $this->currency ? $this->currency->symbol : '',
            'otherRewardPrice'              => $otherRewardPrice,
            'otherRewardPriceCurrency'      => ($auth_currency) ? $auth_currency->symbol : '',
            'is_doorstep_delivery'          => $this->is_doorstep_delivery,
            'is_my_order'                   => false,
            'currency'                      => $this->currency,
            'has_counter_offer'             => !!$this->has_counter_offer,
            'can_revise'                    => false,
            'pin_code'                      => ($this->pin_code) ? (string) $this->pin_code : null,
            'pin_time_to_live'              => ($this->pin_time_to_live) ? date('Y-m-d H:i:s', strtotime($this->pin_time_to_live)) : null,
            'item_purchased_receipt'            => $this->item_purchased_receipt,
            'item_purchased_amount'             => $this->item_purchased_amount,
            'other_item_purchased_amount'       => ($this->currency && $this->createdBy->currency) ? Helper::convertCurrency($this->currency->id, $this->createdBy->currency->id, $this->item_purchased_amount) : '',
            'custom_duty_charges_receipt'       => $this->custom_duty_charges_receipt,
            'custom_duty_charges_amount'        => $this->custom_duty_charges_amount,
            'other_custom_duty_charges_amount'  => ($this->currency && $this->createdBy->currency) ? Helper::convertCurrency($this->currency->id, $this->createdBy->currency->id, $this->custom_duty_charges_amount) : '',
            'customer_returnable'           => $payable_data["customer"],
            'traveller_payable'             => $payable_data["traveller"],
            'is_traveler_paid'              => $this->is_traveler_paid,
            'is_customer_paid'              => $this->is_customer_paid,
        ];

        if($this->status == 'new') {
            $myOffer = $this->offers()
                            ->where('user_id', auth()->id())
                            ->where('is_disabled', false)
                            ->first();

            if($myOffer) {
                $order['can_revise'] = true;
                $order['my_offer'] = new TravelerOfferResource($myOffer);
           }
        }

        return $order;
    }
}
