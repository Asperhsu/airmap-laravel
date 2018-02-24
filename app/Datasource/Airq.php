<?php

namespace App\Datasource;

use Carbon\Carbon;
use App\Models\Record;

class Airq implements Contract
{
    public static function feedResource()
    {
        return "http://www.airq.org.tw/Home/realtime10m";
    }

    public static function parse(array $raw)
    {
        $raw    = collect($raw);
        return [
            'uuid'          => md5($raw->get('Name').$raw->get('Latitude').$raw->get('Longitude')),
            'name'          => $raw->get('Name'),
            'maker'         => 'Airq',
            'lat'           => $raw->get('Latitude'),
            'lng'           => $raw->get('Longitude'),
            'published_at'  => static::parseTime($raw->get('EndTime'))->timezone('UTC'),
            'pm25'          => $raw->get('Value'),
            'humidity'      => null,
            'temperature'   => null,
        ];
    }

    public static function parseTime(string $timeString)
    {
        if (strpos($timeString, '上午')) {
            $timeString = str_replace('上午 ', '', $timeString) . ' AM';
        }

        if (strpos($timeString, '下午')) {
            $timeString = str_replace('下午 ', '', $timeString) . ' PM';
        }

        return Carbon::createFromFormat('Y/n/j h:i:s A', $timeString, 'Asia/Taipei');
    }
}
