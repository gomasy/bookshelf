@extends('layouts.app')

@section('title', __('auth.sign.in'))

@section('head')
<script src="@asset('/assets/core.min.js')"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">{{ __('auth.sign.in') }}</div>
            <div class="panel-body">
                <form role="form" method="POST" action="/login">
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
                            <input type="checkbox"> {{ __('auth.sign.remember') }}
                        </label>
                    </div>
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-block btn-primary">{{ __('auth.sign.in') }}</button>
                </form>
                <hr>
                <p><a href="{{ route('password.request') }}">{{ __('passwords.forgot') }}</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
