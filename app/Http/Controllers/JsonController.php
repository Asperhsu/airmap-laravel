<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\JSONRepository;
use App\Models\Group;
use App\Service\LassPrediction;

class JsonController extends Controller
{
    public function group(Request $request, string $json)
    {
        $group = str_replace('.json', '', $json);

        $records = JSONRepository::group($group);
        abort_unless($records, 404);

        return response()->json($records);
    }

    public function airmap(Request $request)
    {
        $records = JSONRepository::groups();

        return response()->json($records);
    }

    public function townmap(Request $request)
    {
        $records = JSONRepository::townmap();

        return response()->json($records);
    }

    public function lastest(Request $request)
    {
        $group  = $request->input('group');
        $uuid   = $request->input('id');
        abort_unless($group && $uuid, 402);

        $record = JSONRepository::latest($group, $uuid);
        return response()->json($record);
    }

    public function history(Request $request)
    {
        $group  = $request->input('group');
        $uuid   = $request->input('id');
        $start  = $request->input('start'); // timestamp
        $end    = $request->input('end');   // timestamp
        abort_unless($group && $uuid && $start && $end, 402);

        $records = JSONRepository::history($group, $uuid, $start, $end);
        return response()->json($records);
    }

    public function bounds(Request $request)
    {
        $bounds = $request->input('bounds');
        abort_unless($bounds, 402);

        $northEast = $sourthWest = [];
        [
            $sourthWest['lat'], $sourthWest['lng'],
            $northEast['lat'], $northEast['lng']
        ] = array_map(function ($value) {
            return floatval($value);
        }, explode(',', $bounds));

        $records = JSONRepository::bounds($northEast, $sourthWest);
        return response()->json($records);
    }

    public function prediction(Request $request)
    {
        $uuid   = $request->input('id');
        abort_unless($uuid, 402);

        $service = new LassPrediction();
        return $service->findUuid('74DA3895DED4');
    }
}
