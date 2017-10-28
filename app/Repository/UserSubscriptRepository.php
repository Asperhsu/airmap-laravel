<?php

namespace App\Repository;

use DB;
use Carbon\Carbon;
use App\Models\Record;
use App\Models\Geometry;
use App\Models\UserSubscript;
use App\Service\FbBotResponse;
use App\Formatter\RecordsToBotElements;

class UserSubscriptRepository
{
    protected $fb_m_id;
    
    public function __construct(string $fb_m_id)
    {
        $this->fb_m_id = $fb_m_id;
    }

    public function addFavoriteSite(int $groupId, string $name)
    {
        $exist = Record::where('group_id', $groupId)
            ->where('name', $name)
            ->count();

        if (!$exist) {
            return FbBotResponse::text($name . ' 這個站點不存在');
        }

        $exist = UserSubscript::where('fb_m_id', $this->fb_m_id)
            ->where('group_id', $groupId)
            ->where('name', $name)
            ->count();

        if ($exist) {
            $msg = '已在我的最愛內';
        } else {
            $subscript = new UserSubscript;
            $subscript->fb_m_id = $this->fb_m_id;
            $subscript->group_id = $groupId;
            $subscript->name = $name;
            $result = $subscript->save();
            
            $msg = $result ? '訂閱成功' : '訂閱失敗';
        }

        return FbBotResponse::text($name . ' ' . $msg);
    }

    public function addFavoriteRegion(int $geometryId)
    {
        $geometry = Geometry::find($geometryId);

        if (!$geometry) {
            return FbBotResponse::text($name . ' 這個區域不存在');
        }

        $subscript = new UserSubscript;
        $subscript->fb_m_id = $this->fb_m_id;
        $subscript->geometry_id = $this->geometryId;
        $result = $subscript->save();

        $msg = $result ? '訂閱成功' : '訂閱失敗';
        return FbBotResponse::text($geometry->full_text . ' ' . $msg);
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
            ->orderBy('geometry_id')
            ->get()
            ->map(function ($subscript) use (&$recordAttrs, &$geometryIds) {
                if ($subscript->geometry_id) {
                    $geometryIds[$subscript->id] = $subscript->geometry_id;
                }

                if ($subscript->group_id && $subscript->name) {
                    $recordAttrs[$subscript->id] = [
                        'group_id' => $subscript->group_id,
                        'name' => $subscript->name,
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
                return RecordsToBotElements::toRemoveSite($record, $this->fb_m_id, $subId);
            });
        }

        // accordin recording count response type
        $elements = $records->concat($regions)->values();

        $noResult = '您沒有訂閱站點';
        $tooMuch = FbBotResponse::tooMuchRecordsElement();
        return FbBotResponse::items($elements, $noResult, $tooMuch);
    }

    public function fetchRegions(array $ids)
    {
        if (!count($ids)) { return collect(); }
        
        $regions = [];

        $records = Record::join('geometry_record', function ($join) use ($geometryIds) {
                $join->on('geometry_record.record_id', '=', 'records.id');
                $join->whereIn('geometry_id', array_values($ids));
                $join->select(DB::raw('max(record_id) as record_id'));
                $join->groupBy('record_id');
            })->get()
            ->map(function ($record) use ($regions) {
                $regions[$record->geometry_id][] = $record->pm25;
            });

        $elements = Geometry::whereIn('id', $ids)->get()
            ->map(function ($geometry, $id) {
                $title = $geometry->full_text;
                $avg = collect($regions[$id])->avg();

                return [
                    'title' => sprintf('<%s>%s', $title),
                    'subtitle' => sprintf('%d ug/m3', $avg),
                    'buttons' => [
                        [
                            'type' => 'json_plugin_url',
                            'title' => '取消訂閱',
                            'url' => route('bot.user.remove', [
                                'fbmid' => $this->fb_m_id,
                                'id' => $id,
                            ]),
                        ]
                    ]
                ];
            });
        
        dd($elements);
        return $elements;

        // id => full_text, avg pm2.5
    }
}