@extends('widget.layout')

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
        <h2>Widget 小工具(測試中</h2>
        <p>如果想把特定測站嵌入網頁，您可以從下方預覽小工具與取得HTML碼</p>
    </div>

    <div class="row widget widget-text">
        <div class="col-sm-4 text-center">
            <div class="preview">
                {!! $iframes->get('text') !!}
            </div>
        </div>
        <div class="col-sm-8">
            <h3>簡約列表</h3>

            <span class="label label-primary">建議尺寸</span>
            <pre>高度: 230px</pre>

            <span class="label label-success">Code</span>
            <pre>{{ $iframes->get('text') }}</pre>
        </div>
    </div>

    <div class="row widget widget-marker">
        <div class="col-sm-4 text-center">
            <div class="preview">
                {!! $iframes->get('marker') !!}
            </div>
        </div>
        <div class="col-sm-8">
            <h3>醒目圖示</h3>
            <p>圖示隨著頁面寬度自動調整，高度需與寬度接近1:1比例</p>

            <span class="label label-primary">建議尺寸</span>
            <pre>高度比寬度小10px. Ex: width: 230px => height: 220px</pre>

            <span class="label label-success">Code</span>
            <pre>{{ $iframes->get('marker') }}</pre>
        </div>
    </div>

    <div class="row widget widget-thin">
        <div class="col-sm-4 text-center">
            <div class="preview">
                {!! $iframes->get('thin') !!}
            </div>
        </div>
        <div class="col-sm-8">
            <h3>狹窄</h3>
            <p>適合寬度不大的版面使用，最低寬度需求 120px</p>

            <span class="label label-primary">建議尺寸</span>
            <pre>height: 150px</pre>

            <span class="label label-success">Code</span>
            <pre>{{ $iframes->get('thin') }}</pre>
        </div>
    </div>
</div>
@endsection