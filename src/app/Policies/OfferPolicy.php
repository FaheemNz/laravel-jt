<?php

namespace App\Policies;

use App\Offer;
use App\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
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
    
    public function delete(User $user, Offer $offer)
    {
        return $user->id == Order::findOrFail($offer->order_id)->user_id;
    }
    
    public function create()
    {
    
    }
    
    public function update()
    {
    
    }
}
