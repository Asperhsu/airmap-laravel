@extends('layouts.default')

@section('style')
@parent
<style>
    .header-title { margin: 2rem 0; }
	.widget { padding-bottom: 1em;  }
	.widget .preview { border: 1px solid #333; margin: 20px auto;  }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <div class="header-title">
                <h2>Widget 小工具 (JS 版本)</h2>
                <h5>如果想把特定測站嵌入網頁，您可以從下方預覽小工具與取得HTML碼</h5>
            </div>
        </div>
    </div>

    <div class="row widget">
        <div class="col-4">
            <div class="preview">
                {!! $vues->get('text') !!}
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3>簡約列表</h3>
                </div>
                <div class="card-body">
                    <h5 class="card-title">建議尺寸</h5>
                    <p class="card-text">
                        高度: 230px
                    </p>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Code</h5>
                    <p class="card-text">
                        <pre><code>{{ $vues->get('text') }}<br/>{{ $vueScript }}</code></pre>
                    </p>
                </div>
            </div>
        </div>
    </div>


    <div class="row widget">
        <div class="col-4">
            <div class="preview">
                {!! $vues->get('marker') !!}
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3>醒目圖示</h3>
                </div>
                <div class="card-body">
                    <h5 class="card-title">建議尺寸</h5>
                    <p class="card-text">
                        高度比寬度小10px. Ex: width: 230px => height: 220px
                    </p>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Code</h5>
                    <p class="card-text">
                        <pre><code>{{ $vues->get('marker') }}<br/>{{ $vueScript }}</code></pre>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row widget">
        <div class="col-4">
            <div class="preview">
                {!! $vues->get('thin') !!}
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3>狹窄</h3>
                </div>
                <div class="card-body">
                    <h5 class="card-title">建議尺寸</h5>
                    <p class="card-text">
                        適合寬度不大的版面使用，最低寬度需求 120px
                    </p>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Code</h5>
                    <p class="card-text">
                        <pre><code>{{ $vues->get('thin') }}<br/>{{ $vueScript }}</code></pre>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            如需更多說明，請參考<a href="{{ route('widget.index') }}">小工具說明</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/js/airmap-widget.js"></script>
@endpush