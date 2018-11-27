@extends('layouts.app')

@section('title', 'ログイン')

@section('head')
<script>function onSubmit() { document.getElementById('login-form').submit(); }</script>
@endsection

@section('content')
<main class="login">
    <div class="col-md-6 col-md-offset-3">
        <div class="row">
            <div class="panel panel-info">
                <div class="panel-heading">ログイン</div>
                <div class="panel-body">
                    <form id="login-form" role="form" method="POST" action="/login">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">{{ __('validation.attributes.email') }}</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="example@example.com" required autofocus>
@if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
@endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password">{{ __('validation.attributes.password') }}</label>
                            <input id="password" type="password" class="form-control" name="password" placeholder="●●●●●●●●" required>
@if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
@endif
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> ログイン状態を記憶
                            </label>
                        </div>
                        @include('elements/recaptcha')
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-block btn-primary">ログイン</button>
                    </form>
                    <hr>
                    <p><a href="{{ route('password.request') }}">パスワードを忘れた時は？</a></p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
