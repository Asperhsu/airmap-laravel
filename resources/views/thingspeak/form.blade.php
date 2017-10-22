@extends('layouts.manager')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card col-6">
        <div class="card-body">
            <h4 class="card-title">
                @if ($thingspeak->id)
                修改設備
                @else
                新增設備
                @endif
            </h4>

            <form method="POST" action="{{ route('thingspeak.store') }}">
                {{ csrf_field() }}

                
            </form>
        </div>
    </div>
</div>
@endsection
