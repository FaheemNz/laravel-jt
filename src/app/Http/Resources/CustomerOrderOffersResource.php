<?php

namespace App\Http\Resources;

use App\SystemSetting;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OfferCustomerResource;
use App\Currency;
use App\Lib\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CustomerOrderOffersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
//        $currency_USD = Currency::where('short_code','USD')->first();
        $authCurrency =   Auth::user()->currency;
        $currentDate = Carbon::now();
        $badges = [];
        ($currentDate->isSameDay(Carbon::parse($this->expiry_date))) ? array_push($badges,"24hrs Left") : null;


        $traveler_service_charges_percentage = $this->order->traveler_service_charges_percentage;
        $customer_service_charges_percentage = $this->order->customer_service_charges_percentage;
        $customer_duty_charges_percentage    = $this->order->customer_duty_charges_percentage;

        $traveler_service_charges = $this->reward * $traveler_service_charges_percentage;
        $other_traveler_service_charges = Helper::convertCurrency($this->currency->id, $authCurrency->id, $traveler_service_charges);

        $customer_service_charges = $this->price * $customer_service_charges_percentage ;
        $other_customer_service_charges = Helper::convertCurrency($this->currency->id, $authCurrency->id, $customer_service_charges);

        $customer_duty_charges = $this->price * $customer_duty_charges_percentage ;
        $other_customer_duty_charges = Helper::convertCurrency($this->currency->id, $authCurrency->id, $customer_duty_charges);


        $traveler_earning = $this->reward - $traveler_service_charges;
        $other_traveler_earning = Helper::convertCurrency($this->currency->id, $authCurrency->id, $traveler_earning);

        $customer_payable = $this->price + $this->reward + $customer_duty_charges + $customer_service_charges;
        $other_customer_payable = Helper::convertCurrency($this->currency->id, $authCurrency->id, $customer_payable);

        $otherRewardPrice = ($this->currency && $authCurrency) ? Helper::convertCurrency($this->currency->id, $authCurrency->id, $this->reward) : '';

          //========= COUNTER OFFER =============

        if ($this->counterOffer()->first()) {

            $counterOffer =  $this->counterOffer()->first()->toArray();

            $counter_traveler_service_charges= $counterOffer['reward'] * $traveler_service_charges_percentage;
            $counter_other_traveler_service_charges=Helper::convertCurrency($this->currency->id, $authCurrency->id, $counter_traveler_service_charges);
            $counter_traveler_earning=$counterOffer['reward']-$counter_traveler_service_charges;
            $counter_other_traveler_earning=Helper::convertCurrency($this->currency->id, $authCurrency->id, $counter_traveler_earning);
            $counter_customer_payable=$this->price + $counterOffer['reward'] + $customer_duty_charges + $customer_service_charges;
            $counter_other_customer_payable=Helper::convertCurrency($this->currency->id, $authCurrency->id,$counter_customer_payable);

            $counterOffer = array_merge($counterOffer,[
                'counter_traveler_service_charges'=>$counter_traveler_service_charges,
                'counter_other_traveler_service_charges'=>$counter_other_traveler_service_charges,
                'counter_traveler_earning'=>$counter_traveler_earning,
                'counter_other_traveler_earning'=>$counter_other_traveler_earning,
                'counter_customer_payable'=>$counter_customer_payable,
                'counter_other_customer_payable'=>$counter_other_customer_payable,
                'other_reward'=> Helper::convertCurrency($this->currency->id, $authCurrency->id,$counterOffer['reward'])
            ]);
        }else{
            $counterOffer = [];
        }
        //=========================================

        return [
            'id'                                        =>  $this->id,
            'description'                               =>  $this->description,
            'arrival_date'                              =>  $this->trip->arrival_date ?? '',
            'expiry_date'                               =>  $this->expiry_date,
            'trip_id'                                   =>  $this->trip_id,
            'badges'                                    =>  $badges,
            'basePrice'                                 =>  $this->price,        //Convert from offer price currency to USD
            'basePriceCurrency'                         =>  $this->currency ? $this->currency->symbol : '',
            'otherPrice'                                =>  ($this->currency && $authCurrency) ? Helper::convertCurrency($this->currency->id, $authCurrency->id, $this->price) : '',        //Convert from offer price currency to Preferred Price
            'otherPriceCurrency'                        =>  ($authCurrency) ? $authCurrency->symbol : '',
            'baseRewardPrice'                           =>  $this->reward,                  //Convert from offer reward currency to USD
            'baseRewardPriceCurrency'                   =>  $this->currency ? $this->currency->symbol : '',
            'otherRewardPrice'                          =>  $otherRewardPrice,
            'otherRewardPriceCurrency'                  =>  ($authCurrency) ? $authCurrency->symbol : '',
            'createdBy'                                 =>  new OfferCustomerResource($this->createdBy),
            'status'                                    =>  $this->status,
            'has_counter_offer'                         =>  !!$this->counterOffer()->exists(),
            'counter_offer'                             =>  $counterOffer,
            'traveler_service_charges_percentage'       =>  $traveler_service_charges_percentage * 100,
            'customer_service_charges_percentage'       =>  $customer_service_charges_percentage * 100,
            'customer_duty_charges_percentage'          =>  $customer_duty_charges_percentage * 100,
            'traveler_service_charges'                  =>  $traveler_service_charges,
            'other_traveler_service_charges'            =>  $other_traveler_service_charges,
            'customer_service_charges'                  =>  $customer_service_charges,
            'other_customer_service_charges'            =>  $other_customer_service_charges,
            'customer_duty_charges'                     =>  $customer_duty_charges,
            'other_customer_duty_charges'               =>  $other_customer_duty_charges,
            'traveler_earning'                          =>  $traveler_earning,
            'other_traveler_earning'                    =>  $other_traveler_earning,
            'customer_payable'                          =>  $customer_payable,
            'other_customer_payable'                    =>  $other_customer_payable,
        ];
    }
}
