@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 90vh;">
    <div class="card col-6 p-0">
        <h4 class="card-header">修改 {{ $editUser->name }} 密碼</h4>

        <div class="card-body">
            <form method="POST">
                {{ csrf_field() }}

                <div class="form-group">
                    @if ($isSelf)
                    <label for="old_password">舊密碼</label>
                    @else
                    <label for="old_password">您的密碼</label>
                    @endif
                    <input type="password" class="form-control {{ $errors->has('old_password') ? ' is-invalid' : '' }}" id="old_password" name="old_password" required autofocus>

                    @if ($errors->has('old_password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('old_password') }}
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password">新密碼</label>
                    <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" required>

                    @if ($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password_confirmation">確認密碼</label>
                    <input type="password" class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" id="password_confirmation" name="password_confirmation" required>

                    @if ($errors->has('password_confirmation'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password_confirmation') }}
                    </div>
                    @endif
                </div>

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        修改
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection