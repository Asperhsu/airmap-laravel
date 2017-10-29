<?php

namespace App\Service;

use Cache;
use Illuminate\Support\Collection;

class JsonCache {
    public static $groupExpireMins = 5;
    public static $latestExpireMins = 1;
    public static $historyExpireMins = 1;
    public static $geometryExpireMins = 5;

    public static function cacheKey($key)
    {
        return static::class . '|' . $key;
    }

    public static function group(int $id, Collection $records = null)
    {
        $key = 'group-'.$id;
        
        if (!$records) {
            return Cache::get(static::cacheKey($key));
        }
        
        return Cache::put(static::cacheKey($key), $records, static::$groupExpireMins);
    }

    public static function forgetGroup(int $id)
    {
        static::forgetLatest($id);
        static::forgetHistory($id);
        
        $key = 'group-'.$id;
        return Cache::forget($key);
    }

    public static function keyCollection(string $key, string $value = null, bool $clear = false)
    {
        if ($clear) {
            return Cache::forget($key);
        }
        
        // getter
        $collection = Cache::get(static::cacheKey($key), collect());
        if (!$value) {
            return $collection;
        }
        
        // setter
        $collection->push($value);
        return Cache::forever(static::cacheKey($key), $collection);
    }

    public static function latest(int $groupId, string $uuid, Collection $records = null)
    {
        $key = implode('|', ['latest', $groupId, $uuid]);

        if (!$records) {
            return Cache::get(static::cacheKey($key));
        }

        static::keyCollection('latest', $key);
        return Cache::put(static::cacheKey($key), $records, static::$latestExpireMins);
    }

    public static function forgetLatest(int $groupId)
    {
        static::keyCollection('latest')->each(function ($key) {
            Cache::forget($key);
        });
        static::keyCollection('latest', null, true);
    }


    public static function history(int $groupId, string $uuid, array $records = null)
    {
        $key = implode('|', ['history', $groupId, $uuid]);

        if (!$records) {
            return Cache::get(static::cacheKey($key));
        }

        static::keyCollection('history', $key);
        return Cache::put(static::cacheKey($key), $records, static::$historyExpireMins);
    }

    public static function forgetHistory(int $groupId)
    {
        static::keyCollection('history')->each(function ($key) {
            Cache::forget($key);
        });
        static::keyCollection('history', null, true);
    }

    public static function geometry(int $id, Collection $record = null)
    {
        $key = 'geometry-'.$id;
        
        if (!$record) {
            return Cache::get(static::cacheKey($key));
        }
        
        return Cache::put(static::cacheKey($key), $record, static::$geometryExpireMins);
    }

    public static function forgetGeometry(int $id)
    {
        $key = 'geometry-'.$id;
        return Cache::forget($key);
    }

}