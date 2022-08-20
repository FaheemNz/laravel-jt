<?php

namespace App\Http\Resources;

use App\Services\PayableService;
use App\Utills\Constants\OrderStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OfferCustomerResource;
use App\Http\Resources\OrderCustomerResource;
use App\Http\Resources\CustomerOrderOffersResource;
use App\Http\Resources\ChatRoomResource;
use App\Lib\Helper;
use Illuminate\Support\Facades\Auth;

class CustomerOrderResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $PayableService = new PayableService();
        $payable_data   = $PayableService->generatePayable($this->payment);

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
            'from_city_id'                  => $this->from_city_id,
            'from_city'                     => new UserCityResource($this->sourceCity),
            'destination_city_id'           => $this->destination_city_id,
            'destination_city'              => new UserCityResource($this->destinationCity),
            'images'                        => $this->imageOrder->pluck('image.name'),
            'image_ids'                     => $this->imageOrder->pluck('image.id'),
            'duty_charges_images'           => $this->imageOrder->where('type','custom_duty')->pluck('image.name'),
            'purchased_images'              => $this->imageOrder->where('type','receipt')->pluck('image.name'),
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
            'totalOffers'                   => $this->offers()
                                                    ->where('expiry_date', '>=', date("Y-m-d"))
                                                    ->where('status', '!=', 'rejected')
                                                    ->count(),
            'basePrice'                     => $basePrice,
            'basePriceCurrency'             => ($this->currency) ? $this->currency->symbol : '',
            'otherPrice'                    => $otherPrice,
            'otherPriceCurrency'            => ($this->createdBy->currency) ? $this->createdBy->currency->symbol : '',
            'baseRewardPrice'               => $baseRewardPrice,
            'baseRewardPriceCurrency'       => ($this->currency) ? $this->currency->symbol : '',
            'otherRewardPrice'              => $otherRewardPrice,
            'otherRewardPriceCurrency'      => ($this->createdBy->currency) ? $this->createdBy->currency->symbol : '',
            'pin_code'                      => ($this->pin_code) ? (string) $this->pin_code : null,
            'pin_time_to_live'              => ($this->pin_time_to_live) ? date('Y-m-d H:i:s', strtotime($this->pin_time_to_live)) : null,
            'is_doorstep_delivery'          => $this->is_doorstep_delivery,
            'is_my_order'                   => auth()->id() == $this->user_id ? true : false,
            'currency'                      => $this->currency,
            'item_purchased_receipt'            => $this->item_purchased_receipt,
            'item_purchased_amount'             => $this->item_purchased_amount,
            'other_item_purchased_amount'       => ($this->currency && $this->createdBy->currency) ? Helper::convertCurrency($this->currency->id, $this->createdBy->currency->id, $this->item_purchased_amount) : '',
            'custom_duty_charges_receipt'       => $this->custom_duty_charges_receipt,
            'custom_duty_charges_amount'        => $this->custom_duty_charges_amount,
            'other_custom_duty_charges_amount'  => ($this->currency && $this->createdBy->currency) ? Helper::convertCurrency($this->currency->id, $this->createdBy->currency->id, $this->custom_duty_charges_amount) : '',
            'customer_returnable'               => $payable_data["customer"],
            'traveller_payable'                 => $payable_data["traveller"],
            'is_traveler_paid'                  => $this->is_traveler_paid,
            'is_customer_paid'                  => $this->is_customer_paid,
        ];

        if(auth()->user()->isAdmin){
            $order['brrring'] = $payable_data['brrring'];
        }

        if($this->status == 'new') {
            return $order;
        } else {
            $acceptedOffer = $this->payment->offer?: $this->payment->counterOffer->offer;

            if($acceptedOffer) {
                $order['offerBy'] = new OfferCustomerResource($acceptedOffer->createdBy);
                $order['offer'] = new CustomerOrderOffersResource($acceptedOffer);

                $order['chatRoom'] = $acceptedOffer->chatRoom
                                        ? (new ChatRoomResource($acceptedOffer->chatRoom))
                                        : null;
            }
        }

        return $order;
    }
}
