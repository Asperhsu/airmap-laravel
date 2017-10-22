<?php

namespace App\Datasource;

class LassEEVEE extends Lass
{
    static $maker = 'LASS-EEVEE';
    
    public static function feedResource()
    {
        return "https://pm25.lass-net.org/data/last-all-eevee.json";
    }
}
