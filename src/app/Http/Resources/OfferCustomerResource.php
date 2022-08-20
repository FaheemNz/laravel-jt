<?php

namespace App\Http\Resources;

use App\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferCustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $customer_ratting   = Order::where('user_id', $this->id)->avg("customer_rating");
        $traveller_ratting  = Order::where('traveler_id', $this->id)->avg("traveler_rating");

        return [
            'id'                        => $this->id,
            'fullName'                  => $this->first_name . ' ' . $this->last_name,
            'rating'                    => round($customer_ratting, 1),
            'as_traveller_rating'       => round($traveller_ratting, 1),
            'totalCompletedOrders'      => $this->offers()->whereHas('order', function ($query) {
                                                return $query->whereIn('status',['traveler_rated','customer_rated','rated','traveler_paid']);
                                            })->where('status','accepted')->count(),
            'image'                     => $this->image()
        ];
    }
}
