<?php

namespace App\Http\Controllers;

use App\Service\FbBotResponse;
use App\Repository\SearchRepository;
use App\Repository\UserSubscriptRepository;
use Illuminate\Http\Request;

class FbBotController extends Controller
{
    public function searchSiteName(Request $request)
    {
        $userId = $request->input('messenger_user_id');
        $name = $request->input('name');

        $response = SearchRepository::searchSiteName($name, $userId);
        return response()->json($response);
    }

    public function searchRegion(Request $request)
    {
        $userId = $request->input('messenger_user_id');
        $region = $request->input('region');
        
        $response = [];
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

    public function addUserFavoriteRegion(Request $request, $region)
    {
        logger([$request->method(), $request->all()]);

        $response = [];
        return response()->json($response);
    }

    public function removeUserFavorite(Request $request, string $fbmid, int $id)
    {
        $repo = new UserSubscriptRepository($fbmid);
        $response = $repo->removeFavorite($id);
        
        return response()->json($response);
    }
}