<?php

namespace App\Repository;

use DB;
use Carbon\Carbon;
use App\Models\Record;
use App\Models\Geometry;
use App\Models\UserSubscript;
use App\Service\FbBotResponse;

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

        $subscript = new UserSubscript;
        $subscript->fb_m_id = $this->fb_m_id;
        $subscript->group_id = $this->groupId;
        $subscript->name = $this->name;
        $result = $subscript->save();

        $msg = $result ? '訂閱成功' : '訂閱失敗';
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

    public function favoriteRecord()
    {
        Carbon::setLocale('zh-TW');
        $recordAttrs = [];
        $geometryIds = [];
        
        // load user subscriptions
        UserSubscript::where('fb_m_id', $this->fb_m_id)
            ->with('group')
            ->orderBy('group_id')
            ->orderBy('geometry_id')
            ->get()
            ->map(function ($subscript) use (&$recordAttrs, &$geometryIds) {
                if ($subscript->group_id && $subscript->name) {
                    $recordAttrs[] = compact('group_id', 'name');
                    return;
                }
                if ($subscript->geometry_id) {
                    $geometryIds[] = $subscript->geometry_id;
                }
            });
        
        // fetch records
        $records = collect($recordAttrs)->map(function ($where) {
            $record = Record::where($where)->orderBy('published_at', 'desc')->first();
            return $this->transformRecordToElement($record);
        });
        dd($records);
        
        $regions = $this->fetchRegions($geometryIds);

        // accordin recording count response type
        $count = $records->count() + $regions->count();
        if ($count === 0) {
            return FbBotResponse::text('你還沒有訂閱喔');
        }
        if ($count === 1) {
            return FbBotResponse::galleries($elements);
        }        
        return FbBotResponse::list($elements);
    }

    public function transformRecordToElement(Record $record)
    {
        $time = $record->published_at->diffForHumans();
        
        return [
            'title' => sprintf('<%s>%s', $record->group, $record->name),
            'subtitle' => sprintf('%d ug/m3 (%s)', $record->pm25, $time),
            'buttons' => [
                [
                    'type' => 'json_plugin_url',
                    'title' => '取消訂閱',
                    'url' => route('bot.user.remove', [
                        'group' => $record->group_id, 
                        'name' => $record->name,
                    ]),
                ]
            ]
        ];
    }

    public function fetchRegions(array $ids)
    {
        $regions = [];

        $records = Record::join('geometry_record', function ($join) use ($geometryIds) {
            $join->on('geometry_record.record_id', '=', 'records.id');
            $join->whereIn('geometry_id', $ids);
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
                            'url' => route('bot.user.remove', ['region' => $id]),
                        ]
                    ]
                ];
            });
        
        dd($elements);
        return $elements;

        // id => full_text, avg pm2.5
    }
}