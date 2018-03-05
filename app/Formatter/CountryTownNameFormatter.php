<?php

namespace App\Formatter;

use Illuminate\Support\Collection;

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

    public static function formatRecord(Collection $record)
    {
        return [
            'uid' => $record->get('SiteGroup').'$'.$record->get('uniqueKey'),
            'name' => $record->get('SiteName'),
            'pm25' => $record->get('Data')->get('Dust2_5'),
        ];
    }

    public static function formatRecordsFromUids(Collection $records, Collection $uids)
    {
        $uids = $uids->toArray();

        return $records
            ->filter(function ($record) use ($uids) {
                $uid = $record->get('SiteGroup').'$'.$record->get('uniqueKey');
                return in_array($uid, $uids);
            })
            ->map(function ($record) {
                return static::formatRecord($record);
            })
            ->values();
    }
}
