<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
@if (!App::isDownForMaintenance())
        <meta name="csrf-token" content="{{ csrf_token() }}">
@endif
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link href="{{ asset('assets/icon.png') }}" rel="icon" type="image/png">
        <script src="{{ asset('assets/bundle.js') }}"></script>
@yield('js')
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-default navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <!-- Branding Image -->
                        <a class="navbar-brand" id="brand" href="{{ url('/') }}">
                            {{ config('app.name', 'Laravel') }}
                        </a>
@if (!App::isDownForMaintenance())
                    </div>

                    <div class="collapse navbar-collapse" id="app-navbar-collapse">
                        <!-- Left Side Of Navbar -->
                        <ul class="nav navbar-nav">
                            &nbsp;
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right">
@yield('navbar')

                            <!-- Authentication Links -->
@if (Auth::guest())
                            <li><a class="button" id="btn-login" href="{{ route('login') }}">{{ __('auth.login') }}</a></li>
                            <li><a class="button" id="btn-register" href="{{ route('register') }}">{{ __('auth.register') }}</a></li>
@else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }}<span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a id="menu-account" href="{{ route('account') }}">{{ __('account.update.header') }}</a>
                                        <a id="menu-logout" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('auth.logout') }}</a>
                                        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
@endif
                        </ul>
@endif
                    </div>
                </div>
            </nav>

@yield('content')
        </div>
    </body>
</html>
