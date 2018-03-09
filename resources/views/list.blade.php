@extends('layouts.default')

@section('style')
<style>
    .site-card { font-size: .85em; }
</style>
@endsection

@section('content')
<form method="get">
    <div class="container" style="width: 80%">
        <div class="row mt-4 mb-2">
            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">站名搜尋</div>
                    </div>
                    <input type="text" class="form-control" name="keyword" placeholder="請輸入站台名稱關鍵字">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col text-center">OR</div>
        </div>

        <div class="row my-2">
            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">縣市</div>
                    </div>
                    <select class="form-control" name="country">
                        @foreach ($countries as $code => $name)
                        <option value="{{ $code }}" {{ $country == $code ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">鄉鎮</div>
                    </div>
                    <select class="form-control" name="town">
                        <option value="all">全部</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row mt-3 mb-5">
            <div class="col text-center">
                <button class="btn btn-primary px-5" type="submit">
                    <i class="fas fa-search"></i> 搜尋
                </button>
            </div>
        </div>
    </div>
</form>

@if (count($sites))
<div class="container">

    <div class="card-columns">
        @foreach ($sites as $site)
        <div class="card site-card mb-4">
            <div class="card-header bg-dark text-white">{{ $site->get('SiteName') }}</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-6"><strong>Group</strong></div>
                        <div class="col-sm-6">{{ $site->get('SiteGroup') }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-6"><strong>Maker</strong></div>
                        <div class="col-sm-6">{{ $site->get('Maker') }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-6"><strong>ID</strong></div>
                        <div class="col-sm-6">{{ $site->get('uniqueKey') }}</div>
                    </div>
                </li>
                @foreach ($site->get('Data') as $name => $value)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-6"><strong>{{ $name }}</strong></div>
                        <div class="col-sm-6">{{ $value }}</div>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="card-footer">
                <div class="row">
                    <div class="col">
                        @if ($site->get('Geometry'))
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="geometry">{{ $site->get('Geometry')->get('COUNTYNAME') }} {{ $site->get('Geometry')->get('TOWNNAME') }}</span>
                        @endif
                    </div>

                    <div class="col-sm-auto mr-0">
                        <a href="{{ route('widget.create', [$site->get('SiteGroup'), $site->get('uniqueKey')]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-tachometer-alt"></i> Widget
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endif
@endsection


@push('scripts')
<script>
    var countryTowns = {!! json_encode($towns) !!};
    $("select[name=country]").change(function() {
        var countryCode = $(this).val();
        var towns = countryTowns[countryCode];

        var html = [].concat('<option value="all">全部</option>',
            towns.map(function (town) {
                return '<option value="' + town.code + '">' + town.name + '</option>';
            })
        );
        $("select[name=town]").html(html.join(''));
    }).trigger('change');
</script>
@endpush