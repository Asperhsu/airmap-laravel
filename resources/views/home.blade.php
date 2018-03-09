@extends('layouts.default')

@push('scripts')
<script>
    var seconds = 10;
    var passSeconds = 0;
    setInterval(function () {
        passSeconds++;
        $("#countdown").text(seconds - passSeconds);
        if (passSeconds >= seconds) {
            location.href = '{{ $redirectTo }}';
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
    <h1>v4 版本將於 2018/12/31 停止服務</h1>

    @if ($version === 'v5')
    <h2><span id="countdown"></span> 秒後將跳轉到 v5 版本</h2>
    @endif

    @if ($version === 'v4')
    <h2><span id="countdown"></span> 秒後將跳轉到 v4 版本</h2>
    @endif
</div>
@endsection