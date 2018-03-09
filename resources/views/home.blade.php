@extends('layouts.default')

@push('scripts')
<script>
    var seconds = 20;
    var passSeconds = 0;
    setInterval(function () {
        passSeconds++;
        $("#countdown").text(seconds - passSeconds);
        if (passSeconds >= seconds) {
            // location.href = '{{ $redirectTo }}';
        }
    }, 1000);
</script>
@endpush

@section('style')
<style>
    .center {
        height: 70vh;
        display: flex;
        align-items: center;
        /* flex-wrap: wrap; */
        flex-direction: column;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="center">
    <div class="jumbotron" style="width: 60%">
        <h1 class="display-4"><i class="fas fa-child"></i> 我們改版了</h1>
        <p class="lead">v4 版本將於 2018/12/31 停止服務 <i class="fas fa-calendar-times"></i></p>

        <hr class="my-4">

        <p><i class="fas fa-stopwatch"></i> <span id="countdown"></span> 秒後將跳轉到 {{ $version }} 版本</p>
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="{{ $redirectTo }}">或點選此處前往 <i class="fas fa-external-link-alt"></i></a>
        </p>
    </div>
</div>
@endsection