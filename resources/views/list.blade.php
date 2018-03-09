@extends('layouts.default')

@section('style')
<style>
    .records-table { font-size: .85em; }
    .records-table.table td {
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<form method="get">
    <div class="container">
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
<div class="container-fluid">
    <table class="table table-hover records-table">
        <thead class="thead-light">
            <tr>
                <th scope="col">SiteName</th>
                <th scope="col">Group</th>
                <th scope="col">Data</th>
                <th scope="col">Position</th>
                <th scope="col">Func.</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sites as $site)
            <tr>
                <td>{{ $site->get('SiteName') }}</td>
                <td>
                    <div class="row">
                        <div class="col-sm-6 text-md-right">
                            <span class="badge badge-pill badge-light">Group</span>
                        </div>
                        <div class="col-sm-6 text-md-left text-sm-right">{{ $site->get('SiteGroup') }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 text-md-right">
                            <span class="badge badge-pill badge-light">Maker</span>
                        </div>
                        <div class="col-sm-6 text-md-left text-sm-right">{{ $site->get('Maker') }}</spadivn>
                    </div>
                </td>
                <td>
                    @foreach ($site->get('Data')->except('Create_at') as $name => $value)
                    <div class="row">
                        <div class="col-sm-6 text-md-right">
                            <span class="badge badge-pill badge-light">{{ $name }}</span>
                        </div>
                        <div class="col-sm-6 text-md-left text-sm-right">{{ $value }}</div>
                    </div>
                    @endforeach
                </td>
                <td>
                    @foreach ($site->get('LatLng') as $name => $value)
                    <div class="row">
                        <div class="col-sm-4 text-md-right">
                            <span class="badge badge-pill badge-light">{{ $name }}</span>
                        </div>
                        <div class="col-sm-8 text-md-left text-sm-right">{{ $value }}</div>
                    </div>
                    @endforeach

                    @if ($site->get('Geometry'))
                    <div class="row">
                        <div class="col-sm-4 text-md-right">
                            <span class="badge badge-pill badge-light">鄉鎮</span>
                        </div>
                        <div class="col-sm-8 text-md-left text-sm-right">
                            {{ $site->get('Geometry')->get('COUNTYNAME') }} {{ $site->get('Geometry')->get('TOWNNAME') }}
                        </div>
                    </div>
                    @endif
                </td>
                <td class="text-center">
                    <a href="{{ route('widget.create', [$site->get('SiteGroup'), $site->get('uniqueKey')]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-tachometer-alt"></i> Widget
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
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