<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\LatestRecord;
use App\Service\GaHelper;

class ShortcutController extends Controller
{
    public function search(string $keyword)
    {
        resolve(GaHelper::class)->pageView('/shortcut/' . $keyword);

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
