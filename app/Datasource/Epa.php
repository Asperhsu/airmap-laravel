<?php

namespace App\Datasource;

use Carbon\Carbon;
use App\Models\Record;

class Epa implements Contract
{
    public static function feedResource()
    {
        return "http://opendata2.epa.gov.tw/AQI.json";
    }

    public static function parse(array $raw)
    {
        $raw = collect($raw);

        // set published time to cueent minute prevent valid time filter
        $nowMinute = Carbon::now('Asia/Taipei')->minute;
        $published_at = Carbon::parse($raw->get('PublishTime'), 'Asia/Taipei')->minute($nowMinute);

        return [
            'uuid'          => $raw->get('SiteEngName'),
            'name'          => $raw->get('SiteName'),
            'maker'         => 'EPA',
            'lat'           => $raw->get('TWD97Lat'),
            'lng'           => $raw->get('TWD97Lon'),
            'published_at'  => $published_at->timezone('UTC'),
            'pm25'          => $raw->get('PM2.5'),
            'humidity'      => null,
            'temperature'   => null,
        ];
    }
}
