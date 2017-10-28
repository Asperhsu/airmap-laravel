<?php

namespace App\Repository;

use DB;
use App\Models\Record;
use App\Service\FbBotResponse;
use App\Formatter\RecordsToBotElements;

class SearchRepository
{
    public static function searchSiteName(string $keyword, string $userId){
        if (mb_strlen($keyword) < 4) {
            return FbBotResponse::text('關鍵字至少四個字');
        }

        $subQuery = "(SELECT max(id) as id FROM `records` WHERE `name` like '%{$keyword}%' group by `name`) as keyword";
        
        $elements = Record::join(DB::raw($subQuery), 'records.id', '=', 'keyword.id')
            ->get()
            ->map(function ($record) use ($userId) {
                return RecordsToBotElements::toAddSite($record, $userId);
            });
        
        $noResult = '沒有找到類似 '.$keyword.' 的站台';
        $tooMuch = FbBotResponse::tooMuchRecordsElement();
        return FbBotResponse::items($elements, $noResult, $tooMuch);
    }
}