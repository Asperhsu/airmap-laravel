@extends('layouts.default')

@php $weekChinese = ['日', '一', '二', '三', '四', '五', '六']; @endphp

@section('content')
<div class="container">
    @if (!count($calendar))
    <h1 class="mt-5">目前還沒有圖片</h1>
    @endif

    <div class="row mt-5">
        <div class="col-sm-auto">
            @foreach($calendar as $year => $months)
            <h1>{{ $year }}</h1>
                @foreach($months as $month => $rows)
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title mb-0">
                            <span class="badge badge-pill badge-primary">{{ $month }}</span>
                        </h3>

                        <div class="month">
                            @for ($i=0; $i<=6; $i++)
                            <div class="day">{{ $weekChinese[$i] }}</div>
                            @endfor

                            @foreach($rows as $row)
                                @for ($i=0; $i<=6; $i++)
                                <div class="day">
                                    @if (isset($row[$i]))
                                        @if ($row[$i]['file'])
                                        <button data-file="{{ $row[$i]['file'] }}" type="button" class="btn btn-sm btn-outline-primary">{{ $row[$i]['day'] }}</button>
                                        @else
                                        <span>{{ $row[$i]['day'] }}</span>
                                        @endif
                                    @endif
                                </div>
                                @endfor
                            @endforeach
                        </div>

                    </div>
                </div>
                @endforeach
            @endforeach
        </div>

        @if ($lastFile)
        <div class="col-sm-auto mx-auto">
            <h5 id="date">{{ str_replace('.gif', '', $lastFile) }}</h5>
            <img id="img" src="{{ asset('screenshots/gif/'.$lastFile) }}" class="rounded mx-auto d-block">
        </div>
        @endif
    </div>
</div>
@endsection

@section('style')
<style>
    .month { max-width: 300px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; }
    .month .day { width: 14.2857%; line-height: 2.5em; text-align: center; }
    .month .day button { width: 80%; }
</style>
@endsection

@push('scripts')
<script>
    $(".calendar button").click(function () {
        var base = '{{ asset('screenshots/gif') }}';
        var file = $(this).data('file');

        $("#date").text(file.replace('.gif', ''));
        $("#img").attr('src', base + '/' + file);
    });
</script>
@endpush