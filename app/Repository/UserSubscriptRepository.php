<?php

namespace App\Repository;

use DB;
use Carbon\Carbon;
use App\Models\Record;
use App\Models\Geometry;
use App\Models\UserSubscript;
use App\Service\FbBotResponse;
use App\Formatter\RecordsToBotElements;
use App\Repository\JSONRepository;

class UserSubscriptRepository
{
    protected $fb_m_id;
    
    public function __construct(string $fb_m_id)
    {
        $this->fb_m_id = $fb_m_id;
    }

    public function addFavoriteSite(int $groupId, string $uuid)
    {
        $exist = UserSubscript::where('fb_m_id', $this->fb_m_id)
            ->where('group_id', $groupId)
            ->where('uuid', $uuid)
            ->count();

        if ($exist) {
            $msg = '已在我的最愛內';
        } else {
            $subscript = new UserSubscript;
            $subscript->fb_m_id = $this->fb_m_id;
            $subscript->group_id = $groupId;
            $subscript->uuid = $uuid;
            $result = $subscript->save();
            
            $msg = $result ? '訂閱成功' : '訂閱失敗';
        }

        return FbBotResponse::text($msg);
    }

    public function addFavoriteRegion(array $geometryIds)
    {
        $exist = UserSubscript::where('geometry_ids', $geometryIds)
            ->count();

        if ($exist) {
            $msg = '已在我的最愛內';
            return FbBotResponse::text($msg);
        }

        $subscript = new UserSubscript;
        $subscript->fb_m_id = $this->fb_m_id;
        $subscript->geometry_ids = $geometryIds;
        $result = $subscript->save();

        $msg = $result ? '訂閱成功' : '訂閱失敗';
        return FbBotResponse::text($msg);
    }

    public function removeFavorite(int $id)
    {
        $result = UserSubscript::find($id)->delete();

        $msg = $result ? '取消訂閱成功' : '取消訂閱失敗';
        return FbBotResponse::text($msg);
    }

    public function favoriteRecord()
    {
        $geometryIds = [];
        $recordAttrs = [];
        
        // load user subscriptions
        UserSubscript::where('fb_m_id', $this->fb_m_id)
            ->with('group')
            ->orderBy('group_id')
            ->orderBy('geometry_ids')
            ->get()
            ->map(function ($subscript) use (&$recordAttrs, &$geometryIds) {
                if ($subscript->geometry_ids) {
                    $geometryIds[$subscript->id] = $subscript->geometry_ids;
                }

                if ($subscript->group_id && $subscript->uuid) {
                    $recordAttrs[$subscript->id] = [
                        'group_id' => $subscript->group_id,
                        'uuid' => $subscript->uuid,
                    ];
                    return;
                }
            });
        
        // fetch records
        $regions = collect();
        if (count($geometryIds)) {
            $regions = $this->fetchRegions($geometryIds);
        }

        $records = collect();
        if (count($recordAttrs)) {
            $records = collect($recordAttrs)->map(function ($where, $subId) {
                $record = Record::where($where)->orderBy('published_at', 'desc')->first();
                return RecordsToBotElements::toUnsubSite($record, $this->fb_m_id, $subId);
            });
        }

        // accordin recording count response type
        $elements = $records->concat($regions)->values();

        $noResult = '您沒有訂閱站點';
        $tooMuch = FbBotResponse::tooMuchRecordsElement();
        return FbBotResponse::items($elements, $noResult, $tooMuch);
    }

    public function fetchRegions(array $geometryIds)
    {
        if (!count($geometryIds)) { return collect(); }

        $elements = collect();

        foreach ($geometryIds as $subId => $ids) {
            $records = Geometry::whereIn('id', $ids)
                ->get()
                ->map(function ($geometry) {
                    return JSONRepository::region($geometry);
                });

            $element = RecordsToBotElements::toUnsubRegion(collect([
                'regions' => $records->pluck('regions')->flatten()->unique(),
                'site_count' => $records->pluck('site_count')->sum(),
                'pm25' => round($records->pluck('pm25')->avg(), 2),
                'humidity' => round($records->pluck('humidity')->avg(), 2),
                'temperature' => round($records->pluck('temperature')->avg(), 2),
                'ids' => collect($ids),
            ]), $this->fb_m_id, $subId);

            $elements = $elements->concat($element);
        }
        
        return $elements;
    }
}