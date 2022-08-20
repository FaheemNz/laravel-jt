<?php


namespace App\Utills\Constants;


class DefaultStatus
{
    const ACTIVE = 1;
    const IN_ACTIVE = 2;

    const ALL = [
      self::ACTIVE      => "Active",
      self::IN_ACTIVE   => "In Active"
    ];
}
