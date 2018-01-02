<?php

namespace App\Datasource;

use Carbon\Carbon;
use App\Models\Record;
use App\Models\Thinkspeak;

class Thingspeak implements Contract
{
    public static function feedResource()
    {
        return "https://api.thingspeak.com/channels/{{channel}}/feeds.json?results=1";
    }

    public static function parse(array $raw)
    {
        $raw        = collect($raw);
        $mapping    = $raw->get('fieldsMap');

        return [
            'uuid'          => $raw->get('id'),
            'name'          => $raw->get('name'),
            'maker'         => $raw->get('maker'),
            'lat'           => $raw->get('latitude'),
            'lng'           => $raw->get('longitude'),
            'published_at'  => Carbon::parse($raw->get('created_at')),
            'pm25'          => $raw->get($mapping->get('pm25')),
            'humidity'      => $raw->get($mapping->get('humidity')),
            'temperature'   => $raw->get($mapping->get('temperature')),
        ];
    }
}
