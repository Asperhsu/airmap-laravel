<?php

namespace App\Formatter;

use Illuminate\Support\Collection;
use Carbon\Carbon;

class PredictionToChartFormatter
{
    public static function format(Collection $predictions)
    {
        if (!$predictions->count()) {
            return [
                'labels' => null,
                'data' => null,
            ];
        }

        // merge all time labels
        $times = $predictions->map(function ($items, $method) {
            return $items->pluck('time');
        })->values()->flatten()->unique()->sort(function ($a, $b) {
            return $a->timestamp - $b->timestamp;
        })->values();

        // remap each line to merged labels index
        $datasets = [];
        $predictions->map(function ($items, $method) use ($times, &$datasets) {
            $items->map(function ($item) use ($method, $times, &$datasets) {
                $timeIndex = $times->search($item['time']);
                $datasets[$method][$timeIndex] = $item['value'];
            });
        });

        // fill unindex values to null
        foreach ($datasets as $method => $values) {
            for ($i=0; $i<=$times->count(); $i++) {
                isset($values[$i]) ?: $datasets[$method][$i] = null;
            }
            ksort($datasets[$method]);
        }

        // labels iso 8601
        $labels = $times->map(function ($time) {
            return $time->toIso8601String();
        });

        return [
            'labels' => $labels,
            'data' => $datasets,
        ];
    }
}
