<?php

namespace App\Service;

use Illuminate\Support\Collection;

class FbBotResponse
{
    const MIN_LIST_ELEMENTS = 2;
    const MAX_LIST_ELEMENTS = 4;
    const MIN_GENERIC_ELEMENTS = 0;
    const MAX_GENERIC_ELEMENTS = 10;
    
    public static function text($items)
    {
        $messages = [];
        foreach ((array) $items as $text) {
            $messages[] = compact('text');
        }
        
        return compact('messages');
    }

    public static function galleries(array $elements, $ratio = 'square')
    {
        $ratios = ['square', 'horizontal'];
        if (!in_array($ratio, $ratios)) {
            $ratio = 'square';
        }
        
        $messages = [
            [
                'attachment' => [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'generic',
                        'image_aspect_ratio' => $ratio,
                        'elements' => $elements,
                    ]
                ]
            ]
        ];

        return compact('messages');
    }

    public static function list(array $elements, $topStyle = 'compact')
    {
        if (count($elements) < static::MIN_LIST_ELEMENTS) {
            return false;
        }
        
        $topStyles = ['large', 'compact'];
        if (!in_array($topStyle, $topStyles)) {
            $topStyle = 'compact';
        }
        
        $messages = [
            [
                'attachment' => [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'list',
                        'top_element_style' => $topStyle,
                        'elements' => $elements,
                    ]
                ]
            ]
        ];

        return compact('messages');
    }

    public static function items(Collection $elements, string $noResult = '找不到紀錄', array $tooMuch = [])
    {
        $cnt = $elements->count();

        if (!$cnt) { return static::text($noResult); }

        if ($cnt >= static::MIN_LIST_ELEMENTS && $cnt <= static::MAX_LIST_ELEMENTS) {
            return static::list($elements->toArray());
        }

        if ($cnt >= static::MIN_GENERIC_ELEMENTS && $cnt <= static::MAX_GENERIC_ELEMENTS) {
            return static::galleries($elements->toArray());
        }

        $results = $elements
                    ->slice(0, static::MAX_GENERIC_ELEMENTS - 1)
                    ->push($tooMuch);
        return static::galleries($results->toArray());
    }

    public static function tooMuchRecordsElement(string $overResult = null, string $btnTitle = null, string $btnUrl = null)
    {
        $overResult = $overResult ?: '紀錄超出所能顯示的個數';
        $btnTitle = $btnTitle ?: '開啟網頁檢視完整清單';
        $btnUrl = $btnUrl ?: '';

        return [
            'title' => $overResult,
            'buttons' => [
                [
                    'type' => 'web_url',
                    'title' => $btnTitle,
                    'url' => $btnUrl,
                ]
            ]
        ];
    }
}