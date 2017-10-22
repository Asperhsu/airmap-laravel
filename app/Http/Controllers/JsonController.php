<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\JSONRepository;
use App\Models\Group;

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
}
