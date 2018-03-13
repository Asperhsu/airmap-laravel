<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class V4Controller extends Controller
{
    public $msg = '此 v4 版本將於 2018/12/31 停止服務';

    public function map()
    {
        return view('v4.map')->with('msg', $this->msg);
    }

    public function list()
    {
        return view('v4.list')->with('msg', $this->msg);
    }

    public function site()
    {
        return view('v4.site')->with('msg', $this->msg);
    }
}
