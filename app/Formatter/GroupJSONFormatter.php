<?php

namespace App\Formatter;

use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\Record;
use App\Models\Group;
use App\Models\LatestRecord;

class GroupJSONFormatter
{
    public static function format($record)
    {
        $isRecord = is_a($record, Record::class);
        $isLatestRecord = is_a($record, LatestRecord::class);

        if (!$isRecord && !$isLatestRecord) {
            throw new \TypeError('Record should be Record or LatestRecord instance.');
        }

        // grometry
        $geometrySrv = resolve('App\Service\Geometry');
        $feature = $geometrySrv->findFeature($record->lat, $record->lng);
        $geometry = $feature ? collect($feature['properties']) : null;

        return collect([
            'uniqueKey' => $record->uuid,
            'SiteName'  => $record->name,
            'SiteGroup' => $isRecord ? $record->group->name : $record->group_name,
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
            'Geometry' => $geometry,
        ]);
    }

    public static function analyseStatus($indoor, $shortterm_pollution, $longterm_pollution)
    {
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
