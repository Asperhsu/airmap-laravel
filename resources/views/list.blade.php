@extends('app')

@section('title')
<title data-lang="pageTitle">g0v零時空汙觀測網站點清單</title>
<link rel='shortcut icon' type='image/x-icon' href='https://i.imgur.com/Gro4juQ.png' />
@endsection

@section('meta')
<meta property="og:title" content="g0v零時空汙觀測網站點清單">
<meta property="og:description" content="Taiwan Air Quality Site List">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('list') }}">
<meta property="og:image" content="https://i.imgur.com/jcxK6Pj.jpg">
@endsection

@section('style')
@parent
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.min.css">
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('script')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.gmap.key') }}"></script>
    <script src="{{ asset('js/vendor-common.js') }}"></script>
    <script src="{{ asset('js/list.js') }}"></script>
@endsection

@section('body')
    <div id="navbar" class="container-fluid">
        <div class="row">
            <div class="col-sm-6 g0v-logo">
                <img src="https://i.imgur.com/IWqy7yh.png" alt="Image">
            </div>
            <div class="col-sm-6 link">
                <a href='/' class="btn btn-success">Map</a>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="filter">
        <div class="well well-sm">
            <div class="title">
                群組過濾
            </div>
            <div class="btn-container">
                <button type="button" class="btn btn-default" data-group="lass">LASS</button>
                <button type="button" class="btn btn-default" data-group="lass-4u">LASS 4U</button>
                <button type="button" class="btn btn-default" data-group="lass-maps">LASS MAPS</button>
                <button type="button" class="btn btn-default" data-group="asus">Asus Airbox</button>
                <button type="button" class="btn btn-default" data-group="edimax">Edimax Airbox</button>
                <!--<button type="button" class="btn btn-default" data-group="epa">EPA</button>-->
                <button type="button" class="btn btn-default" data-group="independent">g0v Independent</button>
                <button type="button" class="btn btn-default" data-group="probecube">g0v Probecube</button>
            </div>
        </div>

        <p class="filterMsg bg-info">
            ※因站點數過多，請先選擇過濾條件後才會顯示站點
        </p>
    </div>

    <div class="container-fluid">
        <div id="loading" class="loading">
            <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
        </div>

        <table id="bsTable" class="bsTable table table-striped" 
                data-search="true" 
                data-toggle="table" data-detail-view="true" 
                data-detail-formatter="bsTable.formatter.detail" 
                >
            <thead>
                <tr>
                    <th data-formatter="bsTable.formatter.sn">#</th>
                    <th data-field="SiteName" data-class="SiteName" data-sortable="true" data-searchable="true" data-formatter="bsTable.formatter.siteName">Name</th>
                    <th data-field="Maker" data-class="Maker" data-sortable="true" data-searchable="true">Maker</th>
                    <th data-field="Data.Dust2_5" data-class="Dust2_5" data-sortable="true">PM 2.5</th>
                    <th data-field="Data.Temperature" data-class="Temperature" data-sortable="true">Temp</th>
                    <th data-field="Data.Humidity" data-class="Humidity" data-sortable="true">RH</th>
                    <th data-field="LatLng" data-class="LatLng" data-formatter="bsTable.formatter.location">Location</th>
                    <th data-class="widget" data-formatter="bsTable.formatter.widget">Widget</th>
                    <th data-field="Analysis.ranking" data-class="ranking" data-sortable="true" data-formatter="bsTable.formatter.ranking">Ranking</th>
                    <th data-field="Analysis.status" data-class="supposeStatus" data-sortable="true" data-formatter="bsTable.formatter.status">Analysis</th>
                    <th data-field="Data.Create_at" data-class="Create_at" data-sortable="true" data-formatter="bsTable.formatter.updateTime">Updated At</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <hr>

    <div class="container-fluid">
        <h4>Tips</h4>
        <div class="bg-info">
            <ul>
                <li>點選 <span class='glyphicon glyphicon-bookmark'></span> 可連結至站點詳細資料頁面</li>
                <li>
                    點選 <span class='glyphicon glyphicon-map-marker'></span> 可連結至地圖顯示站點;
                    滑鼠停留於上會顯示大約地址 (Powered by Google)
                </li>
            </ul>
        </div>
    </div>

    <div class="container-fluid" id="footer">
        <p>Asper &copy; 2017 <a href='/about'>About</a></p>
        <p>
            <a href="https://www.facebook.com/g0vairmap/" target="_blank">Facebook Page</a> 
        </p>
    </div>
@endsection