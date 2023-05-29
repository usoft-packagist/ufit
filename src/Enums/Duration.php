<?php

namespace Usoft\Ufit\Enums;

class Duration
{
    const SECONDS = 'seconds';
    const HOURS = 'hours';
    const DAYS = 'days';
    const WEEKS = 'weeks';
    const MONTHS = 'months';
    const YEARS = 'years';

    public static function getDurations()
    {
        return [
            self::SECONDS,
            self::HOURS,
            self::DAYS,
            self::WEEKS,
            self::MONTHS,
            self::YEARS,
        ];
    }
}
