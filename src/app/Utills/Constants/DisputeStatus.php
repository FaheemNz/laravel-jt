<?php


namespace App\Utills\Constants;


class DisputeStatus
{
    const PENDING       = 1;
    const VIEWED        = 2;
    const RESOLVING     = 3;
    const RESOLVED      = 4;
    const CLOSED        = 5;

    const ALL = [
        self::PENDING     => "pending",
        self::VIEWED      => "viewed",
        self::RESOLVING   => "resolving",
        self::RESOLVED    => "resolved",
        self::CLOSED      => "closed",
    ];
}
