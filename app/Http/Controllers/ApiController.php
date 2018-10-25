<?php

namespace App\Http\Controllers;

use App\Service\IconMaker;
use Illuminate\Http\Request;
use App\Repository\JSONRepository;
use Carbon\Carbon;
use App\Service\GaHelper;
use App\Repository\SearchRepository;
use App\Formatter\GroupJSONFormatter;

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

    public function searchNearBy(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        if (!$lat || !$lng) {
            return response()->json([
                'success' => false,
                'msg' => 'need query parameter lat and lng',
            ], 400);
        }

        $records = SearchRepository::searchNearBy($lat, $lng);
        if ($records) {
            return response()->json([
                'success' => true,
                'sites' => $records->map(function ($record) {
                    return GroupJSONFormatter::format($record);
                })
            ]);
        }

        return response()->json([
            'success' => true,
            'sites' => [],
        ]);
    }
}
