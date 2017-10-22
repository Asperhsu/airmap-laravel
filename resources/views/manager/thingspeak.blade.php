@extends('layouts.manager')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mt-3">
        <a href="{{ route('thingspeak.create') }}" class="btn btn-primary btn-sm mr-1">新增設備</a>
    </div>

    <hr>
    
    <div class="row-fluid">    
        <div class="card-columns">
            @foreach ($items as $item)
            <div class="card">
                <div class="card-body pb-2">
                    <h4 class="card-title">{{ $item->party }}</h4>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $item->maker }}</h6>
                </div>

                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <td class="text-right">Channel</td>
                            <td>
                                <a target="_blank" href="https://thingspeak.com/channels/{{ $item->channel }}">
                                {{ $item->channel }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Fields</td>
                            <td>
                                @foreach ($item->fields_map as $type => $field)
                                <h6><span class="badge badge-secondary">{{ $field }}</span> {{ $type }} </h6>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="card-body p-2 pb-3">
                    <div class="d-flex justify-content-around">
                        <a href="{{ route('thingspeak.fetch', ['id' => $item->id]) }}" class="btn btn-outline-info btn-sm">Fetch</a>
                        <a href="{{ route('thingspeak.edit', ['id' => $item->id]) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                        <button data-target="{{ route('thingspeak.destroy', ['id' => $item->id]) }}" class="btn-destroy btn btn-outline-danger btn-sm">Delete</button>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-auto mr-auto">
                            <small class="text-muted">Updated at {{ $item->updated_at->diffForHumans() }}</small>
                        </div>
                        <div class="col-auto">
                            @if ($item->active)
                            <span class="badge badge-pill badge-success">Enable</span>
                            @else
                            <span class="badge badge-pill badge-dark">Disable</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            {{ csrf_field() }}
        <div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(".btn-destroy").click(function(){
        if (confirm("確定刪除?")) {
            var target = $(this).data('target');
            var token = $("meta[name='csrf-token']").attr('content');
            
            $.ajax({
                url: target,
                method: "post",
                data: {
                    _method: 'delete',
                    _token: token,
                },
            }).done(function (msg) {
                location.reload();
            }).fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        }
    });
</script>
@endsection
