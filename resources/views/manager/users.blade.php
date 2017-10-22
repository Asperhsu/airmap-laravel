@extends('layouts.manager')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-3">
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm mr-1">新增使用者</a>
        <a href="{{ route('manager.chgpassword') }}" class="btn btn-warning btn-sm">更改我的密碼</a>
    </div>

    <hr>

    <div class="card-columns">
        @foreach ($users as $user)
        <div class="card">
            <div class="card-header">
                {{ $user->name }}
            </div>
            <div class="card-body">
                <h6 class="card-subtitle">Email</h6>
                <p class="card-text">{{ $user->email }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
