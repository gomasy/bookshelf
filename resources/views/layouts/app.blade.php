<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width">
@if (!App::isDownForMaintenance())
        <meta name="csrf-token" content="{{ csrf_token() }}">
@endif
        <title>{{ config('app.name') }} - @yield('title')</title>
        <link href="@asset('/assets/icon.png')" rel="icon" type="image/png">
        <script src="@asset('/assets/core.min.js')"></script>
        @yield('js')
    </head>
    <body>
        <div class="wrapper">
            <nav class="active" id="sidebar">
                <div class="sidebar-header">
                    <h3>Books Manager</h3>
                    <strong><i class="glyphicon glyphicon-book" aria-hidden="true"></i></strong>
                </div>

                <ul class="list-unstyled components">
                    <li><a href="{{ route('home') }}"><i class="glyphicon glyphicon-home" aria-hidden="true"></i>{{ __('home.title') }}</a></li>
                    <li><a href="#"><i class="glyphicon glyphicon-question-sign" aria-hidden="true"></i>{{ __('home.sidebar.help') }}</a></li>
@auth
                    <li><a href="{{ route('account') }}"><i class="glyphicon glyphicon-cog" aria-hidden="true"></i>{{ __('home.sidebar.setting') }}</a></li>
                    <hr>
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="glyphicon glyphicon-log-out" aria-hidden="true"></i>
                            {{ __('auth.sign.out') }}
                        </a>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
@endauth
@guest
                    <hr>
                    <li><a href="{{ route('login') }}"><i class="glyphicon glyphicon-log-in" aria-hidden="true"></i>{{ __('auth.sign.in') }}</a></li>
                    <li><a href="{{ route('register') }}"><i class="glyphicon glyphicon-user"></i>{{ __('auth.register.title') }}</a></li>
@endguest
                </ul>
            </nav>

            <div id="content">
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            @yield('title')
                        </div>

                        @yield('navbar')
                    </div>
                </nav>
                @yield('content')
            </div>
        </div>
    </body>
</html>
