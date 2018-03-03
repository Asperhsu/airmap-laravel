@extends('v4.app')

@section('title')
<title>g0v零時空汙觀測網 關於</title>
<link rel='shortcut icon' type='image/x-icon' href='https://i.imgur.com/Gro4juQ.png' />
@endsection

@section('style')
@parent
<style>
    th, td{ border-top:0 !important; }
    th{ width:40%; text-align: right; font-size:1.2em; }
    th.sub{ color:#777; font-size:0.9em; }
    td{ border-left: 3px solid #eee; }
    tr.seprate > td{ border-left: 0; }
</style>
@endsection

@section('body')
    <div class="container">
        <h1 style="text-align: center;">About</h1>

        <table class="table">
            <tr>
                <th>Map</th>
                <td></td>
            </tr>
            <tr>
                <th></th>
                <td><a href="https://maps.google.com.tw">Google Map</a></td>
            </tr>
            <tr>
                <th class="sub">Map Style</th>
                <td><a href="https://snazzymaps.com/">Smazzy Maps</a></td>
            </tr>
            <tr class="seprate">
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th>Layout</th>
                <td></td>
            </tr>
            <tr>
                <th></th>
                <td><a href="http://getbootstrap.com">Bootstrap</a></td>
            </tr>
            <tr>
                <th></th>
                <td><a href="https://github.com/seiyria/bootstrap-slider">Bootstrap Slider</a></td>
            </tr>
            <tr>
                <th></th>
                <td><a href="http://www.bootstrap-switch.org/">Bootstrap Switch</a></td>
            </tr>
            <tr>
                <th class="sub">Using in list page</th>
                <td><a href="http://bootstrap-table.wenzhixin.net.cn/">Bootstrap Table</a></td>
            </tr>
            <tr class="seprate">
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th>JS Library</th>
                <td></td>
            </tr>
            <tr>
                <th></th>
                <td><a href="http://getbootstrap.com">jQuery</a></td>
            </tr>
            <tr>
                <th></th>
                <td><a href="https://vuejs.org/">Vue.js</a></td>
            </tr>
            <tr>
                <th></th>
                <td><a href="https://d3js.org/">d3.js</a></td>
            </tr>
            <tr>
                <th></th>
                <td><a href="http://momentjs.com/">moment.js</a></td>
            </tr>
            <tr class="seprate">
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th>Voronoi Diagram</th>
                <td></td>
            </tr>
            <tr>
                <th></th>
                <td><a href="http://nrl.iis.sinica.edu.tw/LASS/GIS/voronoi/voronoi.html">LASS: Voronoi Diagram of PM2.5 Stations</a></td>
            </tr>
            <tr>
                <th></th>
                <td><a href="http://bl.ocks.org/shimizu/5610671">GoogleMap Voronoi Diagram</a></td>
            </tr>
            <tr>
                <th></th>
                <td><a href="blog.infographics.tw/2015/06/voronoi-with-d3js/">D3.JS 入門系列 － Voronoi Diagram 教學</a></td>
            </tr>
            <tr class="seprate">
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th>Datasource</th>
                <td></td>
            </tr>
            <tr>
                <th class="sub">Hackpad</th>
                <td><a href="http://beta.hackfoldr.org/g0vairmap/g0v--rp53Cw7woim">g0v 零時空汙觀測專案</a></td>
            </tr>
            <tr>
                <th class=""></th>
                <td><a href="https://lass.hackpad.com">LASS Hackpad</a></td>
            </tr>
            <tr>
                <th class=""></th>
                <td><a href="https://lass.hackpad.tw/-AirBox-API-qmV1bVwtalg">研究筆記 - AirBox API整理</a></td>
            </tr>
            <tr class="seprate">
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th class="sub">Facebook</th>
                <td><a href="https://www.facebook.com/groups/1688209651441559/">PM2.5 開放資料：社群協同分析平台</a></td>
            </tr>
            <tr>
                <th class=""></th>
                <td><a href="https://www.facebook.com/groups/1607718702812067/">LASS-開源公益的環境感測器網路</a></td>
            </tr>
            <tr class="seprate">
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th class="sub">API</th>
                <td><a href="https://pm25.lass-net.org/zh_tw/#api">PM2.5 開放資料入口網站</a></td>
            </tr>
            <tr>
                <th class=""></th>
                <td><a href="http://airbox.asuscloud.com/doc/sandbox/">Asus AirBox Sandbox</a></td>
            </tr>
            <tr class="seprate">
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th class="sub">It's all start from here.</th>
                <td>
                    <a href="http://g0vairmap.3203.info/map.html">g0v零時空汙觀測網</a> |
                    <a href="https://github.com/immortalmice/Real-time-Air-Quality-Map">GitHub</a>
                </td>
            </tr>
            <tr class="seprate">
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th class="sub">Emission Sites</th>
                <td><a href="http://nrl.iis.sinica.edu.tw/EmissionMap/voronoi.html">Emission Source Map</a></td>
            </tr>
            <tr class="seprate">
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th class="sub">Satellite Image</th>
                <td><a href="http://opendata.cwb.gov.tw/">交通部中央氣象局-開放資料平臺</a></td>
            </tr>
            <tr class="seprate">
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th class="sub">Device Info</th>
                <td>
                    <p>
                        <h5>Lass Open Data</h5>
                        <a href="https://sites.google.com/site/pm25opendata/open-data">Open Data Index</a>
                    </p>
                    <p>
                        <h5>Airbox</h5>
                        <a href="https://airbox.edimaxcloud.com">Edimax Airbox</a> |
                        <a href="http://airbox.asuscloud.com">Asus Cloud</a>
                    </p>
                </td>
            </tr>
            <tr>
                <th class="sub"></th>
                <td>
                    <img src="https://airbox.edimaxcloud.com/images/logo_airbox.png" height="80px">&nbsp;&nbsp;
                    <img src="https://i.imgur.com/6XITiIN.jpg" height="80px">&nbsp;&nbsp;
                    <img src="https://i.imgur.com/z11DvN7.png" height="80px">&nbsp;&nbsp;
                </td>
            </tr>

            <tr class="seprate">
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th>Author</th>
                <td></td>
            </tr>
            <tr>
                <th></th>
                <td><a href="http://www.asper.tw/">Asper</a></td>
            </tr>
            <tr>
                <th></th>
                <td>asperwon (at) gmail.com</td>
            </tr>
            <tr>
                <th class="sub">Project GitHub</th>
                <td>
                    <a href="https://github.com/Aspertw/airmap-laravel">Aspertw/airmap-laravel</a>
                </td>
            </tr>
        </table>
    </div>
@endsection