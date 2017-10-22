<?php

namespace App\Datasource;

use Carbon\Carbon;
use App\Models\Record;

class Asus implements Contract
{
    public static function feedResource()
    {
        return "https://airbox.asuscloud.com/airbox/messages/";
    }

    public static function parse(array $raw)
    {
        $raw    = collect($raw);
        return [
            'uuid'          => $raw->get('id'),
            'name'          => $raw->get('name'),
            'maker'         => 'Asus',
            'lat'           => $raw->get('lat'),
            'lng'           => $raw->get('lng'),
            'published_at'  => Carbon::parse($raw->get('time'), 'Asia/Taipei')->timezone('UTC'),
            'pm25'          => $raw->get('pm25'),
            'humidity'      => $raw->get('humidity'),
            'temperature'   => $raw->get('temperature'),
        ];
    }
}
