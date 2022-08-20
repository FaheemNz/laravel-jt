<?php

namespace App\Rules;

use App\Order;
use Illuminate\Contracts\Validation\Rule;

class UniqueOrderName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Order::whereUserId($value)->count() !== 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Order name you entered has already been taken. Please try another name.';
    }
}
