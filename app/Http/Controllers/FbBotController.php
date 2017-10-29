<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\FbBotResponse;
use App\Repository\JSONRepository;
use App\Repository\SearchRepository;
use App\Formatter\RecordsToBotElements;
use App\Repository\UserSubscriptRepository;

class FbBotController extends Controller
{
    public function searchSiteName(Request $request)
    {
        $userId = $request->input('messenger_user_id');
        $keyword = $request->input('name');

        if (mb_strlen($keyword) < 4) {
            return FbBotResponse::text('關鍵字至少四個字');
        }

        $elements = SearchRepository::searchSiteName($keyword)->map(function ($record) use ($userId) {
            return RecordsToBotElements::toSubSite($record, $userId);
        });;

        $noResult = '沒有找到類似 '.$keyword.' 的站台';
        $tooMuch = FbBotResponse::tooMuchRecordsElement();
        $response = FbBotResponse::items($elements, $noResult, $tooMuch);

        return response()->json($response);
    }

    public function searchRegion(Request $request)
    {
        $userId = $request->input('messenger_user_id');
        $keyword = $request->input('name');
        
        // check
        if (mb_strlen($keyword) < 3) {
            return FbBotResponse::text('關鍵字至少三個字');
        }
        $keywords = explode(' ', $keyword);
        if (count($keywords) > 3) {
            return FbBotResponse::text('關鍵字最多三組');
        }
        
        // find geometries and retrive records
        $geometries = SearchRepository::searchRegion($keywords);
        $records = $geometries->map(function ($geometry) {
            return JSONRepository::region($geometry);
        });

        if (!$records->count()) {
            return FbBotResponse::text('搜尋不到符合的區域');
        }

        $elements = RecordsToBotElements::toSubRegion(collect([
            'regions' => $records->pluck('regions')->flatten()->unique(),
            'pm25' => round($records->pluck('pm25')->avg(), 2),
            'humidity' => round($records->pluck('humidity')->avg(), 2),
            'temperature' => round($records->pluck('temperature')->avg(), 2),
            'ids' => $geometries->pluck('id'),
        ]), $userId);

        $response = FbBotResponse::galleries($elements);
        return response()->json($response);
    }

    public function listUserFavorite(Request $request)
    {
        $fbmid = $request->input('messenger_user_id');

        $repo = new UserSubscriptRepository($fbmid);
        $response = $repo->favoriteRecord();

        return response()->json($response);
    }

    public function addUserFavoriteSite(Request $request, string $fbmid, string $group, string $name)
    {
        $repo = new UserSubscriptRepository($fbmid);
        $response = $repo->addFavoriteSite($group, $name);
        
        return response()->json($response);
    }

    public function addUserFavoriteRegion(Request $request, string $fbmid, string $region)
    {
        $ids = explode('-', $region);
        
        $repo = new UserSubscriptRepository($fbmid);
        $response = $repo->addFavoriteRegion($ids);
        
        return response()->json($response);
    }

    public function removeUserFavorite(Request $request, string $fbmid, int $id)
    {
        $repo = new UserSubscriptRepository($fbmid);
        $response = $repo->removeFavorite($id);
        
        return response()->json($response);
    }
}