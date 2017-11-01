<?php

namespace App\Repository;

use DB;
use App\Models\Record;
use App\Models\Geometry;

class SearchRepository
{
    public static function searchSiteName(string $keyword){
        if (mb_strlen($keyword) < 4) {
            return false;
        }

        $subQuery = "(SELECT max(id) as id FROM `records` WHERE `name` like '%{$keyword}%' group by `name`) as keyword";
        
        return Record::join(DB::raw($subQuery), 'records.id', '=', 'keyword.id')
            ->get();
    }

    public static function searchRegion(array $keywords)
    {
        $uniqueIds = null;
        
        // find all keyword intersects ids
        foreach ($keywords as $keyword) {
            if (mb_strlen($keyword) < 2) {
                continue;
            }

            $ids = Geometry::where('level1' ,'like', '%'.$keyword.'%')
                ->orWhere('level2' ,'like', '%'.$keyword.'%')
                ->orWhere('level3' ,'like', '%'.$keyword.'%')
                ->orWhere('level4' ,'like', '%'.$keyword.'%')
                ->select('id')
                ->get()
                ->pluck('id');
                
            $uniqueIds = $uniqueIds ? $uniqueIds->intersect($ids) : $ids;
        };

        return Geometry::whereIn('id', $uniqueIds)->get();
    }

    public static function searchNearBy(float $lat, float $lng)
    {
        $preciser = function ($value, $ratio) {
            $foo = $value * $ratio;
            return [floor($foo) / $ratio, ceil($foo) / $ratio];
        };
        
        $records = null;
        $ratios = [100, 10, 1];  // 1 degree about 100 km, 100 mean deviation is 1 km

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