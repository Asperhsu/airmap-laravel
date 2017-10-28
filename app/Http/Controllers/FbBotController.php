<?php

namespace App\Http\Controllers;

use App\Service\FbBotResponse;
use Illuminate\Http\Request;

class FbBotController extends Controller
{
    public function searchSiteName(Request $request)
    {
        $userId = $request->input('messenger_user_id');
        $siteName = $request->input('siteName');
        
        $response = [];
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
        $userId = $request->input('messenger_user_id');

        $response = FbBotResponse::list([
            [
                'title' => 'Title',
                'subtitle' => 'subtitle',
                'buttons' => [
                    [
                        'type' => 'json_plugin_url',
                        'title' => 'ADD',
                        'url' => route('bot.user.addsite', ['group' => 'group', 'name' => 'name']),
                    ]
                ]
            ],
            [
                'title' => 'Title2',
                'subtitle' => 'subtitle',
            ]
        ]);
        return response()->json($response);
    }

    public function addUserFavoriteSite(Request $request, $group, $name)
    {
        logger([$request->method(), $request->all()]);

        $response = FbBotResponse::text('success');
        return response()->json($response);
    }

    public function addUserFavoriteRegion(Request $request, $region)
    {
        logger([$request->method(), $request->all()]);

        $response = [];
        return response()->json($response);
    }

    public function removeUserFavorite(Request $request, int $id)
    {
        logger($id);
        
        $response = [];
        return response()->json($response);
    }
}