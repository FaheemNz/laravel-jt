<?php


namespace App\Utills\Constants;


class SystemSettingsType
{
    const APP       = 1;
    const ADMIN     = 2;
    const POLICY    = 3;

    const ALL = [
        self::APP       => "App",
        self::ADMIN     => "Admin",
        self::POLICY    => "Policy",
    ];

}
