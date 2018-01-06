@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-between mt-3 px-3">
        <h3>{{ $title }}</h3>
        <a href="{{ route('admin.thingspeak.create') }}" class="btn btn-primary mr-1">
            <span class="oi oi-plus"></span> 新增設備
        </a>
    </div>

    <hr>

    <div class="row-fluid">
        <div class="card-columns">
            @foreach ($items as $item)
            <div class="card">
                <div class="card-body pb-2">
                    <h4 class="card-title">
                        {{ $item->party }}
                    </h4>
                    <h6 class="card-subtitle mb-2 text-muted">
                        <span class="badge badge-pill badge-primary">Maker</span> {{ $item->maker }}
                    </h6>
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
                                <button type="button" class="btn btn-light" disabled>
                                    <span class="badge badge-success">{{ str_replace('field', '', $field) }}</span>
                                    {{ ucfirst($type) }}
                                </button>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="card-body p-2 pb-3">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm">
                                <button data-channel="{{ $item->channel }}" class="btn-fetch btn btn-outline-info btn-block">
                                    <span class="oi oi-pulse"></span> Fetch
                                </a>
                            </div>
                            <div class="col-sm">
                                <a href="{{ route('admin.thingspeak.edit', ['id' => $item->id]) }}" class="btn btn-outline-primary btn-block">
                                    <span class="oi oi-pencil"></span> Edit
                                </a>
                            </div>
                            <div class="col-sm">
                                <button data-target="{{ route('admin.thingspeak.destroy', $item->id) }}" class="btn-destroy btn btn-outline-danger btn-block">
                                    <span class="oi oi-x"></span> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-auto mr-auto">
                            <small class="text-muted">Updated at {{ $item->updated_at->diffForHumans() }}</small>
                        </div>
                        <div class="col-auto">
                            @if ($item->active)
                            <span class="badge badge-pill badge-success">Enable <span class="oi oi-media-play"></span></span>
                            @else
                            <span class="badge badge-pill badge-dark"><span class="oi oi-media-pause"></span> Disable</span>
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

<!-- Modal -->
<div class="modal fade" id="fetchStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Latest Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(".btn-fetch").click(function(){
        var $modal = $("#fetchStatusModal");
        var channel = $(this).data('channel');
        var template = "https://api.thingspeak.com/channels/{identity}/feeds.json?results=1";
        var url = template.replace('{identity}', channel);

        $modal.find('pre').text('Loading').end().modal('show');

        $.get(url).done(function (msg) {
            $modal.find('pre').text(JSON.stringify(msg, null, 4));
        }).fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });
    });

    $(".btn-destroy").click(function(){
        if (!confirm("確定刪除?")) { return; }

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
    });
</script>
@endpush
