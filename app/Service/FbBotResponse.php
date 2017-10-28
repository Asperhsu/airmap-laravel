<?php

namespace App\Service;

class FbBotResponse
{
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
        if (count($elements) < 2) {
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
}