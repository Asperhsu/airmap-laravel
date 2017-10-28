<?php

namespace App\Formatter;

use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RecordsToBotElements
{
    public static function title(Record $record)
    {
        return sprintf('<%s> %s', $record->group->name, $record->name);
    }

    public static function subTitle(Record $record)
    {
        Carbon::setLocale('zh-TW');
        $items = [];

        if ($record->pm25) {
            $items[] = sprintf('PM 2.5__: %s mg/u3', $record->pm25);
        }
        if ($record->temperature) {
            $items[] = sprintf('Temp____: %s 度', $record->temperature);
        }
        if ($record->humidity) {
            $items[] = sprintf('Humidity: %s %%', $record->humidity);
        }

        $time = $record->published_at;
        $items[] = sprintf('%s (%s)', $time->toDateTimeString(), $time->diffForHumans());
        
        return implode(chr(10), $items);
    }

    public static function logo(Record $record)
    {
        return config(sprintf('datasource.%s.logo', strtolower($record->group->name)), '');
    }
    
    public static function toAddSite(Record $record, string $fbmid)
    {
        return [
            'title' => static::title($record),
            'subtitle' => static::subTitle($record),
            'image_url' => static::logo($record),
            'default_action' => [
                'type' => 'web_url',
                'url' => route('site').sprintf('#%s$%s', $record->group->name, $record->name),
            ],
            'buttons' => [
                [
                    'type' => 'json_plugin_url',
                    'title' => '加入我的最愛',
                    'url' => route('bot.user.addsite', [
                        'fbmid' => $fbmid,
                        'group' => $record->group_id, 
                        'name' => $record->name,
                    ]),
                ]
            ]
        ];
    }

    public static function toRemoveSite(Record $record, string $fbmid, int $subscriptId)
    {
        return [
            'title' => static::title($record),
            'subtitle' => static::subTitle($record),
            'image_url' => static::logo($record),
            'default_action' => [
                'type' => 'web_url',
                'url' => route('site').sprintf('#%s$%s', $record->group->name, $record->name),
            ],
            'buttons' => [
                [
                    'type' => 'json_plugin_url',
                    'title' => '從我的最愛移除',
                    'url' => route('bot.user.remove', [
                        'fbmid' => $fbmid,
                        'id' => $subscriptId, 
                    ]),
                ]
            ]
        ];
    }
}