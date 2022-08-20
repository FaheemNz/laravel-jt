<?php

namespace App\Policies;

use App\CounterOffer;
use App\Offer;
use App\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CounterOfferPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function create(User $user, int $orderId)
    {
        return $user->id == Order::findOrFail($orderId)->user_id;
    }
    
    public function update(User $user, CounterOffer $counterOffer)
    {
        return $user->id == Offer::findOrFail($counterOffer->offer_id)->user_id;
    }
}
