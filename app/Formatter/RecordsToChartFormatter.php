<?php

namespace App\Formatter;

use Illuminate\Support\Collection;

class RecordsToChartFormatter
{
    
    public static function format(Collection $records)
    {
        $history = [];
        
        foreach ($records as $key => $record) {
            $history['Dust2_5'][$key] = $record->pm25;
            $history['Humidity'][$key] = $record->humidity;
            $history['Temperature'][$key] = $record->temperature;
            $history['isotimes'][$key] = str_replace('+00:00', 'Z', $record->published_at->toIso8601String());
        }

        return $history;
    }
}