@extends('layouts.admin')

@section('content')
<div class="container">
    @include('partials.alerts')

    <div class="row justify-content-center mt-3">
        <a href="{{ route('admin.register') }}" class="btn btn-primary btn-sm mr-1"><span class="oi oi-plus"></span> 新增使用者</a>
        <a href="{{ route('admin.chgpassword', [$userId]) }}" class="btn btn-warning btn-sm"><span class="oi oi-pencil"></span> 更改我的密碼</a>
    </div>

    <hr>

    <div class="card-deck">
        @foreach ($users as $user)
        <div class="card">
            <div class="card-header">
                <span class="oi oi-person"></span>
                {{ $user->name }}
            </div>
            <div class="card-body">
                <h6 class="card-subtitle">Email</h6>
                <p class="card-text">{{ $user->email }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.chgpassword', [$user->id]) }}" class="btn btn-warning btn-sm"><span class="oi oi-pencil"></span> 更改密碼</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
