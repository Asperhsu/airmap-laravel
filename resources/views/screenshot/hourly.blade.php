@extends('layouts.default')

@section('style')
@parent
<style>
    .image-source { background: #eee; border-radius: 5px; padding: .5em; font-family: 'Courier New', Courier, monospace; }
</style>
@endsection

@section('content')
<div class="text-center">
    <img src="{{ asset('screenshots/hourly/latest.png') }}" class="mt-3 img-fluid img-thumbnail" >
</div>

<div class="container text-center mt-3 image-source">
    <div class="row">
        <div class="col">
            <code>{{ e('<img src="' . asset('screenshots/hourly/latest.png') . '">') }}</code>
        </div>
    </div>

    <div class="row">
        <div class="col">每整點更新圖片，您也可以崁入此圖片至您的網站</div>
    </div>
</div>
@endsection