<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\JSONRepository;
use View;

class WidgetV4Controller extends Controller
{
    public function create(Request $request, string $group, string $uuid)
    {
        $items = collect([
            ['type' => 'text', 'height' => 230],
            ['type' => 'marker', 'height' => 220],
            ['type' => 'thin', 'height' => 150],
        ]);

        $vueScript = '<script src="' . url('js/airmap-widget.js') . '"></script>';
        $vues = $items->mapWithKeys(function ($item) use ($group, $uuid) {
            return [$item['type'] => view('v4.widget.vue', [
                'group' => $group, 'uuid' => $uuid, 'type' => $item['type'], 'height' => $item['height']
            ])->render()];
        });


        return view('v4.widget.create-js', compact('group', 'uuid', 'vueScript', 'vues'));
    }

    public function show(Request $request, string $type, string $group, string $uuid)
    {
        abort_unless($group && $uuid, 402);
        $record = JSONRepository::latest($group, $uuid);
        if (!$record) {
            return 'site not found.';
        }

        $view = 'v4.widget.'.$type;
        if (!View::exists($view)) {
            return $type . ' widget not Exists';
        }

        return view('v4.widget.'.$type, compact('type', 'group', 'uuid', 'record'));
    }
}
