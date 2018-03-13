<?php

namespace App\Service;

use Carbon\Carbon;

class Widget
{
    const COLORS = [    // PM2.5
        11 => '#9CFF9C',
        23 => '#31FF00',
        35 => '#31CF00',
        41 => '#FFFF00',
        47 => '#FFCF00',
        53 => '#FF9A00',
        58 => '#FF6464',
        64 => '#FF0000',
        70 => '#990000',
        71 => '#CE30FF',
    ];

    public static function color(float $value)
    {
        $colors = static::COLORS;
        $values = array_keys($colors);
        $lastColorValue = array_pop($values);

        foreach ($colors as $maxValue => $color) {
            if ($value <= $maxValue) {
                return $color;
            }

            if ($value >= $lastColorValue) {
                return $colors[$lastColorValue];
            }
        }

        return null;
    }

    public static function humanTime(string $dateString)
    {
        Carbon::setLocale(config('app.locale'));
        return Carbon::parse($dateString)->diffForHumans();
    }
}
