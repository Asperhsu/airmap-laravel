<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class V5Controller extends Controller
{
    public function index(Request $request, string $require = null)
    {
        if (is_null($require)) {
            return $this->responseIndex();
        }

        $path = storage_path('v5/' . $require);
        if (File::exists($path)) {
            return $this->responseForFile($path);
        }

        return about(404);
    }

    public function acceptWarning()
    {
        session()->put('v5-warning', true);

        return redirect('/v5\/');
    }

    protected function responseForFile(string $path)
    {
        $extension = File::extension($path);
        $mime = File::mimeType($path);
        $content = File::get($path);

        if ($extension === 'css') {
            $mime = 'text/css';
        }

        return response($content)->header('Content-Type', $mime);
    }

    protected function responseIndex()
    {
        if (session()->has('v5-warning')) {
            $path = storage_path('v5/index.html');
            return $this->responseForFile($path);
        }

        return view('v5.warning');
    }
}
