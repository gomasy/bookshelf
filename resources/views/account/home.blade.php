@extends('layouts.app')

@section('content')
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ __('account.update.header') }}</div>
                            <div class="panel-body">
                                <form class="form-horizontal" role="form" action="{{ route('account/update') }}" method="POST">
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label">{{ __('validation.attributes.email') }}</label>
                                        <div class="col-md-6">
                                            <input class="form-control" type="email" name="email" value="{{ old('email', Auth::user()['email']) }}">
@if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
@endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label">{{ __('validation.attributes.name') }}</label>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="name" value="{{ old('name', Auth::user()['name']) }}">
@if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
@endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label">{{ __('validation.attributes.password') }}</label>
                                        <div class="col-md-6">
                                            <input class="form-control" type="password" name="password" value="">
@if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
@endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">{{ __('validation.attributes.password-confirm') }}</label>
                                        <div class="col-md-6">
                                            <input class="form-control" type="password" name="password_confirmation" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-primary">{{ __('account.submit') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </body>
                    </div>
                </div>
            </div>
@endsection
