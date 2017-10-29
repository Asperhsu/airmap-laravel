<?php

namespace App\Http\Controllers;

use App\Service\IconMaker;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function makeIcon(Request $request, float $pm25)
    {
        $color = IconMaker::color($pm25);
        $img   = IconMaker::make($color, $pm25);

        return response($img)->header('Content-Type', 'image/png');
    }
}
