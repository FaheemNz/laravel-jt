<?php


namespace App\Utills\Constants;


class OfferStatus
{
    const UNACCEPTED = "unaccepted";
    const ACCEPTED = "accepted";
    const STALE = "stale";
    const REJECTED = "rejected";
    const OPEN = "open";
    const CLOSED = "closed";
    const PAYMENT_IN_PROGRESS = "payment_in_progress";

    const ALL = [
        self::UNACCEPTED,
        self::ACCEPTED,
        self::STALE,
        self::REJECTED,
        self::OPEN,
        self::CLOSED,
        self::PAYMENT_IN_PROGRESS
    ];

    const NEGATIVE = [
        self::UNACCEPTED,
        self::REJECTED,
        self::CLOSED,
    ];

    const POSITIVE = [
        self::ACCEPTED,
        self::STALE,
        self::OPEN,
        self::PAYMENT_IN_PROGRESS
    ];
}
