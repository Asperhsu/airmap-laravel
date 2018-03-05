@extends('v4.widget.layout')

@section('style')
@parent
<style>
	.widget { padding-bottom: 1em; border-bottom: 1px solid #eee; }
	.widget .preview { border: 1px solid #333; margin: 20px auto; }
	.widget-marker .preview { width: 230px; }
	.widget-thin .preview { width: 120px; }
</style>
@endsection

@section('body')
<div class="container">
    <div class="jumbotron">
        <h2>Widget 小工具 (JS 版本)</h2>
        <p>如果想把特定測站嵌入網頁，您可以從下方預覽小工具與取得HTML碼</p>
    </div>

    <div class="row widget widget-text">
        <div class="col-sm-4 text-center">
            <div class="preview">
                {!! $vues->get('text') !!}
            </div>
        </div>
        <div class="col-sm-8">
            <h3>簡約列表</h3>

            <span class="label label-primary">建議尺寸</span>
            <pre>高度: 230px</pre>

            <span class="label label-success">Code</span>
            <pre>{{ $vues->get('text') }}<br/>{{ $vueScript }}</pre>
        </div>
    </div>

    <div class="row widget widget-marker">
        <div class="col-sm-4 text-center">
            <div class="preview">
                {!! $vues->get('marker') !!}
            </div>
        </div>
        <div class="col-sm-8">
            <h3>醒目圖示</h3>
            <p>圖示隨著頁面寬度自動調整，高度需與寬度接近1:1比例</p>

            <span class="label label-primary">建議尺寸</span>
            <pre>高度比寬度小10px. Ex: width: 230px => height: 220px</pre>

            <span class="label label-success">Code</span>
            <pre>{{ $vues->get('marker') }}<br/>{{ $vueScript }}</pre>
        </div>

        <div class="row widget widget-thin">
            <div class="col-sm-4 text-center">
                <div class="preview">
                    {!! $vues->get('thin') !!}
                </div>
            </div>
            <div class="col-sm-8">
                <h3>狹窄</h3>
                <p>適合寬度不大的版面使用，最低寬度需求 120px</p>

                <span class="label label-primary">建議尺寸</span>
                <pre>height: 150px</pre>

                <span class="label label-success">Code</span>
                <pre>{{ $vues->get('thin') }}<br/>{{ $vueScript }}</pre>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h3>進階使用</h3>
            <ul>
                <li>
                    <h4>widget 元件</h4>
                    <p>藉由使用不同的 widget 元件，顯示不同的樣式。下方會使用 <code>widget-*</code> 泛指所有的widget元件</p>
                    <p>可用的元件有: </p>
                    <ul>
                        <li><code>{{ '<widget-text></widget-text>' }}</code>: 簡約列表</li>
                        <li><code>{{ '<widget-marker></widget-marker>' }}</code>: 醒目圖示</li>
                        <li><code>{{ '<widget-thin></widget-thin>' }}</code>: 狹窄</li>
                    </ul>
                </li>
                <li>
                    <h4>額外參數</h4>
                    <p>每個 widget-* 都可以有不同的參數，如不同的站台等，以下是參數的介紹</p>
                    <ul>
                        <li>
                            <span class="label label-danger">group</span> 站點群組：
                            必要參數，此頁面已自動生成
                        </li>
                        <li>
                            <span class="label label-danger">uuid</span> 站點ID：
                            必要參數，此頁面已自動生成
                        </li>
                        <li>
                            <span class="label label-success">name</span> 站點名稱：
                            選用參數，可自訂站台的顯示名稱
                        </li>
                        <li>
                            <span class="label label-success">measure</span> 站點名稱：
                            選用參數，可自訂PM 2.5顯示顏色。可用值有: PM2.5, AQI, PM2.5_NASA
                        </li>
                    </ul>
                </li>
                <li>
                    <h4>單一頁面多個小工具</h4>
                    <p>
                        同一頁面只需要引入一次
                        <pre>{{ '<script src="' . url('js/airmap-widget.js') . '"></script>' }}</pre>
                    </p>
                    <p>每個 <code>{{ $vueScript }}</code> 可以包含多個 widget-* 的元件</p>
                    <p>
                        如:
                        <pre>{{ implode("\r\n", [
                            '<div class="airmap-widget">',
                            "\t".'<widget-text ...></widget-text>',
                            "\t".'<widget-thin ...></widget-thin>',
                            "\t".'<widget-marker ...></widget-marker>',
                            '</div>',
                        ]) }}</pre>
                    </p>
                    <p>分開放置時，請確定有每個 <code>widget-*</code> 都置放於 <code>{{ $vueScript }}</code> 內</p>
                    <p>
                        如:
                        <pre>{{ implode("\r\n", [
                            '<div class="airmap-widget">',
                            "\t".'<widget-text ...></widget-text>',
                            '</div>',
                            '',
                            '<div class="airmap-widget">',
                            "\t".'<widget-thin ...></widget-thin>',
                            '</div>',
                            '',
                            '<div class="airmap-widget">',
                            "\t".'<widget-marker ...></widget-marker>',
                            '</div>',
                        ]) }}</pre>
                    </p>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('script')
@parent
<script src="/js/airmap-widget.js"></script>
@endsection