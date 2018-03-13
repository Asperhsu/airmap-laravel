@extends('layouts.default')

@section('title', '自造站點持續募集中')

@section('style')
@parent
<style>
    .card-img-top { padding: 1rem; }
</style>
@endsection

@section('content')
<div class="container">
    <h2 class="my-4">自造站點募集</h2>

    <div class="row">
        <div class="col">
            <h5><i class="fas fa-question-circle mr-1"></i>為何需要自造測站</h5>
            <p class="ml-4">
                零時觀測網的任務為彙整官方與非官方的空氣品質觀測資料，測站的數量越多則可使空汙追蹤的解析度提高。
                然而官方的大型測站在地理分布數量與更新頻率上並不足夠，社群自造的低成本物聯網感測器即是這時代的互補解決方案。
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <h5><i class="fas fa-question-circle mr-1"></i>立即加入空汙感測器自造社群的行列動手做</h5>
            <p class="ml-4">
                以下為各社群自造感測器的方案，觀測到的資料皆可即時呈現於g0v零時空汙觀測網。
            </p>
        </div>
    </div>


    <div class="card-deck">
        <div class="card">
            <img class="card-img-top" src="https://i.imgur.com/z11DvN7.png">
            <div class="card-body">
                <p class="card-text">
                    ProbeCube是基於開源硬體的物聯網專案，志在提供大眾可以輕鬆動手自造WiFi連網的空汙感測器方案，目前亦為g0v空汙觀測的支援性專案，推廣測站提高觀測網空汙追蹤能力。
                </p>
            </div>
            <div class="card-footer text-center">
                <div class="btn-group" role="group">
                    <a class="btn btn-sm btn-outline-info" target="_blank" href="https://github.com/Lafudoci/ProbeCube"><i class="fab fa-github"></i> GitHub</a>
                    <a class="btn btn-sm btn-outline-info" target="_blank" href="http://beta.hackfoldr.org/g0vairmap/g0v--rp53Cw7woim">Hackpad</a>
                    <a class="btn btn-sm btn-outline-info" target="_blank" href="https://www.facebook.com/groups/491642281023600/"><i class="fab fa-facebook-square"></i> Facebook</a>
                </div>
            </div>
        </div>

        <div class="card">
            <img class="card-img-top" src="https://i.imgur.com/6XITiIN.jpg">
            <div class="card-body">
                <p class="card-text">
                    LASS是開源公益的環境感測器網路計畫，系統完成度高、門檻低，支援平台眾多，賦予Maker自行客製各種感測能力，所有感測資料皆自由開放以利各種衍生專案與應用。
                </p>
            </div>
            <div class="card-footer text-center">
                <div class="btn-group" role="group">
                    <a class="btn btn-sm btn-outline-info" target="_blank" href="https://github.com/LinkItONEDevGroup/LASS"><i class="fab fa-github"></i> GitHub</a>
                    <a class="btn btn-sm btn-outline-info" target="_blank" href="https://lass.hackpad.tw/">Hackpad</a>
                    <a class="btn btn-sm btn-outline-info" target="_blank" href="https://www.facebook.com/groups/1607718702812067/"><i class="fab fa-facebook-square"></i> Facebook</a>
                </div>
            </div>
        </div>

        <div class="card">
            <img class="card-img-top" src="https://i.imgur.com/d04Edr0.jpg">
            <div class="card-body">
                <p class="card-text text-center">
                    歡迎更多社群方案加入
                </p>
            </div>
        </div>
    </div>

    <div class="card my-5">
        <div class="card-body">
            <h5 class="">
                或者，你已有獨立開發的感測器想要分享即時觀測值給零時觀測網<br/>
                可 <a target="_blank" href="http://goo.gl/forms/Y95gdHCgIr">填寫此表單</a> 與我們聯絡。
            </h5>
        </div>
    </div>
</div>
@endsection
