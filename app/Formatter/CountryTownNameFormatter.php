<?php

namespace App\Formatter;

use Illuminate\Support\Collection;
use App\Models\LatestRecord;

class CountryTownNameFormatter
{
    public static function transferWord(string $name)
    {
        $name = str_replace('台', '臺', $name);

        return $name;
    }

    public static function formatCountry(string $name)
    {
        return static::transferWord($name);
    }

    public static function formatTown(string $name)
    {
        return static::transferWord($name);
    }

    public static function formatRecord(LatestRecord $record)
    {
        return [
            'uid' => $record->group_name.'$'.$record->uuid,
            'name' => $record->name,
            'pm25' => $record->pm25,
        ];
    }

    public static function formatRecords(Collection $records)
    {
        return $records->map(function ($record) {
            return static::formatRecord($record);
        });
    }
}
