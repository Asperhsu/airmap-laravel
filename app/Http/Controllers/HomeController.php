<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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

    public function about()
    {
        return view('about');
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

        return view('datasource', compact('datasources', 'fetchLogs'));
    }

    public function dialyGif()
    {
        $files = Storage::disk('screenshots')->files();
        $lastFile = last($files);

        $fileCalendar = $calendar = [];
        foreach ($files as $file) {
            list($year, $month, $day) = explode('-', $file);
            $fileCalendar[(int) $year][(int) $month][(int) $day] = true;
        }

        // genreate calendar
        foreach ($fileCalendar as $year => $months) {
            foreach ($months as $month => $days) {
                $dt = Carbon::createFromFormat('Y-m', $year.'-'.$month, 'Asia/Taipei');
                $rows = $row = [];

                for ($i=1; $i<=$dt->daysInMonth; $i++) {
                    $day = $dt->copy()->day($i);
                    $weekNo = $day->dayOfWeek;
                    $filename = $day->format('Y-m-d').'.gif';
                    $row[$weekNo] = [
                        'day' => $i,
                        'file' => in_array($filename, $files) ? $filename : null,
                    ];

                    if ($weekNo == Carbon::SATURDAY) {
                        $rows[] = $row;
                        $row = [];
                    }
                }
                $rows[] = $row;

                $calendar[$year][$month] = $rows;
            }
        }

        return view('dialy-gif', compact('calendar', 'lastFile'));
    }
}
