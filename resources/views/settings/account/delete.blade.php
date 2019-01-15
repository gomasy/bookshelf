@extends('layouts.app')

@section('title', '確認')

@section('content')
<main class="account-delete">
    <div class="col-md-6 col-md-offset-3">
        <div class="row">
            <div class="panel panel-danger">
                <div class="panel-heading">続行するにはパスワードを入力する必要があります</div>
                <div class="panel-body">
                    <form role="form" method="post" action="/settings/account/delete">
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
                        <button type="submit" class="btn btn-block btn-danger">削除する</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
