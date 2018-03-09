<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Repository\JSONRepository;

class HomeController extends Controller
{
    public function index(Request $request, string $latlng = null)
    {
        $version = $latlng ? 'v4' : 'v5';
        $redirectTo = $latlng ? route('v4.map').$latlng : url('v5');

        return view('home', compact('version', 'redirectTo'));
    }

    public function recruit()
    {
        return view('recruit');
    }

    public function list(Request $request)
    {
        $countries = $towns = $sites = [];
        $country = $request->get('country');
        $town = $request->get('town');
        $keyword = $request->get('keyword');

        $geometrySrv = resolve('App\Service\Geometry');
        ['countries' => $countries, 'towns' => $towns] = $geometrySrv->countryTowns();

        if ($keyword) {
            $sites = JSONRepository::groups()->filter(function ($record) use ($keyword) {
                return strpos(strtolower($record->get('SiteName')), strtolower($keyword)) !== false;
            });
        }

        if (!$keyword && $country && $town) {
            $sites = JSONRepository::groups()->filter(function ($record) use ($country, $town) {
                return $record->get('Geometry')
                    && ($record->get('Geometry')->get('COUNTYCODE') === $country)
                    && ($town == 'all' ? true : $record->get('Geometry')->get('TOWNCODE') === $town);
            });
        }

        return view('list', compact('countries', 'towns', 'sites', 'country', 'town', 'keyword'));
    }

    public function datasource()
    {
        $datasources = collect([
            collect(['group' => 'All-in-one', 'json' => 'airmap.json']),
            collect(['group' => 'LASS', 'json' => 'lass.json']),
            collect(['group' => 'LASS 4U', 'json' => 'lass-4u.json']),
            collect(['group' => 'LASS MAPS', 'json' => 'lass-maps.json']),
            // collect(['group' => 'LASS EEVEE', 'json' => 'lass-eevee.json']),
            // collect(['group' => 'Asus Airbox', 'json' => 'asus-airbox.json']),
            collect(['group' => 'ProbeCube', 'json' => 'probecube.json']),
            collect(['group' => 'Independent', 'json' => 'independent.json']),
            collect(['group' => 'EPA', 'json' => 'epa.json']),
            collect(['group' => 'Airq', 'json' => 'airq.json']),
        ]);

        return view('datasource', compact('datasources'));
    }

    public function dialyGif()
    {
        $files = collect(Storage::disk('screenshots')->files())->filter(function ($file) {
            return ends_with($file, '.gif');
        })->toArray();
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
