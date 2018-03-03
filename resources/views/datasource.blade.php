@extends('layouts.default')

@section('content')
<div class="container">
    <ul class="nav nav-pills justify-content-center mt-4" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#datasource" data-toggle="tab" role="tab">資料來源</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#history" data-toggle="tab" role="tab">歷史查詢</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#latest" data-toggle="tab" role="tab">最新紀錄</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#fetch-log" data-toggle="tab" role="tab">資料更新紀錄</a>
        </li>
    </ul>

    <hr>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="datasource" role="tabpanel" aria-labelledby="home-tab">
            <h2>
                資料來源 <span class="badge badge-primary">JSON</span>
                <small class="text-muted">經過轉換為 g0v 站點格式</small>
            </h2>

            <table class="table table-striped ml-4">
                <thead>
                    <tr>
                        <td>站點</td>
                        <td>來源網址</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datasources as $source)
                    <tr>
                        <td>{{ $source->get('group') }}</td>
                        <td>
                            @php $link = route('json', ['json' => $source->get('json')]); @endphp
                            <a href="{{ $link }}">
                                <span class="badge badge-info">Json</span>
                                {{ $link }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h4>必要參數</h4>
            <p class="ml-4">無</p>

            <h4>選用參數</h4>
            <p class="ml-4">無</p>
        </div>

        <div class="tab-pane fade show " id="history" role="tabpanel" aria-labelledby="home-tab">
            <h2>
                歷史查詢
                <small class="text-muted">單一站點歷史紀錄</small>
            </h2>

            <table class="table ml-4">
                <tbody>
                    <tr>
                        <td>URL</td>
                        <td>{{ url('query-history') }}</td>
                    </tr>
                    <tr>
                        <td>Method</td>
                        <td>GET</td>
                    </tr>
                </tbody>
            </table>

            <h4>必要參數</h4>
            <table class="table ml-4">
                <thead>
                    <tr>
                        <td>參數名稱</td>
                        <td>參數型態</td>
                        <td>描述</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>group</td>
                        <td>string</td>
                        <td>site group (ex: lass, lass-4u, ...etc).</td>
                    </tr>
                    <tr>
                        <td>id</td>
                        <td>string</td>
                        <td>device unique key specified in datasource json.</td>
                    </tr>
                    <tr>
                        <td>start</td>
                        <td>timestamp</td>
                        <td>history start time.</td>
                    </tr>
                    <tr>
                        <td>end</td>
                        <td>timestamp</td>
                        <td>history end time.</td>
                    </tr>
                </tbody>
            </table>

            <h4>範例</h4>
            <ul>
                <li>{{ url('query-history?group=lass&id=xxx&start=xxx&end=xxx') }}</li>
            </ul>
        </div>

        <div class="tab-pane fade show " id="latest" role="tabpanel" aria-labelledby="home-tab">
            <h2>
                最新紀錄
                <small class="text-muted">單一站點最新一筆紀錄</small>
            </h2>

            <table class="table ml-4">
                <tbody>
                    <tr>
                        <td>URL</td>
                        <td>{{ url('query-lastest') }}</td>
                    </tr>
                    <tr>
                        <td>Method</td>
                        <td>GET</td>
                    </tr>
                </tbody>
            </table>

            <h4>必要參數</h4>
            <table class="table ml-4">
                <thead>
                    <tr>
                        <td>參數名稱</td>
                        <td>參數型態</td>
                        <td>描述</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>group</td>
                        <td>string</td>
                        <td>site group (ex: lass, lass-4u, ...etc).</td>
                    </tr>
                    <tr>
                        <td>id</td>
                        <td>string</td>
                        <td>device unique key specified in datasource json.</td>
                    </tr>
                </tbody>
            </table>

            <h4>範例</h4>
            <ul>
                <li>{{ url('query-lastest?group=lass&id=xxx') }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection
