@extends('layouts.default')

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
    <div class="jumbotron text-center" style="width: 60%">
        <h1 class="display-4 text-danger">
            <i class="fas fa-exclamation-circle"></i> <br/>
            本網域 <small>(airmap.g0v.asper.tw)</small> <br/>將於 3/1 停用
        </h1>
        <hr class="my-4">

        <p class="lead">
            <a class="btn btn-primary btn-lg" href="http://airmap.g0v.tw">
                新網域為 http://airmap.g0v.tw
            </a>
        </p>
    </div>
</div>
@endsection