<?php

namespace App\Datasource;

class Lass4U extends Lass
{
    static $maker = 'LASS-4U';
    
    public static function feedResource()
    {
        return "https://data.lass-net.org/data/last-all-lass4u.json";
    }
}
