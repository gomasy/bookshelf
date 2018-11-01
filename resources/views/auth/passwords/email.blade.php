@extends('layouts.app')

@section('title', __('passwords.title'))

@section('content')
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
                <form  role="form" method="POST" action="/password/email">
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email">{{ __('validation.attributes.email') }}</label>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="example@example.com" required autofocus>
@if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
@endif
                    </div>
                    <hr>
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-block btn-primary">{{ __('passwords.send') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
