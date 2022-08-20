<?php

namespace App\Http\Resources;

use App\Payment;
use App\Services\PayableService;
use App\Utills\Constants\OrderStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Lib\Helper;
use App\Http\Resources\ChatRoomResource;
use App\Http\Resources\TravelerOfferResource;
use App\Currency;
use Illuminate\Support\Facades\Auth;

class TravelerOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $PayableService = new PayableService();
        $payable_data = $PayableService->generatePayable($this->payment);

        $auth_currency = Auth::user()->currency;

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
            'images'                        => $this->images()->where('type','customer')->pluck('name'),
            'duty_charges_images'           => $this->images()->where('type','custom_duty')->pluck('name'),
            'purchased_images'              => $this->images()->where('type','receipt')->pluck('name'),
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
            'customer_rating'               => $this->customer_rating,
            'customer_review'               => $this->customer_review,
            'traveler_rating'               => $this->traveler_rating,
            'traveler_review'               => $this->traveler_review,
            'is_disputed'                   => $this->is_disputed,
            'completeSourceAddress'         => $this->complete_source_address,
            'completeDestinationAddress'    => $this->complete_destination_address,
            'totalOffers'                   => $this->offers()->where('is_disabled',false)->count(),
            'currency'                      => $this->currency,
            'basePrice'                     => $basePrice,
            'basePriceCurrency'             => ($this->currency) ? $this->currency->symbol : '',
            'otherPrice'                    => $otherPrice,
            'otherPriceCurrency'            => (Auth::user()->currency) ? Auth::user()->currency->symbol : '',
            'baseRewardPrice'               => $baseRewardPrice,
            'baseRewardPriceCurrency'       => $this->currency ? $this->currency->symbol : '',
            'otherRewardPrice'              => $otherRewardPrice,
            'otherRewardPriceCurrency'      => (Auth::user()->currency) ? Auth::user()->currency->symbol : '',
            'is_doorstep_delivery'          => $this->is_doorstep_delivery,
            'item_purchased_receipt'            => $this->item_purchased_receipt,
            'item_purchased_amount'             => $this->item_purchased_amount,
            'other_item_purchased_amount'       => ($this->currency && $this->createdBy->currency) ? Helper::convertCurrency($this->currency->id, $this->createdBy->currency->id, $this->item_purchased_amount) : '',
            'custom_duty_charges_receipt'       => $this->custom_duty_charges_receipt,
            'custom_duty_charges_amount'        => $this->custom_duty_charges_amount,
            'other_custom_duty_charges_amount'  => ($this->currency && $this->createdBy->currency) ? Helper::convertCurrency($this->currency->id, $this->createdBy->currency->id, $this->custom_duty_charges_amount) : '',
            'is_traveler_paid'                  => $this->is_traveler_paid,
            'is_customer_paid'                  => $this->is_customer_paid,
            'customer_returnable'               => $payable_data["customer"],
            'traveller_payable'                 => $payable_data["traveller"],
        ];

        $order['can_revise'] = false;

        if($this->status == OrderStatus::NEW) {
            $myOffer = $this->offers()->where('user_id', Auth::user()->id)
                ->where('is_disabled',false)
                ->first();

            if($myOffer) {
                $order['can_revise']    = true;
                $order['my_offer']      = new TravelerOfferResource($myOffer);
            }
        } else {
            $acceptedOffer      = $this->payment->offer?: $this->payment->counterOffer->offer;
            $order['offer']     = new CustomerOrderOffersResource($acceptedOffer);
            $order['chatRoom']  = $acceptedOffer->chatRoom
                                     ? (new ChatRoomResource($acceptedOffer->chatRoom))
                                     : null;
        }
        return $order;
    }
}
