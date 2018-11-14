<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width">
@if (!App::isDownForMaintenance())
        <meta name="csrf-token" content="{{ csrf_token() }}">
@endif
        <meta property="description" content="手持ちの書籍をISBNコードなどで管理し、ブラウザ上で簡単に表示・検索できるサービスです">
        <title>{{ config('app.name') }} - @yield('title')</title>
        <link href="@asset('/assets/icon.png')" rel="icon" type="image/png">
        <script src="@asset('/assets/vendor.js')"></script>
        <script defer src="@asset('/assets/app.js')"></script>
        @yield('head')

        <!-- ogp -->
        <meta property="og:type" content="{{ Request::path() === '/' ? 'website' : 'article' }}">
        <meta property="og:url" content="{{ Request::url() }}">
        <meta property="og:image" content="{{ config('app.url') }}/assets/icon.png">
    </head>
    <body>
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3>Books Manager</h3>
                <strong><i class="fa fa-book" aria-hidden="true"></i></strong>
            </div>

            <ul class="list-unstyled components">
                <li{!! Request::path() === '/' ? ' class="active"' : '' !!}><a href="/"><i class="fa fa-home" aria-hidden="true"></i>ホーム</a></li>
                <li{!! Request::path() === 'help' ? ' class="active"' : '' !!}><a href="/help"><i class="fa fa-question-circle" aria-hidden="true"></i>ヘルプ</a></li>
@auth
                <li{!! preg_match('/^settings(\/.*)?$/', Request::path()) ? ' class="active"' : '' !!}><a href="/settings"><i class="fa fa-cog" aria-hidden="true"></i>設定</a></li>
                <hr>
                <li>
                    <a href="/logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                        ログアウト
                    </a>
                    <form id="logout-form" method="POST" action="/logout" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
@endauth
@guest
                <hr>
                <li{!! Request::path() === 'login' ? ' class="active"' : '' !!}><a href="/login"><i class="fa fa-sign-in" aria-hidden="true"></i>ログイン</a></li>
                <li{!! Request::path() === 'register' ? ' class="active"' : '' !!}><a href="/register"><i class="fa fa-user-plus" aria-hidden="true"></i>登録</a></li>
@endguest
            </ul>
        </nav>

        <div class="container wrapper">
            <header class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        @yield('title')

                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                            <span class="sr-only">Toggle Navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse" id="app-navbar-collapse">
                        <ul class="nav navbar-right">
                            <li class="nav-item">
                                @yield('navbar')
                            </li>

@auth
                            <li class="nav-item sm">
                                <a class="button" href="/settings">設定</a>
                                <a class="button" href="#">ヘルプ</a>
                                <a class="button" href="/logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">ログアウト</a>
                            </li>
@endauth
@guest
                            <li class="nav-item sm">
                                <a class="button" href="/login">ログイン</a>
                                <a class="button" href="/register">登録</a>
                            </li>
@endguest
                        </ul>
                    </div>
                </div>
            </header>

            @yield('content')

            <footer>
                <ul class="list-unstyled">
                    <li><a href="">運営者情報</a></li>
                    <li><a href="/contact">お問い合わせ</a></li>
                    <li><a href="/privacy-policy">プライバシーポリシー</a></li>
                </ul>
                <p>👎 本サービスは国立国会図書館のAPIを利用しています 👎</p>
                <p>&copy; Books Manager 2017-{{ date('Y') }}</p>
            </footer>
        </div>
    </body>
</html>
