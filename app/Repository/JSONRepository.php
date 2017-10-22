<?php

namespace App\Repository;

use DB;
use Carbon\Carbon;
use App\Models\Group;
use App\Models\Record;
use App\Formatter\GroupJSONFormatter;
use App\Formatter\RecordsToChartFormatter;
use App\Service\JsonCache;

class JSONRepository
{
    /**
     * record published time filter minutes
     *
     * @var integer
     */
    public static $validMins = 30;

    /**
     * get valid published time
     *
     * @return Carbon\Carbon
     */
    protected static function validTime()
    {
        return Carbon::now()->subMinutes(static::$validMins);
    }
    
    /**
     * gind enabled group from group name
     *
     * @param string $group
     * @return App\Models\Group
     */
    public static function findGroup(string $group)
    {
        return Group::where('enable', true)
                ->where('name', $group)
                ->first();
    }

    /**
     * get group records
     *
     * @param string $group
     * @return Collection
     */
    public static function group($group)
    {
        if (!is_a($group, Group::class) && !($group = static::findGroup($group)) ) {
            return false;
        }

        // if cache exists, return cache
        if ($value = JsonCache::group($group->id)) {
            return $value;
        }

        // get latest record id for each uuid
        $subQuery = '(SELECT MAX(id) as id FROM records WHERE group_id='.$group->id.' GROUP BY uuid) IDS';
        
        $records = Record::join(DB::raw($subQuery), function ($join) {
                $join->on('records.id', '=' , 'IDS.id');
            })->leftJoin('lass_analyses', 'records.uuid', 'lass_analyses.uuid')
            ->where('published_at', '>=', static::validTime())
            ->get()
            ->map(function ($record) use ($group) {
                return GroupJSONFormatter::format($group, $record);
            });

        JsonCache::group($group->id, $records);
        return $records;
    }

    /**
     * get all groups records
     *
     * @return Collection
     */
    public static function groups()
    {
        $records = collect();
        
        Group::where('enable', true)->each(function ($group) use (&$records) {
            if ($groupRecords = static::group($group)) {
                $records = $records->merge($groupRecords);
            }
        });

        return $records;
    }
    
    /**
     * get latest record for specify device
     *
     * @param string $group
     * @param string $uuid
     * @return Collection
     */
    public static function latest(string $group, string $uuid)
    {
        $group = static::findGroup($group);
        if (! $group) {
            return false;
        }

        // if cache exists, return cache
        if ($value = JsonCache::latest($group->id, $uuid)) {
            return $value;
        }

        $record = Record::where('group_id', $group->id)
                ->leftJoin('lass_analyses', 'records.uuid', 'lass_analyses.uuid')
                ->where('records.uuid', $uuid)
                ->orderBy('published_at', 'desc')
                ->first();
        $record = GroupJSONFormatter::format($group, $record);

        JsonCache::latest($group->id, $uuid, $record);
        return $record;
    }

    /**
     * get history chart data for specify device
     *
     * @param string $group
     * @param string $uuid
     * @param int $start  timestamp
     * @param int $end    timestamp
     * @return array
     */
    public static function history(string $group, string $uuid, int $start, int $end)
    {
        $group = static::findGroup($group);
        if (! $group) {
            return false;
        }

        // if cache exists, return cache
        if ($value = JsonCache::history($group->id, $uuid)) {
            return $value;
        }
        
        $start = Carbon::createFromTimeStampUTC($start);
        $end   = Carbon::createFromTimeStampUTC($end);
        
        $records = Record::where('group_id', $group->id)
                ->leftJoin('lass_analyses', 'records.uuid', 'lass_analyses.uuid')
                ->where('records.uuid', $uuid)
                ->whereBetween('published_at', [$start, $end])
                ->orderBy('published_at', 'desc')
                ->get();
                
        $records = RecordsToChartFormatter::format($records);

        JsonCache::history($group->id, $uuid, $records);
        return $records;
    }
}