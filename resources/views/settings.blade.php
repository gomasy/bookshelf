@extends('layouts.app')

@section('title', __('settings.title'))

@section('js')
<script src="@asset('/assets/settings.min.js')"></script>
@endsection

@section('content')
<div class="row" id="settings">
    <div class="col-lg-2 col-sm-3">
        <div class="panel panel-default">
            <div class="panel-heading">{{ __('settings.title') }}</div>
            <ul class="list-unstyled" id="settings-menu">
                <li{!! Request::path() === 'settings/account' ? ' class="active"' : '' !!}><a href="/settings/account"><i class="fa fa-user" aria-hidden="true"></i>{{ __('settings.account.update.title') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="col-lg-6 col-sm-7">
        @yield('account')
    </div>
</div>
@endsection
