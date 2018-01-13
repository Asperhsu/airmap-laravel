<?php

namespace App\Http\Controllers;

use App\Service\IconMaker;
use Illuminate\Http\Request;
use App\Repository\JSONRepository;

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
            'message' => 'no match record',
        ]);
    }
}
