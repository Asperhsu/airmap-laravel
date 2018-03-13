<?php

namespace App\Formatter;

use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RecordsToBotElements
{
    public static function group(Record $record)
    {
        return $record->group_name ?: $record->group->name;
    }

    public static function title(Record $record)
    {
        return sprintf('<%s> %s', static::group($record), $record->name);
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
        return config(sprintf('datasource.%s.logo', strtolower(static::group($record))), '');
    }

    public static function icon(float $value)
    {
        return route('pm25icon', round($value));
    }

    public static function toSubSite(Record $record, string $fbmid)
    {
        return [
            'title' => static::title($record),
            'subtitle' => static::subTitle($record),
            'image_url' => static::icon($record->pm25),
            'default_action' => [
                'type' => 'web_url',
                'url' => route('v4.site').sprintf('#%s$%s', static::group($record), $record->uuid),
            ],
            'buttons' => [
                [
                    'type' => 'json_plugin_url',
                    'title' => '加入我的最愛',
                    'url' => route('bot.user.addsite', [
                        'group' => $record->group_id,
                        'uuid' => $record->uuid,
                    ]),
                ]
            ]
        ];
    }

    public static function toUnsubSite(Record $record, string $fbmid, int $subscriptId)
    {
        return [
            'title' => static::title($record),
            'subtitle' => static::subTitle($record),
            'image_url' => static::icon($record->pm25),
            'default_action' => [
                'type' => 'web_url',
                'url' => route('v4.site').sprintf('#%s$%s', static::group($record), $record->uuid),
            ],
            'buttons' => [
                [
                    'type' => 'json_plugin_url',
                    'title' => '從我的最愛移除',
                    'url' => route('bot.user.remove', [
                        'id' => $subscriptId,
                    ]),
                ]
            ]
        ];
    }

    public static function toSubRegion(Collection $region, string $fbmid)
    {
        $subTitle = implode(chr(10), [
            sprintf('Sites Count: %s', $region->get('site_count')),
            sprintf('Avg PM 2.5__: %s mg/u3', $region->get('pm25')),
            sprintf('Avg Temp____: %s 度', $region->get('temperature')),
            sprintf('Avg Humidity: %s %%', $region->get('humidity')),
        ]);

        return [
            [
                'title' => $region->get('regions')->implode(', '),
                'subtitle' => $subTitle,
                'image_url' => static::icon($region->get('pm25')),
                'buttons' => [
                    [
                        'type' => 'json_plugin_url',
                        'title' => '加入我的最愛',
                        'url' => route('bot.user.addregion', [
                            'region' => $region->get('ids')->implode('-'),
                        ]),
                    ]
                ]
            ]
        ];
    }

    public static function toUnsubRegion(Collection $region, string $fbmid, int $subscriptId)
    {
        $subTitle = implode(chr(10), [
            sprintf('Sites Count: %s', $region->get('site_count')),
            sprintf('Avg PM 2.5__: %s mg/u3', $region->get('pm25')),
            sprintf('Avg Temp____: %s 度', $region->get('temperature')),
            sprintf('Avg Humidity: %s %%', $region->get('humidity')),
        ]);

        return [
            [
                'title' => $region->get('regions')->implode(', '),
                'subtitle' => $subTitle,
                'image_url' => static::icon($region->get('pm25')),
                'buttons' => [
                    [
                        'type' => 'json_plugin_url',
                        'title' => '從我的最愛移除',
                        'url' => route('bot.user.remove', [
                            'id' => $subscriptId,
                        ]),
                    ]
                ]
            ]
        ];
    }
}
