@extends('v4.app')

@section('title')
<title data-lang="pageTitle">g0v零時空汙觀測網</title>
<link rel='shortcut icon' type='image/x-icon' href='https://i.imgur.com/Gro4juQ.png' />
@endsection

@section('meta')
<meta property="og:title" content="g0v零時空汙觀測網">
<meta property="og:description" content="g0v Realtime Air Pollution Map">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('v4.map') }}">
<meta property="og:image" content="https://i.imgur.com/Tv1rgZO.jpg">
@endsection

@section('style')
@parent
<link rel="stylesheet" href="{{ asset('v4/css/map.css') }}">
@endsection

@section('script')
    @parent
    <script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script>google.charts.load('current', {'packages':['corechart']});</script>
    <script src="{{ asset('v4/js/vendor-common.js') }}"></script>
    <script src="{{ asset('v4/js/vendor-map.js') }}"></script>
    <script src="{{ asset('v4/js/map.js') }}"></script>
@endsection

@section('body')
    @if (isset($msg))
    <style>#container{ height: calc(100% - 2em); top: 2em; }</style>
    <div id="msg"><?=$msg;?></div>
    @endif

    <div id="container">
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

        <div id="siteLogo">
            <a href="http://beta.hackfoldr.org/g0vairmap/g0v--riRTvbB0E5x">
                <img src="https://i.imgur.com/IWqy7yh.png" class="img-responsive" alt="Image">
            </a>
        </div>
        <div id="recruit">
            <a href='/recruit' class="popover right">
                <div class="arrow"></div>
                <div class="popover-content" data-lang="recruit">自造站點募集中</div>
            </a>
        </div>
        <div id="fb-bot" title="Facebook Messenger 機器人">
            <a href="https://m.me/g0vairmap" target="_blank">
                <img src="https://static.xx.fbcdn.net/rsrc.php/v3/yM/r/a_HNTVVyh4W.png" alt="Facebook Messenger Bot" class="img-responsive">
            </a>
        </div>

        <div id="map-container"></div>

        <div id="navigator">
            <button type="button" class="navbar-toggle navigator-toggle" v-on:click="toggleContainer">
                <span v-show="open" class="glyphicon glyphicon-triangle-left"></span>
                <span v-show="!open" class="glyphicon glyphicon-triangle-right"></span>
            </button>

            <div class="header">
                <div class="logo-side">
                    <div class="lang-switch">
                        <input type="checkbox" class="bs-switch" id="languageSwitch" checked="checked" data-size="mini" data-off-text="中文" data-on-text="En" data-on-Color="default" data-off-Color="default">
                    </div>
                </div>

                <div class="map-search">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-search"></span>
                        </span>
                        <input type="text" id="typeahead" class="form-control typeahead" placeholder="Search Map"
                            autocomplete="off">
                    </div>
                </div>
            </div>

            <ul class="list-group">
                <li class="list-group-item" data-name="siteList" >
                    <h4 class="list-group-item-heading">
                        <span data-lang="siteList">站點清單</span>
                        <a href='/list' class="pull-right btn btn-primary btn-xs" target="_blank">
                            &nbsp;<span class="glyphicon glyphicon-list-alt"></span>&nbsp;
                        </a>
                    </h4>
                </li>

                <li class="list-group-item" data-name="siteLayer" >
                    <h4 class="list-group-item-heading" v-on:click="changeActiveItem">
                        <span data-lang="siteFilter">測量站點</span>
                        <input type="checkbox" class="bs-switch layerToggle siteLayer">
                    </h4>
                    <div class="list-group-item-text" v-show="showItemText('siteLayer')" transition="slide">
                        <div class="blockquote">
                            <span class="title" data-lang="group">群組</span>
                            <div class="site-category-allswitch">
                                <button type="button" class="btn btn-xs btn-success" v-on:click="site_selectAllCategory" data-lang="selectAll">Select All</button>
                                <button type="button" class="btn btn-xs btn-defalut" v-on:click="site_deselectAllCategory" data-lang="selectNone">Deselect All</button>
                            </div>
                        </div>
                        <div class="blockquote-content category">
                            <button type="button" class="btn btn-sm btn-site"
                                data-category="@{{cate.name}}"
                                v-for="cate in site.category"
                                v-bind:class="{'btn-default': !cate.active, 'btn-success': cate.active}"
                                v-on:click="site_changeCategory">
                                <div class="count">@{{ cate.cnt }}</div>
                                <div class="name">@{{ cate.name }}</div>
                            </button>
                        </div>

                        <div class="blockquote">
                            <span class="title" data-lang="measureType">量測類別</span>
                        </div>
                        <div class="blockquote-content">
                            <button type="button" class="btn btn-sm btn-site"
                                v-for="measure in site.measure"
                                v-bind:class="{'btn-default': !measure.active, 'btn-primary': measure.active}"
                                v-on:click="site_changeMeasure"
                            >@{{ measure.name }}</button>
                        </div>

                        <div class="blockquote">
                            <span class="title" data-lang="">測站圖示</span>
                        </div>
                        <div class="blockquote-content site-icon-text">
                            <div>
                                <svg width="30" height="30" viewBox="-55 -55 110 110" xmlns="http://www.w3.org/2000/svg" >
                                    <circle r="40" stroke="#4F595D" stroke-width="2" stroke-linecap="butt" stroke-linejoin="miter" fill="#333" />
                                    <text x="0" y="13" fill="#FFFFFF" style="text-anchor: middle; font-weight: bolder; font-size:35px;">@{{ analysis.normal }}</text>
                                </svg>
                                <div class="text">
                                    <span class="">一般站點 (無分析資料)</span>
                                </div>
                                <input type="checkbox" class="pull-right bs-switch statusToggle" data-status="normal">
                            </div>
                            <div>
                                <svg width="30" height="30" viewBox="-20 -20 420 420" xmlns="http://www.w3.org/2000/svg" >
                                    <g stroke="#4F595D" stroke-width="10" fill="#333" >
                                        <path d="M62.513,153.087c-0.009-0.525-0.02-1.049-0.02-1.575c0-50.155,40.659-90.814,90.814-90.814
                                        c43.222,0,79.388,30.196,88.562,70.643c8.555-4.789,18.409-7.531,28.91-7.531c32.766,0,59.328,26.562,59.328,59.328
                                        c0,1.339-0.06,2.664-0.148,3.981c24.325,9.03,41.661,32.444,41.661,59.911c0,35.286-28.605,63.892-63.892,63.892H79.865
                                        C35.757,310.921,0,275.164,0,231.056C0,192.907,26.749,161.011,62.513,153.087z"/>
                                    </g>
                                    <text x="180" y="280" fill="#FFFFFF" style="text-anchor: middle; font-weight: bolder; font-size:140px;">@{{ analysis.shortterm }}</text>
                                </svg>
                                <div class="text">
                                    <span class="">偵測到小型污染源 </span>
                                </div>
                                <input type="checkbox" class="pull-right bs-switch statusToggle" data-status="shortterm-pollution">
                            </div>
                            <div>
                                <svg width="30" height="30" viewBox="-2 -2 44 44" xmlns="http://www.w3.org/2000/svg">
                                    <g stroke="#4F595D" stroke-width="1" fill="#333" >
                                        <path d="M33.609,20.96v12.384c0,1.104-0.896,2-2,2H7.805c-1.104,0-2-0.896-2-2V20.96c0-0.69,0.355-1.332,0.94-1.696l11.901-7.433
                                            c0.648-0.405,1.472-0.405,2.119,0l11.901,7.433C33.253,19.628,33.609,20.269,33.609,20.96z M38.475,15.432L20.768,4.374
                                            c-0.648-0.405-1.471-0.405-2.119,0L0.94,15.432c-0.937,0.585-1.221,1.819-0.637,2.756c0.584,0.938,1.816,1.224,2.756,0.638
                                            L19.707,8.428l16.646,10.396c0.33,0.206,0.695,0.304,1.059,0.304c0.667,0,1.318-0.333,1.697-0.941
                                            C39.695,17.249,39.41,16.017,38.475,15.432z"/>
                                    </g>
                                    <text x="20" y="32" fill="#FFFFFF" style="text-anchor: middle; font-weight: bolder; font-size:14px;">@{{ analysis.indoor }}</text>
                                </svg>
                                <div class="text">
                                    <span>可能放置於室內或設備故障</span>
                                </div>
                                <input type="checkbox" class="pull-right bs-switch statusToggle" data-status="indoor">
                            </div>
                            <div>
                                <svg width="30" height="30" viewBox="-44 -44 600 600" xmlns="http://www.w3.org/2000/svg" >
                                    <path stroke="#4F595D" stroke-width="13" fill="#333" d="M499.669,495.616C406.528,348.416,373.333,159.595,373.333,32c0-28.885-85.781-32-122.667-32C213.781,0,128,3.115,128,32
                                        c0,104.875-15.04,304.555-115.669,463.616c-2.091,3.285-2.219,7.445-0.341,10.859c1.877,3.413,5.461,5.525,9.344,5.525h469.333
                                        c3.883,0,7.467-2.112,9.344-5.525S501.76,498.923,499.669,495.616z M343.403,32.853c-0.747,0.235-1.429,0.469-2.24,0.683
                                        c-2.091,0.597-4.459,1.195-7.061,1.771c-0.491,0.107-0.875,0.213-1.365,0.32c-3.2,0.683-6.784,1.365-10.688,2.005
                                        c-1.003,0.171-2.176,0.32-3.221,0.469c-3.008,0.469-6.187,0.896-9.579,1.323c-1.6,0.192-3.285,0.363-4.971,0.555
                                        c-3.221,0.341-6.592,0.661-10.112,0.96c-1.941,0.149-3.883,0.32-5.909,0.448c-3.797,0.256-7.829,0.469-11.947,0.661
                                        c-1.963,0.085-3.84,0.192-5.867,0.256c-6.272,0.213-12.8,0.341-19.755,0.341c-6.955,0-13.483-0.128-19.755-0.341
                                        c-2.027-0.064-3.904-0.171-5.867-0.256c-4.117-0.192-8.149-0.384-11.947-0.661c-2.027-0.149-3.989-0.299-5.909-0.448
                                        c-3.52-0.299-6.891-0.597-10.112-0.96c-1.685-0.171-3.371-0.363-4.971-0.555c-3.392-0.405-6.571-0.853-9.579-1.323
                                        c-1.045-0.171-2.219-0.32-3.221-0.469c-3.904-0.64-7.488-1.323-10.688-2.005c-0.512-0.107-0.875-0.213-1.365-0.32
                                        c-2.603-0.576-4.992-1.173-7.061-1.771c-0.811-0.235-1.493-0.469-2.24-0.683c-0.981-0.299-1.813-0.597-2.645-0.896
                                        c13.803-4.864,46.037-10.624,95.381-10.624s81.536,5.76,95.339,10.624C345.216,32.256,344.384,32.555,343.403,32.853z"/>
                                    <text x="250" y="480" fill="#FFFFFF" style="text-anchor: middle; font-weight: bolder; font-size:230px;">@{{ analysis.longterm }}</text>
                                </svg>
                                <div class="text">
                                    <span class="">可能接近長時間的固定污染源或設備故障</span>
                                </div>

                                <input type="checkbox" class="pull-right bs-switch statusToggle" data-status="longterm-pollution">
                            </div>

                            <ul class="about">
                                <li>
                                    分析資料由中央研究院資訊科學研究所提供.
                                    <a href="https://sites.google.com/site/pm25opendata/open-data" target="_blank">資料來源</a>
                                </li>
                                <li>
                                    Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a>
                                    from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>

                <li class="list-group-item" data-name="windLayer">
                    <h4 class="list-group-item-heading" v-on:click="changeActiveItem">
                        <span data-lang="windLayer">風力線</span>
                        <input type="checkbox" class="bs-switch layerToggle windLayer">
                    </h4>
                    <div class="list-group-item-text" v-show="showItemText('windLayer')" transition="slide">
                        <div class="loading" v-show="wind.loading">
                            <div class="spinner">
                                <div class="rect1"></div>
                                <div class="rect2"></div>
                                <div class="rect3"></div>
                                <div class="rect4"></div>
                                <div class="rect5"></div>
                            </div>
                        </div>

                        <div class="blockquote-content">
                            <div class="help-block">
                                <div>
                                    <span class="glyphicon glyphicon-alert"></span>
                                    <span data-lang="windResourceAlert">風力線十分消耗資源，容易造成瀏覽器當機，請斟酌使用。</span>
                                </div>
                                <div v-show="wind.dateUpdateTime">
                                    <span class="glyphicon glyphicon-time"></span>
                                    <span data-lang="lastUpdate">資料時間</span>: @{{ wind.dateUpdateTime }}
                                    UTC+8 (5, 11, 17, 23 <span data-lang="halfHourUpdate">半整點更新資料</span>)
                                </div>
                            </div>
                        </div>

                        <div class="blockquote">
                            <span class="title">
                                <span data-lang="windFillOpacity">線條亮度</span> <code>@{{ wind.lineOpacity }}</code>
                            </span>
                            <input type="text" class="wind-lineOpacity" data-provide="slider" data-slider-min="1" data-slider-max="9" data-slider-step="1" data-slider-value="@{{ wind.lineOpacity }}" lang="zh-tw" v-model="wind.lineOpacity">
                        </div>

                        <div class="blockquote">
                            <span class="title">
                                <span data-lang="windMoveSpeed">移動速度</span> <code>@{{ wind_movingSpeedText }}</code>
                            </span>
                            <input type="text" class="wind-movingSpeed" data-provide="slider" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="@{{ wind.movingSpeed }}" data-slider-reversed=true lang="zh-tw" v-model="wind.movingSpeed">
                        </div>
                    </div>
                </li>

                <li class="list-group-item" data-name="mapTool" >
                    <h4 class="list-group-item-heading" v-on:click="changeActiveItem">
                        <span data-lang="mapTool">地圖工具</span>
                    </h4>
                    <div class="list-group-item-text" v-show="showItemText('mapTool')" transition="slide">
                        <div class="blockquote">
                            <span class="title" data-lang="districtChange">區域切換</span>
                        </div>
                        <div class="blockquote-content">
                            <button type="button" class="btn btn-default" data-area="taipei" v-on:click="areaQuickNavi">台北市</button>
                            <button type="button" class="btn btn-default" data-area="taichung" v-on:click="areaQuickNavi">台中市</button>
                            <button type="button" class="btn btn-default" data-area="chiayi" v-on:click="areaQuickNavi">嘉義市</button>
                            <button type="button" class="btn btn-default" data-area="kaohsiung" v-on:click="areaQuickNavi">高雄市</button>
                        </div>

                        <div class="blockquote">
                            <span class="title" data-lang="voronoiDiagram">勢力地圖</span>
                        </div>
                        <div class="blockquote-content">
                            <div class="blockquote">
                                <span class="title" data-lang="display">顯示</span>
                                <input type="checkbox" class="bs-switch siteVoronoi">
                            </div>

                            <div class="blockquote">
                                <span class="title">
                                    <span data-lang="opacity">透明度</span> <code>@{{ site.voronoiLayerOpacity }}</code>
                                </span>
                                <input type="text" data-provide="slider" data-slider-min="1" data-slider-max="10" data-slider-step="1" lang="zh-tw" v-model="site.voronoiLayerOpacity">
                            </div>
                        </div>
                    </div>
                </li>

                <li class="list-group-item" data-name="resourceLayer">
                    <h4 class="list-group-item-heading" v-on:click="changeActiveItem">
                        <span data-lang="resourceLayer">資源圖層</span>
                    </h4>
                    <div class="list-group-item-text" v-show="showItemText('resourceLayer')" transition="slide">
                        <div class="blockquote">
                            <span class="title" data-lang="emissionLayer">固定汙染源</span>
                        </div>
                        <div class="blockquote-content">
                            <div class="blockquote">
                                <span class="title" data-lang="displayEmissionStaton">顯示站點</span>
                                <input type="checkbox" class="bs-switch emissionSites" data-size="small">
                            </div>

                            <div class="blockquote">
                                <span class="title" data-lang="voronoiDiagram">勢力地圖</span>
                                <input type="checkbox" class="bs-switch emissionVoronoiLayer" data-size="small">
                            </div>

                            <div class="blockquote">
                                <span class="title">
                                    <span data-lang="opacity">透明度</span> <code>@{{ emission_opacity }}</code>
                                </span>
                                <input type="text" data-provide="slider" data-slider-min="1" data-slider-max="10" data-slider-step="1" lang="zh-tw" data-slider-value="@{{ emission_opacity }}" v-model="emission_opacity">
                            </div>
                        </div>

                        <div class="blockquote">
                            <span class="title" data-lang="cwbImage">氣象雲圖</span>
                        </div>
                        <div class="blockquote-content">

                            <div class="blockquote">
                                <span class="glyphicon glyphicon-alert"></span>
                                <span data-lang="imageProjectionNotEqual">雲圖與地圖投影法不相同，位置會有誤差。</span>
                            </div>

                            <div class="blockquote">
                                <span class="title" data-lang="display">顯示</span>
                                <input type="checkbox" class="bs-switch satelliteLayer" data-size="small">
                            </div>

                            <div class="blockquote">
                                <span class="title">
                                    <span data-lang="opacity">透明度</span> <code>@{{ satellite_opacity }}</code>
                                </span>
                                <input type="text" data-provide="slider" data-slider-min="1" data-slider-max="9" data-slider-step="1" lang="zh-tw" data-slider-value="@{{ satellite_opacity }}" v-model="satellite_opacity">
                            </div>
                        </div>
                    </div>
                </li>

                <li class="list-group-item" data-name="siteChart">
                    <h4 class="list-group-item-heading" v-on:click="changeActiveItem">
                        <span data-lang="siteChart">測站圖表</span>
                    </h4>
                    <div class="list-group-item-text" v-show="showItemText('siteChart')" transition="slide">
                        <div class="loading"
                                v-bind:style="{display: site.chartLoading ? 'block' : 'none'}">
                            <div class="spinner">
                                <div class="rect1"></div>
                                <div class="rect2"></div>
                                <div class="rect3"></div>
                                <div class="rect4"></div>
                                <div class="rect5"></div>
                            </div>
                        </div>

                        <div v-show="!site.instance">
                            <h5 data-lang="selectSiteFirst" style='text-align: center;'></h5>
                        </div>

                        <div v-show="site.instance">
                            <h5>@{{site.chartTitle}}</h5>

                            <div v-show="!site.chartLoadingError">
                                <div id="site-history-chart" height="300"></div>
                            </div>

                            <div class="site-history-error" v-show="site.chartLoadingError">@{{site.chartLoadingError}}</div>

                            <div class="blockquote">
                                <span class="btn btn-xs" data-lang="lastHourChart" title="過去一小時歷史數值" v-on:click="changeChartInterval('Hourly')" v-bind:class="{'btn-primary': site_chartIntervalActive('Hourly'), 'btn-default': !site_chartIntervalActive('Hourly') }">
                                    <span class="visible-xs-inline visible-sm-inline">H</span>
                                    <span class="visible-md-inline visible-lg-inline">Hourly</span>
                                </span>

                                <span class="btn btn-xs" data-lang="lastDayChart" title="過去一天歷史數值" v-on:click="changeChartInterval('Daily')" v-bind:class="{'btn-primary': site_chartIntervalActive('Daily'), 'btn-default': !site_chartIntervalActive('Daily') }">
                                    <span class="visible-xs-inline visible-sm-inline">D</span>
                                    <span class="visible-md-inline visible-lg-inline">Daily</span>
                                </span>

                                <span class="btn btn-xs" data-lang="lastWeekChart" title="過去一週歷史數值" v-on:click="changeChartInterval('Weekly')" v-bind:class="{'btn-primary': site_chartIntervalActive('Weekly'), 'btn-default': !site_chartIntervalActive('Weekly') }">
                                    <span class="visible-xs-inline visible-sm-inline">W</span>
                                    <span class="visible-md-inline visible-lg-inline">Weekly</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="list-group-item" data-name="externalLink">
                    <h4 class="list-group-item-heading" v-on:click="changeActiveItem">
                        <span data-lang="externalLink">資源連結</span>
                    </h4>
                    <div class="list-group-item-text" v-show="showItemText('externalLink')" transition="slide">
                        <fieldset>
                            <legend><a href='http://nrl.iis.sinica.edu.tw/LASS/AirBox/' target='_blank'>Airbox 上線狀態</a></legend>
                            <ul>
                                <li><a href='http://nrl.iis.sinica.edu.tw/LASS/AirBox/detail.php?city=lass' target='_blank'>LASS上線狀態</a></li>
                                <li><a href='http://nrl.iis.sinica.edu.tw/LASS/AirBox/detail.php?city=taipei' target='_blank'>台北國小上線狀態</a></li>
                                <li><a href='http://nrl.iis.sinica.edu.tw/LASS/AirBox/detail.php?city=newtaipei' target='_blank'>新北國小上線狀態</a></li>
                                <li><a href='http://nrl.iis.sinica.edu.tw/LASS/AirBox/detail.php?city=taichung' target='_blank'>台中國小上線狀態</a></li>
                            </ul>
                        </fieldset>

                        <fieldset>
                            <legend><a href='http://nrl.iis.sinica.edu.tw/LASS/AirBox/' target='_blank'>相關地圖</a></legend>

                            <ul>
                                <li><a href='http://nrl.iis.sinica.edu.tw/LASS/GIS/IDW/' target='_blank'>Inverse Distance Weighting Diagram</a></li>
                            </ul>
                        </fieldset>
                    </div>
                </li>

                <li id="footer">
                    <div class="text-center" style="color: #d52f72;">
                        <h4>
                            <span class="glyphicon glyphicon-heart"></span>
                            特別致謝
                            <span class="glyphicon glyphicon-heart"></span>
                        </h4>
                        <img src="https://i.imgur.com/z11DvN7.png" width="100px" />
                        <p>本站硬體由 ProbeCube 團隊熱心支援</p>
                    </div>
                    <hr>
                    <p class="disclaimer" data-lang="disclaimer">
                        本零時空汙觀測網僅彙整公開資料提供視覺化參考，並不對資料數據提供保證，實際測值以各資料來源為準。
                    </p>
                    <p class="datasource">
                        <a href="http://lass-net.org/" target="_blank">
                            <img src="https://i.imgur.com/6XITiIN.jpg">
                        </a>
                        <a href="https://github.com/Lafudoci/ProbeCube" target="_blank">
                            <img src="https://i.imgur.com/z11DvN7.png">
                        </a>
                        <a href="https://airbox.edimaxcloud.com"  arget="_blank" title="Edimax Airbox">
                            <img src="https://airbox.edimaxcloud.com/images/logo_airbox.png">
                        </a>
                        <a href="http://airbox.asuscloud.com/"  arget="_blank" title="Asus Cloud">
                            <img src="https://i.imgur.com/bUFhdYA.png">
                        </a>
                    </p>
                    <p class="about">Asper &copy; 2017 &nbsp;&nbsp;<a href="/about">About</a></p>
                    <p class="link">
                        <a href="https://www.facebook.com/g0vairmap/" target="_blank">Facebook Page</a>
                    </p>
                </li>
            </ul>
        </div>

        <div id="indicatorLevel"></div>

        <div id="info-on-map" data-lang="visibleSiteCount" title="Visible site count"></div>
    </div>

    <div id="browser-no-support" class="container block-msg" style="display: none;">
        <div class="content">
            <h1>
                <span class="glyphicon glyphicon-remove-sign"></span>
                這裡不支援IE或是Edge喔~
            </h1>
            <h3>
                請用Firefox或是Chrome瀏覽
            </h3>
            <hr>
            <h1>
                <span class="glyphicon glyphicon-remove-sign"></span>
                We don't support IE or Edge.
            </h1>
            <h3>
                Try Firefox or Chrome.
            </h3>
        </div>
    </div>
@endsection