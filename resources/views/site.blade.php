@extends('app')

@section('title')
<title>g0v零時空汙觀測網 站點詳細資料</title>
<link rel='shortcut icon' type='image/x-icon' href='https://i.imgur.com/Gro4juQ.png' />
@endsection

@section('meta')
<meta property="og:title" content="g0v零時空汙觀測網 站點詳細資料">
<meta property="og:description" content="g0v Realtime Air Pollution Mesure Site Detail">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('site') }}">
@endsection

@section('style')
@parent
<link rel="stylesheet" href="{{ asset('css/site.css') }}">
@endsection

@section('script')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.gmap.key') }}"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script>google.charts.load('current', {'packages':['corechart']});</script>
    <script src="{{ asset('js/vendor-common.js') }}"></script>
    <script src="{{ asset('js/site.js') }}"></script>
@endsection

@section('body-attributes', 'data-spy="scroll" data-target="#sidebar" data-offset="30"')
@section('body')
    <div id="sidebar">
        <ul class="nav">
            <li style="border-color: #337AB7"><a href="#gauge">Gauge</a></li>
            <li style="border-color: #F0AD4E"><a href="#locatiion">Location</a></li>
            <li style="border-color: #5CB85C"><a href="#history">History</a></li>
            <li style="border-color: #5BC0DE"><a href="#detail">Detail</a></li>
        </ul>
    </div>

    <div id="container" style="display: none;">
        <div id="navbar">
            <div class="site-info">
                <span class="label label-info site-group">@{{group}}</span>
                <br/>
                <span class="site-name">@{{name}}</span>
            </div>
            <div class="g0v-logo">
                <img src="https://i.imgur.com/IWqy7yh.png" alt="Image" class="hidden-xs">
                <img src="https://i.imgur.com/yCKqhc1.png" alt="Image" class="visible-xs-inline">
                <br/>
                <a href="/" class="btn btn-success" title="Map"><span class="glyphicon glyphicon-map-marker"></span></a>
                <a href="/list" class="btn btn-info" title="List"><span class="glyphicon glyphicon-th-list"></span></a>
            </div>
        </div>

        <div id="content">
            <div id="gauge">
                <div class="body" style="background-color: #337AB7;">
                    <div class="chart-container">
                        <div class="col-sm-4 col-xs-12">
                            <div id="gauge-pm25" class="chart"></div>
                            <div>
                                <span class="bg-info">PM 2.5</span>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div id="gauge-temp" class="chart"></div>
                            <div>
                                <span class="bg-info">Temperature</span>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div id="gauge-humi" class="chart"></div>
                            <div>
                                <span class="bg-info">Humidity</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <hr>
                    <div class="suggestion">
                        <div class="row normal-human">
                            <div class="targetPeople col-xs-12 col-sm-3">
                                <span class="label label-suggestion">一般民眾活動建議</span>
                            </div>
                            <div class="text col-xs-12 col-sm-9">
                                <div data-range-min="0" data-range-max="35" data-color="#5CB85C">正常戶外活動</div>
                                <div data-range-min="36" data-range-max="53" data-color="#F0AD4E">正常戶外活動</div>
                                <div data-range-min="54" data-range-max="70" data-color="#D9534F">任何人如果有不適，如眼痛，咳嗽或喉嚨痛等，應該考慮減少戶外活動</div>
                                <div data-range-min="71" data-range-max="999" data-color="#CE30FF">任何人如果有不適，如眼痛，咳嗽或喉嚨痛等，應減少體力消耗，特別是減少戶外活動</div>
                            </div>
                        </div>

                        <div class="row sensitive-human">
                            <div class="targetPeople col-xs-12 col-sm-3">
                                <span class="label label-suggestion">敏感性族群活動建議</span>
                            </div>
                            <div class="text col-xs-12 col-sm-9">
                                <div data-range-min="0" data-range-max="35" data-color="#5CB85C">正常戶外活動</div>
                                <div data-range-min="36" data-range-max="53" data-color="#F0AD4E"> 	有心臟、呼吸道及心血管疾病的成人與孩童感受到癥狀時，應考慮減少體力消耗，特別是減少戶外活動。</div>
                                <div data-range-min="54" data-range-max="70" data-color="#D9534F">
                                    <ol>
                                        <li>有心臟、呼吸道及心血管疾病的成人與孩童，應減少體力消耗，特別是減少戶外活動</li>
                                        <li>老年人應減少體力消耗</li>
                                        <li>具有氣喘的人可能需增加使用吸入劑的頻率</li>
                                    </ol>
                                </div>
                                <div data-range-min="71" data-range-max="999" data-color="#CE30FF">
                                    <ol>
                                        <li>有心臟、呼吸道及心血管疾病的成人與孩童，以及老年人應避免體力消耗，特別是避免戶外活動</li>
                                        <li>具有氣喘的人可能需增加使用吸入劑的頻率</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="datasource">
                            <span class="label label-default">資料來源</span>
                            <a href="http://taqm.epa.gov.tw/taqm/tw/fpmi.aspx" target="_blank">
                                行政院環保署細懸浮微粒(PM2.5)指標對照表與活動建議
                            </a>
                        </div>
                    </div>
                </div>
                <div class="header row">
                    <div class="title col-sm-6 col-xs-12">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        Lastest Gauge Value
                    </div>

                    <div class="create-at text-right col-sm-6 col-xs-12">
                        <span class="glyphicon glyphicon-time"></span>  
                        <span class="human-timestring">
                            Updated <span class="time"></span> ago.
                        </span>&nbsp;
                        <span class="label label-info utc-timestring">
                            UTC
                        </span>&nbsp;
                        <span class="label label-info local-timestring">
                            Local
                        </span>
                    </div>
                </div>
            </div>

            <div id="locatiion">
                <div class="body" style="background-color: #F0AD4E;" id='map'>
                </div>
                <div class="header">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    Location
                </div>
            </div>

            <div id="history">
                <div class="body" style="background-color: #5CB85C;">
                    <div class="alert alert-danger loading-error" role="alert"></div>

                    <div class="loading">
                        <div class="spinner">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                            <div class="rect4"></div>
                            <div class="rect5"></div>
                        </div>
                    </div>

                    <div id="site-history-chart"></div>
            
                    <div class="chart-control">
                        <span class="btn btn-default active" data-offset-hours="1" lang="zh-tw" title="過去一小時歷史數值">Hourly</span>

                        <span class="btn btn-default" data-offset-hours="24" lang="zh-tw" title="過去一天歷史數值">Daily</span>

                        <span class="btn btn-default" data-offset-hours="168" lang="zh-tw" title="過去一週歷史數值">Weekly</span>

                        <span class="btn btn-default" data-offset-hours="720" lang="zh-tw" title="過去一個月歷史數值">Monthly</span>
                    </div>
                </div>
                <div class="header">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    History Chart
                </div>
            </div>

            <div id="detail">
                <div class="body" style="background-color: #5BC0DE;">
                    <div class="row"></div>
                </div>
                <div class="header">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    Lastest Detail Dataset
                </div>
            </div>

            <div id="guide">
                <div class="header">Guide</div>
                <ul>
                    <li>Every 1 min refresh lastest data.</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="loading" class="loading">
        <div class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
        <div class="msg"></div>
    </div>

    <div id="no-content" class="container block-msg">
        <div class="content">
            <h1>
                <span class="glyphicon glyphicon-question-sign"></span>
                想找甚麼嗎?
            </h1>
            <h3>
                可以先看看我們的 <a href="/">地圖</a> or <a href="/list">站台列表</a>.
            </h3>
            <hr>
            <h1>
                <span class="glyphicon glyphicon-question-sign"></span>
                Oops! What are you looking for?
            </h1>
            <h3>
                Checkout our <a href="/">Map</a> or <a href="/list">List</a>.
            </h3>
        </div>
    </div>

    <div id="error-msg" class="container block-msg">
        <h1>
            <span class="glyphicon glyphicon-warning-sign"></span>
            Oops! Something wrong
        </h1>
        <h3 class='msg'></h3>
        <h5>You can record page url and send to asperwon+airmap@gmail.com.</h5>
    </div>
@endsection