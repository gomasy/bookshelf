@extends('layouts.app')

@section('title', __('auth.register.title'))

@section('content')
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ __('auth.register.header') }}</div>
                            <div class="panel-body">
                                <form role="form" method="POST" action="{{ route('register') }}">
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label for="name">{{ __('validation.attributes.name') }}</label>
                                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="foobar" required autofocus>
@if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
@endif
                                    </div>
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email">{{ __('validation.attributes.email') }}</label>
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="example@example.com" required>
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
                                    <div class="form-group">
                                        <label for="password-confirm">{{ __('validation.attributes.password-confirm') }}</label>
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                    <hr>
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-block btn-primary">{{ __('auth.register.title') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
