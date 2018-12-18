<?php

namespace App\Datasource;

class LassAirbox extends Lass
{
    public static $maker = 'LASS-Airbox';

    public static function feedResource()
    {
        return "https://data.lass-net.org/data/last-all-airbox.json";
    }

    public static function parse(array $raw)
    {
        $feed = parent::parse($raw);

        $feed['name'] = $raw->get('SiteName');
    }
}
