@extends('layouts.default')

@php $weekChinese = ['日', '一', '二', '三', '四', '五', '六']; @endphp

@section('content')
<div class="container">
    @if (!count($calendar))
    <h1 class="mt-5">目前還沒有圖片</h1>
    @endif

    <div class="d-flex flex-column align-items-center mt-2">
        @if ($lastFile)
        <div class="mx-auto">
            <h5 id="date">{{ str_replace('.gif', '', $lastFile) }}</h5>
            <img id="img" src="{{ asset('screenshots/gif/'.$lastFile) }}" class="img-fluid img-thumbnail">
        </div>

        <hr class="w-100">
        @endif


        <div id="carouselExampleControls" class="carousel slide" data-rige="carousel" data-intervel="false">
            <div class="carousel-inner" style="padding: 0 10%;">
                @foreach($calendar as $year => $months)
                    @foreach($months as $month => $rows)
                    <div class="carousel-item {{ $loop->first ? 'active' :'' }}">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title mb-0">
                                    {{ $year }} <span class="badge badge-pill badge-primary">{{ $month }}</span>
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
                    </div>
                    @endforeach
                @endforeach
            </div>
            <a class="carousel-control-prev bg-secondary" href="#carouselExampleControls" role="button" data-slide="prev" style="width: 10%;">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next bg-secondary" href="#carouselExampleControls" role="button" data-slide="next" style="width: 10%;">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
@endsection

@section('style')
<style>
    .month { max-width: 300px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; }
    .month .day { width: 14.2857%; line-height: 2em; text-align: center; }
    .month .day button { width: 80%; padding: 0; }
</style>
@endsection

@push('scripts')
<script>
    $(".month button").click(function () {
        var base = '{{ asset('screenshots/gif') }}';
        var file = $(this).data('file');

        $("#date").text(file.replace('.gif', ''));
        $("#img").attr('src', base + '/' + file);
    });
</script>
@endpush