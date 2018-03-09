@extends('layouts.app')

@section('navbar')
@include('partials.navbar-default')
@endsection

@section('footer')
<div class="announcement">
    <div class="text-center">本零時空汙觀測網僅彙整公開資料提供視覺化參考，並不對資料數據提供保證，實際測值以各資料來源為準。</div>
    <div class="text-center">
        本網站由 <a target="_blank" href="https://g0v.tw">g0v</a> 零時空汙觀測專案專案小組開發維護，專案詳情請見
        <a target="_blank" href="http://beta.hackfoldr.org/g0vairmap">hackholdr</a>
    </div>
</div>
@endsection