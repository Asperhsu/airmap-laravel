@extends('layouts.manager')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="height: calc(100vh - 80px)">
        <div class="col-8">
            <h2>新增使用者</h2>

            <hr>

            <form class="form-horizontal" method="POST">
                {{ csrf_field() }}

                <div class="form-group row">
                    <label for="name" class="col-sm-4 col-form-label">名稱</label>
                    <div class="col-sm-8">
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" value="{{ old('name') }}" required autofocus>

                        @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-4 col-form-label">E-Mail</label>
                    <div class="col-sm-8">
                        <input type="text" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-sm-4 col-form-label">密碼</label>
                    <div class="col-sm-8">
                        <input type="password" name="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" required>

                        @if ($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-sm-4 col-form-label">密碼確認</label>
                    <div class="col-sm-8">
                        <input type="password" name="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" id="password-confirm" required>

                        @if ($errors->has('password_confirmation'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password_confirmation') }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-8">
                        <button type="submit" class="btn btn-primary">
                            Register
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
