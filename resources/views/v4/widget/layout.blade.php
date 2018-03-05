@extends('v4.app')

@section('title')
<title data-lang="pageTitle">g0v零時空汙觀測網 :: 小工具</title>
<link rel='shortcut icon' type='image/x-icon' href='https://i.imgur.com/Gro4juQ.png' />
@endsection

@section('meta')
<meta property="og:title" content="g0v零時空汙觀測網 :: 小工具">
<meta property="og:description" content="Taiwan Air Quality Site :: Widget">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('widget.create', [$group, $uuid]) }}">
<meta property="og:image" content="https://i.imgur.com/jcxK6Pj.jpg">
@endsection

@section('script')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="{{ asset('v4/js/vendor-common.js') }}"></script>
    <script src="{{ asset('v4/js/widget.js') }}"></script>
@endsection

@if (isset($type) && isset($record))
@section('body-attributes')
data-widget-type="{{ $type }}" data-site='{!! $record->toJson() !!}'
@endsection
@endif
