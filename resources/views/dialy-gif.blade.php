@extends('layouts.default')

@section('content')

<div class="container">
    @if (!count($calendar))
    <h1 class="mt-5">目前還沒有圖片</h1>
    @endif

    <div class="row mt-5">
        <div class="col-md-auto mx-auto">
            @foreach($calendar as $year => $months)
            <h1>{{ $year }}</h1>
                @foreach($months as $month => $rows)
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">
                            <span class="badge badge-pill badge-primary">{{ $month }}</span>
                        </h3>

                        <div class="calendar">
                            @foreach($rows as $row)
                                @for ($i=0; $i<=6; $i++)
                                <div class="day">
                                    @if (isset($row[$i]))
                                        @if ($row[$i]['file'])
                                        <button data-file="{{ $row[$i]['file'] }}" type="button" class="btn btn-outline-primary">{{ $row[$i]['day'] }}</button>
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
        <div class="col-md-auto mx-auto">
            <h5 id="date">{{ str_replace('.gif', '', $lastFile) }}</h5>
            <img id="img" src="{{ asset('screenshots/'.$lastFile) }}" class="rounded mx-auto d-block">
        </div>
        @endif
    </div>
</div>
@endsection

@section('style')
<style>
    .calendar { max-width: 400px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; }
    .calendar .day { width: 14.2857%; line-height: 3em; text-align: center; }
</style>
@endsection

@push('scripts')
<script>
    $(".calendar button").click(function () {
        var base = '{{ asset('screenshots') }}';
        var file = $(this).data('file');

        $("#date").text(file.replace('.gif', ''));
        $("#img").attr('src', base + '/' + file);
    });
</script>
@endpush