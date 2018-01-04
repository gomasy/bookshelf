@extends('layouts.app')

@section('title', __('passwords.title'))

@section('content')
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ __('passwords.title') }}</div>
                            <div class="panel-body">
@if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
@endif
                                <form role="form" method="POST" action="{{ route('password.request') }}">
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email">{{ __('validation.attributes.email') }}</label>
                                        <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
@if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
@endif
                                    </div>
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password">{{ __('validation.attributes.password') }}</label>
                                        <input id="password" type="password" class="form-control" name="password" required>
@if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
@endif
                                    </div>
                                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        <label for="password-confirm">{{ __('validation.attributes.password-confirm') }}</label>
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
@if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
@endif
                                    </div>
                                    <hr>
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-block btn-primary">{{ __('passwords.confirm') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
