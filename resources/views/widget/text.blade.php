@extends('widget.layout')

@section('style')
    @parent
    <style>
        body{ overflow-x: hidden; overflow-y: hidden; cursor: default; }
        .header{ text-align: center; padding: 8px 0; }
        .header img{ height:25px; }

        .content{ padding: 0 10px; }
        .table{ margin-bottom: 0; }
        .table th{ text-align: right; }
        .pm25color{ display: inline-block; width:1.5em; border-radius: 50%; border: 1px solid #4F595D; }
    </style>
@endsection

@section('body')
    <div class="header">
        <a href="{{ route('v4.site', $group, $uuid) }}" title="site detail page" target="g0vDetail">
            <img src="https://i.imgur.com/IWqy7yh.png" alt="g0v icon">
        </a>
    </div>

    <div class="content">
        <table class="table">
            <tr>
                <th>名稱</th>
                <td>{{ $record->get('SiteName') }}</td>
            </tr>

            @if ($record->get('Data')->get('Dust2_5'))
            <tr>
                <th>PM 2.5</th>
                <td>
                    <span class="pm25color">&nbsp;</span>
                    <span class="value">{{ $record->get('Data')->get('Dust2_5') }}</span>
                    <span class="unit">μg/m<sup>3</sup></span>
                </td>
            </tr>
            @endif

            @if ($record->get('Data')->get('Temperature'))
            <tr>
                <th>溫度</th>
                <td>
                    <span class="value">{{ $record->get('Data')->get('Temperature') }}</span>
                    <span class="unit">℃</span>
                </td>
            </tr>
            @endif

            @if ($record->get('Data')->get('Humidity'))
            <tr>
                <th>濕度</th>
                <td>
                    <span class="value">{{ $record->get('Data')->get('Humidity') }}</span>
                    <span class="unit">%</span>
                </td>
            </tr>
            @endif

            <tr>
                <th>更新時間</th>
                <td>
                    <span class="humanTime"></span>
                </td>
            </tr>
        </table>
    </div>
@endsection