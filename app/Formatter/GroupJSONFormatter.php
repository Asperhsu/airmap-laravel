<?php

namespace App\Formatter;

use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\Record;
use App\Models\Group;

class GroupJSONFormatter
{
    public static function format(Group $group, Record $record)
    {
        return collect([
            'uniqueKey' => $record->uuid,
            'SiteName'  => $record->name,
            'SiteGroup' => $group->name,
            'Maker'     => $record->maker,
            'LatLng'    => collect([
                'lat'   => $record->lat,
                'lng'   => $record->lng,
            ]),
            'Data'      => collect([
                'Dust2_5'       => $record->pm25,
                'Humidity'      => $record->humidity,
                'Temperature'   => $record->temperature,
                'Create_at'     => str_replace('+00:00', 'Z', $record->published_at->toIso8601String()),
            ]),
            'Analysis'  => collect([
                'ranking' => $record->ranking,
                'status'  => static::analyseStatus($record->indoor, $record->shortterm_pollution, $record->longterm_pollution),
            ]),
        ]);
    }

    public static function analyseStatus($indoor, $shortterm_pollution, $longterm_pollution) {
        $status = [];

        if ($indoor) {
            $status[] = "indoor";
        }
        if ($longterm_pollution) {
            $status[] = "longterm-pollution";
        }

        if ($indoor || $longterm_pollution) {
            $status[] = 'malfunction';
        }

        if ($shortterm_pollution) {
            $status[] = "shortterm-pollution";
        }

        return implode('|', $status);
    }
}