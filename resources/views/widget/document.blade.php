@extends('layouts.default')

@section('style')
@parent
<style>
    .basic li { line-height: 2.5em; }
    .advance pre { background: #eee; border-radius: 5px; padding: .5em; font-family: 'Courier New', Courier, monospace; }
    .advance ul { margin-bottom: 1em; }
</style>
@endsection

@section('content')
<div class="container basic">
    <h2 class="my-4">基礎</h2>

    <div class="row">
        <div class="col">
            <ol class="basic">
                <li>請先至 <a href="{{ route('list') }}">站點列表</a> 搜尋後</li>
                <li>
                    點選
                    <button class="btn btn-sm btn-outline-primary" type="submit">
                        <i class="fas fa-tachometer-alt"></i> Widget
                    </button> 後選擇想要的樣式
                </li>
                <li>
                    於您的網頁加入 <code>Code</code> 中的內容
                </li>
            </ol>
        </div>
    </div>
</div>

<div class="container advance">
    <h2 class="my-4">進階使用</h2>

    <div class="row">
        <div class="col pb-3">
            <h5><strong>widget 元件</strong></h5>
            <hr>
            <div>藉由使用不同的 widget 元件，顯示不同的樣式。下方會使用 <code>widget-*</code> 泛指所有的widget元件</div>
            <div>可用的元件有: </div>
            <dl class="row pl-3">
                <dt class="col-sm-6"><code>{{ '<widget-text></widget-text>' }}</code></dt>
                <dd class="col-sm-6">簡約列表</dd>

                <dt class="col-sm-6"><code>{{ '<widget-marker></widget-marker>' }}</code></dt>
                <dd class="col-sm-6">醒目圖示</dd>

                <dt class="col-sm-6"><code>{{ '<widget-thin></widget-thin>' }}</code></dt>
                <dd class="col-sm-6">狹窄</dd>
            </dl>
        </div>
    </div>

    <div class="row">
        <div class="col pb-3">
            <h5><strong>額外參數</strong></h5>
            <hr>
            <div>每個 widget-* 都可以有不同的參數，如不同的站台等，以下是參數的介紹</div>
            <dl class="row pl-3">
                <dt class="col-sm-3"><code>group</code></dt>
                <dd class="col-sm-9">站點群組：必要參數，此頁面已自動生成</dd>

                <dt class="col-sm-3"><code>uuid</code></dt>
                <dd class="col-sm-9">站點ID：必要參數，此頁面已自動生成</dd>

                <dt class="col-sm-3"><code>name</code></dt>
                <dd class="col-sm-9">站點名稱：選用參數，可自訂站台的顯示名稱</dd>

                <dt class="col-sm-3"><code>measure</code></dt>
                <dd class="col-sm-9">站點名稱：選用參數，可自訂PM 2.5顯示顏色。可用值有: PM2.5, AQI, PM2.5_NASA</dd>
            </dl>
        </div>
    </div>

    <div class="row">
        <div class="col pb-3">
            <h5><strong>單一頁面多個小工具</strong></h5>
            <hr>
            <p>同一頁面只需要引入一次</p>
            <pre><code>{{ '<script src="' . url('js/airmap-widget.js') . '"></script>' }}</code></pre>


            <p>每個 <code>{{ $vueScript }}</code> 可以包含多個 widget-* 的元件。如:</p>
            <pre><code>{{ implode("\r\n", [
                '<div class="airmap-widget">',
                "\t".'<widget-text ...></widget-text>',
                "\t".'<widget-thin ...></widget-thin>',
                "\t".'<widget-marker ...></widget-marker>',
                '</div>',
            ]) }}</code></pre>

            <p>分開放置時，請確定有每個 <code>widget-*</code> 都置放於 <code>{{ e('<div class="airmap-widget"><div>') }}</code> 內。如:</p>
            <pre><code>{{ implode("\r\n", [
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
            ]) }}</code></pre>
        </div>
    </div>
</div>
@endsection