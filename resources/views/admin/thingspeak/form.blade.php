@extends('layouts.admin')

@php $isEdit = isset($thingspeak->id); @endphp

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card col-6">
        <div class="card-body">
            <h4 class="card-title">
                {{ $isEdit ? '修改設備' : '新增設備' }}
            </h4>

            <form method="POST" action="{{ $isEdit ? route('admin.thingspeak.update', $thingspeak->id) : route('admin.thingspeak.store') }}">
                {{ csrf_field() }}
                {{ $isEdit ? method_field('PUT') : '' }}

                <div class="form-group">
                    <label for="channel">Group</label>
                    <div>
                        @php $groupValue = old('group', $thingspeak->party) ?: 'Probecube'; @endphp
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-info active">
                                <input type="radio" name="group" value="Probecube" autocomplete="off" {{ $groupValue == 'Probecube' ? 'checked' : '' }}> Probecube
                            </label>
                            <label class="btn btn-info">
                                <input type="radio" name="group" value="Independent" autocomplete="off" {{ $groupValue == 'Independent' ? 'checked' : '' }}> Independent
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="channel">Channel ID</label>
                    <input type="number" class="form-control {{ $errors->has('channel') ? ' is-invalid' : '' }}" id="channel"
                        name="channel" value="{{ old('channel', $thingspeak->channel) }}" required autofocus>

                    @if ($errors->has('channel'))
                    <div class="invalid-feedback">
                        {{ $errors->first('channel') }}
                    </div>
                    @endif
                </div>

                <div class="form-group form-group-party">
                    <label for="party">專案 / 群組</label>
                    <input type="text" class="form-control {{ $errors->has('party') ? ' is-invalid' : '' }}" id="party"
                        name="party" value="{{ old('party', $thingspeak->party) }}" autofocus>

                    @if ($errors->has('party'))
                    <div class="invalid-feedback">
                        {{ $errors->first('party') }}
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="maker">Maker</label>
                    <input type="text" class="form-control {{ $errors->has('maker') ? ' is-invalid' : '' }}" id="maker"
                        name="maker" value="{{ old('maker', $thingspeak->maker) }}" required>

                    @if ($errors->has('maker'))
                    <div class="invalid-feedback">
                        {{ $errors->first('maker') }}
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="maker">Fields</label>

                    <div class="container">
                        <div class="row">
                            <div class="col-sm">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text">PM 2.5</span></div>
                                    <input type="number" class="form-control" name="fields[pm25]" maxlength="1"
                                        value="{{ old('fields.pm25', optional($fields)->get('pm25')) }}">
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text">濕度</span></div>
                                    <input type="number" class="form-control" name="fields[humidity]" maxlength="1"
                                        value="{{ old('fields.humidity', optional($fields)->get('humidity')) }}">
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text">溫度</span></div>
                                    <input type="number" class="form-control" name="fields[temperature]" maxlength="1"
                                        value="{{ old('fields.temperature', optional($fields)->get('temperature')) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($errors->has('fields'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fields') }}
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="channel">Active</label>
                    <div>
                        @php $activeValue = old('active', $thingspeak->active) ?: '1'; @endphp
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-info active">
                                <input type="radio" name="active" value="1" autocomplete="off" {{ $activeValue == '1' ? 'checked' : '' }}> Enable
                            </label>
                            <label class="btn btn-info">
                                <input type="radio" name="active" value="0" autocomplete="off" {{ $activeValue == '0' ? 'checked' : '' }}> Disable
                            </label>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ $isEdit ? '修改' : '新增' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $("input[name='group']").change(function() {
        var isProbecube = $(this).val() == 'Probecube';
        $(".form-group-party").toggle(!isProbecube).find('input').prop('required', !isProbecube);
    });
    $("input[name='group']:first").trigger('change');
</script>
@endpush
