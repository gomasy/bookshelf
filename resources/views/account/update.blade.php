@extends('layouts.app')

@section('content')
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ __('account.update.header') }}</div>
                            <div class="panel-body">
                                <form role="form" action="{{ route('account/update') }}" method="POST">
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email">{{ __('validation.attributes.email') }}</label>
                                        <input class="form-control" type="email" name="email" value="{{ old('email', Auth::user()['email']) }}">
@if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
@endif
                                    </div>
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label for="name">{{ __('validation.attributes.name') }}</label>
                                        <input class="form-control" type="text" name="name" value="{{ old('name', Auth::user()['name']) }}">
@if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
@endif
                                    </div>
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password">{{ __('validation.attributes.password') }}</label>
                                        <input class="form-control" type="password" name="password" value="">
@if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
@endif
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">{{ __('validation.attributes.password-confirm') }}</label>
                                        <input class="form-control" type="password" name="password_confirmation" value="">
                                    </div>
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-block btn-primary">{{ __('account.update.submit') }}</button>
                                    <hr>
                                    <p><a href="{{ route('account/delete') }}">{{ __('account.delete.link') }}</a></p>
                                    </div>
                                </form>
                            </div>
                        </body>
                    </div>
                </div>
            </div>
@endsection
