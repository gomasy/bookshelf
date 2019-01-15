@extends('layouts.app')

@section('title', '登録')

@section('content')
<main class="register">
    <div class="col-md-6 col-md-offset-3">
        <div class="row">
            <div class="panel panel-success">
                <div class="panel-heading">ユーザー登録</div>
                <div class="panel-body">
                    <form role="form" method="post" action="/register">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">{{ __('validation.attributes.name') }}</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="foobar" required autofocus>
@if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
@endif
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">{{ __('validation.attributes.email') }}</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="example@example.com" required>
@if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
@endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password">{{ __('validation.attributes.password') }}</label>
                            <input type="password" class="form-control" name="password" placeholder="●●●●●●●●" required>
@if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
@endif
                        </div>
                        <div class="form-group">
                            <label for="password-confirm">{{ __('validation.attributes.password-confirm') }}</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        {{ csrf_field() }}
                        <hr>
                        @include('elements/recaptcha')
                        <br>
                        <button type="submit" class="btn btn-block btn-primary">登録</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
