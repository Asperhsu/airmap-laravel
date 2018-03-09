@extends('widget.layouts.base')

@section('style')
    @parent
    <style>
        .header{ padding: 8px 5px 0; min-height: 32px; }
        .header > div { width: 49%; display: inline-block; margin-right: -4px; }

        .marker {
            margin: -10px auto 0;
            width:180px; height: 180px;
            width: 80vw; height: 80vw;
            border-radius: 50%; border: 2px solid #4F595D;
            padding-top: 35px;
            padding-top: 15vw;
            text-align: center;
        }
        .marker .name{
            margin: 0;
            font-size: 6vw;
        }
        .marker .value{
            line-height: 1;
            font-size: 90px;
            font-size: 40vw;
        }

        .footer { padding: 8px 5px 0; margin-top: -15px; }
        .footer > div { width: 49%; display: inline-block; margin-right: -4px; }
        .footer img{ height:25px; }
    </style>
@endsection

@section('content')
    <div class="header">
        @if ($record->get('Data')->get('Temperature'))
        <div title="溫度">
            <span class="value">{{ $record->get('Data')->get('Temperature') }}</span>
            <span class="unit">℃</span>
        </div>
        @endif

        @if ($record->get('Data')->get('Humidity'))
        <div class="text-right" title="濕度">
            <span class="value">{{ $record->get('Data')->get('Humidity') }}</span>
            <span class="unit">%</span>
        </div>
        @endif
    </div>

    <div class="marker pm25color" style="background-color: {{ $record->get('color') }};">
        <p class="name">{{ $record->get('SiteName') }}</p>
        @if ($record->get('Data')->get('Dust2_5'))
        <p class="value">{{ $record->get('Data')->get('Dust2_5') }}</p>
        @endif
    </div>

    <div class="footer">
        <div>
            <a href="{{ url('/') }}" target="_blank">
                <img src="https://i.imgur.com/Gro4juQ.png" alt="g0v icon">
            </a>
        </div>

        <div class="text-right" title="更新時間">
            <span class="humanTime">{{ $record->get('humanTime') }}</span>
        </div>
    </div>
@endsection