<?php


namespace App\Utills\Constants;

class OrderStatus
{
    //= new
    //= payment_in_progress [when payment initiated]
    //= paid [when payment confirmed]
    //= purchased [when traveler purchased the item(s) and uploaded recept]
    //= custom_paid [if custom paid and uploaded recept]
    //= handedover [when correct 6 digit security code entered by traveler]
    //= rated [when both customer and traveler rated eathother]
//= complete [when remaining payments are paid to both customer and traveler]

    const NEW                   = "new";
    const PAYMENT_IN_PROGRESS   = "payment_in_progress";
    const PAID                  = "paid";
    const PURCHASED             = "purchased";
    const CUSTOM_PAID           = "custom_paid";
    const CODE_GENERATED        = "code_generated";
    const HANDED                = "handed";
    const CUSTOMER_RATED        = "customer_rated";
    const TRAVELER_RATED        = "traveler_rated";
    const RATED                 = "rated";
    const COMPLETED             = "completed";


    const ALL = [
        self::NEW,
        self::PAYMENT_IN_PROGRESS,
        self::PAID,
        self::PURCHASED,
        self::CUSTOM_PAID,
        self::CODE_GENERATED,
        self::HANDED,
        self::TRAVELER_RATED,
        self::CUSTOMER_RATED,
        self::RATED,
        self::COMPLETED
    ];

    const IN_TRANSIT = [
        self::PAID,
        self::PURCHASED,
        self::CUSTOM_PAID,
        self::CODE_GENERATED,
        self::HANDED,
        self::TRAVELER_RATED,
        self::CUSTOMER_RATED,
    ];

    const DONE = [
        self::RATED,
        self::COMPLETED
    ];
}
