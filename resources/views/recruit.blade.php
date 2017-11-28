@extends('app')

@section('title')
<title>g0v零時空汙觀測網 自造站點持續募集中</title>
<link rel='shortcut icon' type='image/x-icon' href='https://i.imgur.com/Gro4juQ.png' />
@endsection

@section('meta')
<meta property="og:title" content="g0v零時空汙觀測網 自造站點募集中">
<meta property="og:description" content="g0v Realtime Air Pollution Mesure Site Recruit">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('recruit') }}">
<meta property="og:image" content="https://i.imgur.com/GtVvd4P.png">
@endsection

@section('style')
@parent
<style>
    th, td{ border-top:0 !important; }
    th{ width:30%; text-align: right; font-size:1.2em; }
    th.sub{ color:#777; font-size:0.9em; }
    td{ border-left: 3px solid #eee; }
    tr.seprate > td{ border-left: 0; }
</style>
@endsection

@section('body')
    <div class="container">
        <div class="row well" style="text-align: center;">
            <img src="https://i.imgur.com/IWqy7yh.png" width="500px" alt="Image">
            <h1>自造站點募集</h1>
        </div>
        

        <div class='row col-xs-12' style="margin-top: 30px;">
            <h3>
                <span class="glyphicon glyphicon-question-sign"></span>
                為何需要自造測站
            </h3>
            <h5 style="padding-left:30px;">零時觀測網的任務為彙整官方與非官方的空氣品質觀測資料，測站的數量越多則可使空汙追蹤的解析度提高。
                然而官方的大型測站在地理分布數量與更新頻率上並不足夠，社群自造的低成本物聯網感測器即是這時代的互補解決方案。
            </h5>
        </div>

        <div class='row col-xs-12'>					
            <h3>
                <span class="glyphicon glyphicon-map-marker"></span>
                立即加入空汙感測器自造社群的行列動手做:
            </h3>
            <h5 style="padding-left:30px;">
                以下為各社群自造感測器的方案，觀測到的資料皆可即時呈現於g0v零時空汙觀測網。
            </h5>

            <div class='row'>
                <div class='col-xs-12 col-md-4'>
                    <div class="vertical-center" width="100%">
                        <img class="center-block" src="https://i.imgur.com/z11DvN7.png" style="max-width: 100%">
                    </div>
                    <div class='col-xs-12'>
                        <p class="text-center">ProbeCube是基於開源硬體的物聯網專案，志在提供大眾可以輕鬆動手自造WiFi連網的空汙感測器方案，目前亦為g0v空汙觀測的支援性專案，推廣測站提高觀測網空汙追蹤能力。</p>
                        <p class="text-center">( 
                            <a target="_blank" href="https://github.com/Lafudoci/ProbeCube">GitHub原始碼</a> | 
                            <a target="_blank" href="http://beta.hackfoldr.org/g0vairmap/g0v--rp53Cw7woim"> Hackpad共筆</a> | 
                            <a target="_blank" href="https://www.facebook.com/groups/491642281023600/"> FB社群討論</a> )</p>
                    </div>
                </div>

                <div class='col-xs-12 col-md-4'>
                    <div class="vertical-center" width="100%">
                        <img class="center-block" src="https://i.imgur.com/6XITiIN.jpg" style="max-width: 100%">
                    </div>
                    <div class='col-xs-12'>
                        <p class="text-center">LASS是開源公益的環境感測器網路計畫，系統完成度高、門檻低，支援平台眾多，賦予Maker自行客製各種感測能力，所有感測資料皆自由開放以利各種衍生專案與應用。</p>
                        <p class="text-center">( 
                            <a target="_blank" href="https://github.com/LinkItONEDevGroup/LASS">GitHub原始碼</a> | 
                            <a target="_blank" href="https://lass.hackpad.tw/"> Hackpad共筆</a> | 
                            <a target="_blank" href="https://www.facebook.com/groups/1607718702812067/"> FB社群討論</a> )</p>
                    </div>
                </div>

                <div class='col-xs-12 col-md-4'>
                    <div class="vertical-center" width="100%">
                        <img class="center-block" src="https://i.imgur.com/d04Edr0.png" style="max-width: 100%">
                    </div>
                    <div class='col-xs-12'>
                        <p class="text-center">歡迎更多社群方案加入!</p>
                    </div>
                </div>
            </div>
        </div>
        <div class='row col-xs-12'>
            <div class='col-md-10 col-md-offset-1' style="padding: 20px 60px 0px 60px;">
                <h3><strong>或者，你已有獨立開發的感測器想要分享即時觀測值給零時觀測網可
                <a target="_blank" href="http://goo.gl/forms/Y95gdHCgIr">填寫此表單</a> 與我們聯絡。</strong></h3>
            </div>
        </div>

        <div id="footer" class="col-xs-12 center-block" style="margin-top: 40px">
            <p class="text-center">本零時空汙觀測網僅彙整公開資料提供視覺化參考，並不對資料數據提供保證，實際測值以各資料來源為準。</p>
            <p class="text-center">本網站由<a target="_blank" href="https://g0v.tw">g0v</a>零時空汙觀測專案專案小組開發維護，專案詳情請見<a target="_blank" href="http://beta.hackfoldr.org/g0vairmap">hackholdr</a>
        </div>
    </div>
@endsection
