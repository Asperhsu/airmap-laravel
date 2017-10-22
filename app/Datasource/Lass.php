<?php

namespace App\Datasource;

use Carbon\Carbon;
use App\Models\Record;

class Lass implements Contract
{
    static $maker = 'LASS';
    
    public static function feedResource()
    {
        return "https://data.lass-net.org/data/last-all-lass.json";
    }

    public static function parse(array $raw)
    {
        $raw    = collect($raw);
        $feed   = [
            'uuid'          => $raw->get('device_id'),
            'name'          => $raw->get('device_id'),
            'maker'         => static::$maker,
            'lat'           => $raw->get('gps_lat'),
            'lng'           => $raw->get('gps_lon'),
            'published_at'  => Carbon::parse($raw->get('timestamp')),  //ISO8601
            'pm25'          => null,
            'humidity'      => null,
            'temperature'   => null,
        ];

        $raw->only(['s_d0', 's_d1', 's_d2', 's_h0', 's_h2', 's_t0', 's_t2'])
            ->each(function ($value, $key) use (&$feed) {
                if (!is_numeric($value)) {
                    return;
                }
                
                if ($key == 's_d0') {
                    $feed['pm25'] = $value;
                }
                
                if (in_array($key, ['s_h0', 's_h2'])) {
                    $feed['humidity'] = $value;
                }

                if (in_array($key, ['s_t0', 's_t2'])) {
                    $feed['temperature'] = $value;
                }
            });
        
        return $feed;
    }
}
