@extends('layouts.app')

@section('title', '設定')

@section('content')
<main class="settings">
    <div class="col-lg-2 col-lg-offset-2 col-sm-3 col-sm-offset-1">
        <div class="setting-menu row">
            <div class="panel panel-default">
                <div class="panel-heading">設定</div>
                <ul class="list-unstyled setting-menu-content">
                    <li{!! Request::path() === 'settings/account' ? ' class="active"' : '' !!}><a href="/settings/account"><i class="fas fa-user" aria-hidden="true"></i>アカウント</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-sm-6">
        <div class="setting-content row">
            @yield('account')
        </div>
    </div>
</main>
@endsection
