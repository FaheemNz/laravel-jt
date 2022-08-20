<?php


namespace App\Utills\Constants;


class ReasonType
{
    const ORDER                     = 1;
    const TRIP                      = 2;
    const OFFER                     = 3;
    const COUNTER_OFFER             = 4;
    const GENERAL                   = 5;
    const USER                      = 6;
    const Transaction               = 7;
    const SETTINGS                  = 8;
    const APP_USABILITY             = 9;
    const OFFER_REJECT              = 10;
    const COUNTER_OFFER_REJECT      = 11;

    const ALL = [
        self::ORDER                 => "order",
        self::TRIP                  => "trip",
        self::OFFER                 => "offer",
        self::COUNTER_OFFER         => "counter_offer",
        self::GENERAL               => "general",
        self::USER                  => "user",
        self::Transaction           => "transaction",
        self::SETTINGS              => "settings",
        self::APP_USABILITY         => "app_usability",
        self::OFFER_REJECT          => "offer_reject",
        self::COUNTER_OFFER_REJECT  => "counter_offer_reject",
    ];
}
