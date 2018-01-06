<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Models\Thingspeak;
use App\Http\Controllers\Controller;

class DeviceController extends Controller
{
    public function probecube(Request $request)
    {
        $title = 'Probecube';

        $items = Thingspeak::probecube()->get();

        return view('admin.devices', compact('title', 'items'));
    }

    public function independent(Request $request)
    {
        $title = 'Independent';

        $items = Thingspeak::independent()->get();

        return view('admin.devices', compact('title', 'items'));
    }
}
