@extends('layouts.app')

@section('title', __('settings.account.delete.title'))

@section('head')
<script src="@asset('/assets/general.min.js')"></script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-danger">
                <div class="panel-heading">{{ __('settings.account.delete.header') }}</div>
                <div class="panel-body">
                    <form role="form" method="POST" action="/settings/account/delete">
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password">{{ __('validation.attributes.password') }}</label>
                            <input class="form-control" type="password" name="password" required autofocus>
@if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
@endif
                        </div>
                        <hr>
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-block btn-danger">{{ __('settings.account.delete.submit') }}</button>
                    </form>
                </div>
            </body>
        </div>
    </div>
</div>
@endsection
