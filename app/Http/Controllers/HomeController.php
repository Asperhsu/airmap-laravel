<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function map()
    {
        return view('map');
    }

    public function list()
    {
        return view('list');
    }

    public function site()
    {
        return view('site');
    }

    public function recruit()
    {
        return view('recruit');
    }
    
    public function datasource()
    {
        $datasources = collect([
            collect(['group' => 'Single', 'json' => 'airmap.json']),
            collect(['group' => 'LASS', 'json' => 'lass.json']),
            collect(['group' => 'LASS 4U', 'json' => 'lass-4u.json']),
            collect(['group' => 'LASS MAPS', 'json' => 'lass-maps.json']),
            collect(['group' => 'LASS EEVEE', 'json' => 'lass-eevee.json']),
            collect(['group' => 'Asus Airbox', 'json' => 'asus-airbox.json']),
            collect(['group' => 'ProbeCube', 'json' => 'probecube.json']),
            collect(['group' => 'Independent', 'json' => 'independent.json']),
        ]);

        $fetchLogs = collect([
            collect(['group' => 'LASS', 'url' => route('fetchlog', ['group' => 'lass'])]),
            collect(['group' => 'LASS 4U', 'url' => route('fetchlog', ['group' => 'lass-4u'])]),
            collect(['group' => 'LASS MAPS', 'url' => route('fetchlog', ['group' => 'lass-maps'])]),
            collect(['group' => 'Edimax Airbox', 'url' => route('fetchlog', ['group' => 'edimax-airbox'])]),
        ]);

        return view('home', compact('datasources', 'fetchLogs'));
    }
}
