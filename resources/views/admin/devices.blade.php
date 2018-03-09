@extends('layouts.admin')

@section('style')
@parent
<style>
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-between mt-3 px-3">
        <h3>{{ $title }}</h3>
        <a href="{{ route('admin.thingspeak.create') }}" class="btn btn-primary mr-1">
            <span class="fa fa-plus"></span> 新增設備
        </a>
    </div>

    <hr>

    <div class="row-fluid">
        <div class="card-columns">
            @foreach ($items as $item)
            <div class="card">
                <div class="card-header text-white {{ $item->active ? 'bg-success' : 'bg-secondary' }}">
                    <div class="row">
                        <div class="col">{{ $item->party }}</div>
                        <div class="col-auto mr-auto">
                            <i class="fas fa-toggle-{{ $item->active ? 'on' : 'off' }}"></i>
                        </div>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-4 text-right"><strong>Maker</strong></div>
                            <div class="col">{{ $item->maker }}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-4 text-right"><strong>Channel</strong></div>
                            <div class="col">
                                <a target="_blank" href="https://thingspeak.com/channels/{{ $item->channel }}">
                                    {{ $item->channel }}
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-4 text-right"><strong>Fields</strong></div>
                            <div class="col">
                                @foreach ($item->fields_map as $type => $field)
                                <div class="field">
                                    <span class="badge badge-success">{{ str_replace('field', '', $field) }}</span>
                                    {{ ucfirst($type) }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col">
                                <button data-channel="{{ $item->channel }}" class="btn-fetch btn btn-outline-info btn-block">
                                    <i class="fas fa-bolt"></i> Fetch
                                </a>
                            </div>
                            <div class="col">
                                <a href="{{ route('admin.thingspeak.edit', ['id' => $item->id]) }}" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                            <div class="col">
                                <button data-target="{{ route('admin.thingspeak.destroy', $item->id) }}" class="btn-destroy btn btn-outline-danger btn-block">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="card-footer text-right">
                    <small class="text-muted">Updated at {{ $item->updated_at->diffForHumans() }}</small>
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
