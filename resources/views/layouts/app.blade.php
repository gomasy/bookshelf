<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
@if (!App::isDownForMaintenance())
        <meta name="csrf-token" content="{{ csrf_token() }}">
@endif
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link href="{{ asset('icon.png') }}" rel="icon" type="image/png">
        <link href="{{ asset('css/bootstrap.min.css?ver=3.3.7') }}" rel="stylesheet">
@yield('css')
        <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js?ver=3.3.7') }}"></script>
@yield('js')
@if (!App::isDownForMaintenance())
        <script>
@yield('inline-js')
        </script>
@endif
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-default navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <!-- Branding Image -->
                        <a class="navbar-brand" href="{{ url('/') }}">
                            {{ config('app.name', 'Laravel') }}
                        </a>
@if (!App::isDownForMaintenance())

                        <!-- Collapsed Hamburger -->
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                            <span class="sr-only">Toggle Navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
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
                            <li><a href="{{ route('login') }}">{{ __('auth.login') }}</a></li>
                            <li><a href="{{ route('register') }}">{{ __('auth.register') }}</a></li>
@else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }}<span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('account') }}">{{ __('account.header') }}</a>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('auth.logout') }}</a>
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
