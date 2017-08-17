@extends('layouts.app')

@section('content')
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ __('account.delete.header') }}</div>
                            <div class="panel-body">
                                <form class="form-horizontal" role="form" action="{{ route('account/delete') }}" method="POST">
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label">{{ __('validation.attributes.password') }}</label>
                                        <div class="col-md-6">
                                            <input class="form-control" type="password" name="password" value="" required>
@if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
@endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger">{{ __('account.delete.submit') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </body>
                    </div>
                </div>
            </div>
@endsection
