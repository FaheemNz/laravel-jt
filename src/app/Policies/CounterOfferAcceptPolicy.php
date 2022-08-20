<?php

namespace App\Policies;

use App\CounterOffer;
use App\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CounterOfferAcceptPolicy
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
    
    public function accept(User $user, CounterOffer $counterOffer)
    {
        return $user->id === Order::findOrFail($counterOffer->order_id)->user_id;
    }
}
