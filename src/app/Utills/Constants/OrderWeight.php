<?php


namespace App\Utills\Constants;


class OrderWeight
{
    const LIGHT     = 1;
    const MEDIUM    = 2;
    const HEAVY     = 3;

    const ALL = [
        self::LIGHT     => "Light",
        self::MEDIUM    => "Medium",
        self::HEAVY     => "Heavy",
    ];
}
