<?php

namespace App\Repository;

use DB;
use App\Models\Record;

class SearchRepository
{
    public static function searchSiteName(string $keyword)
    {
        if (mb_strlen($keyword) < 4) {
            return false;
        }

        $subQuery = "(SELECT max(id) as id FROM `records` WHERE `name` like '%{$keyword}%' group by `name`) as keyword";

        return Record::join(DB::raw($subQuery), 'records.id', '=', 'keyword.id')
            ->get();
    }

    public static function searchNearBy(float $lat, float $lng)
    {
        $preciser = function ($value, $ratio) {
            $foo = $value * $ratio;
            return [floor($foo) / $ratio, ceil($foo) / $ratio];
        };

        $records = null;
        $ratios = [100, 50, 10];  // 1 degree about 100 km, 100 mean deviation is 1 km

        foreach ($ratios as $ratio) {
            $query = (new Record)->setTable('latest_records')
                ->whereBetween('lat', $preciser($lat, $ratio))
                ->whereBetween('lng', $preciser($lng, $ratio));

            if ($query->count()) {
                $records = $query->get();
                break;
            }
        }

        return $records;
    }
}
