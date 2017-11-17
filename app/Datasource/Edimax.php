<?php

namespace App\Datasource;

use Carbon\Carbon;
use App\Models\Record;

class Edimax implements Contract
{
    public static function feedResource()
    {
        return "https://airbox.edimaxcloud.com/devices?token=" . config('datasource.edimax.token');
    }

    public static function parse(array $raw)
    {
        $raw    = collect($raw);
        return [
            'uuid'          => $raw->get('id'),
            'name'          => $raw->get('name'),
            'maker'         => 'Edimax',
            'lat'           => $raw->get('lat'),
            'lng'           => $raw->get('lon'),
            'published_at'  => Carbon::parse($raw->get('time'), 'Asia/Taipei')->timezone('UTC'),
            'pm25'          => $raw->get('pm25'),
            'humidity'      => $raw->get('h'),
            'temperature'   => $raw->get('t'),
        ];
    }
}
