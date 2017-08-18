@extends('layouts.app')

@section('inline-js')
@if (session('result'))
        <script>
            function customFunc() {
@if (session('result')['status'] === 200)
                addSucceeded('{{ session('result')['data']['title'] }}');
@elseif (session('result')['status'] === 404)
                searchFailed();
@elseif (session('result')['status'] === 409)
                addFailed('{{ session('result')['data']['title'] }}');
@elseif (session('result')['status'] === 422)
                validationFailed('{{ session('result')['data'][0] }}');
@endif
            }
        </script>
@endif
@endsection

@section('navbar')
                            <li class="nav-item">
                                <form class="form-inline my-2 my-lg-0" id="register" role="form" method="POST" action="{{ route('create') }}">
                                    <input class="form-control mr-sm-2" type="text" name="code" placeholder="{{ __('home.placeholder') }}" required>
                                    {{ csrf_field() }}
                                    <button class="btn btn-info my-2 my-sm-0" type="submit">{{ __('auth.register') }}</button>
                                    <button class="btn btn-secondary my-2 my-sm-0" type="button" onclick="jumpZXingUrl();">{{ __('home.scan') }}</button>
                                </form>
                            </li>
@endsection

@section('content')
            <div class="container">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table table-hover table-striped" id="main">
                                <thead>
                                    <tr>
                                        <th>{{ __('home.title') }}</th>
                                        <th>{{ __('home.title_ruby') }}</th>
                                        <th>{{ __('home.volume') }}</th>
                                        <th>{{ __('home.authors') }}</th>
                                        <th>{{ __('home.isbn') }}</th>
                                        <th>{{ __('home.jpno') }}</th>
                                        <th>{{ __('home.published_date') }}</th>
                                        <th>{{ __('home.ndl_url') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
@endsection
