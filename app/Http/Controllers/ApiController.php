<?php

namespace App\Http\Controllers;

use App\Service\IconMaker;
use Illuminate\Http\Request;
use App\Repository\JSONRepository;
use App\Models\LatestRecord;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function makeIcon(Request $request, float $pm25)
    {
        $color = IconMaker::color($pm25);
        $img   = IconMaker::make($color, $pm25);

        return response($img)->header('Content-Type', 'image/png');
    }

    public function widget(Request $request, string $group, string $uuid)
    {
        $record = JSONRepository::latest($group, $uuid);

        if (!$record) {
            return response()->json([
                'code' => 404,
                'data' => null,
                'message' => 'no match record',
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'data' => $record,
            'message' => 'success',
        ]);
    }

    public function shortcut(string $keyword)
    {
        $uuidCount = LatestRecord::where('uuid', $keyword)->count();

        if ($uuidCount > 0) {
            $query = LatestRecord::where('uuid', $keyword);
        } else {
            $query = LatestRecord::where('name', 'like', '%' . $keyword . '%');
        }

        $record = $query->orderBy('published_at', 'desc')->first();
        if (!$record) {
            return '沒有找到符合的站台';
        }

        Carbon::setLocale('zh-TW');
        $text = sprintf(
            "%s，細懸浮微粒值 %s，%s，%s更新",
            $record->name,
            $record->pm25,
            $this->getSuggestion($record->pm25),
            $record->published_at->diffForHumans()
        );
        return $text;
    }

    protected function getSuggestion(int $value)
    {
        $suggestions = config('pm25-suggestion');

        foreach ($suggestions as $suggestion) {
            if ($value >= $suggestion['min'] && $value <= $suggestion['max']) {
                return $suggestion['text'];
            }
        }

        return '';
    }
}
