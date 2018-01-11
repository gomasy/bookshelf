@extends('layouts.app')

@section('js')
<script src="@asset('/assets/settings.min.js')"></script>
@endsection

@section('content')
    <div class="row" id="settings">
        <div class="col-lg-3 col-lg-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ __('settings.title') }}</div>
                <div class="panel-body">
                ああああああああああああああああああああああああああああああああああああ
                </div>
            </div>
        </div>
        @yield('account')
        <div class="col-lg-2"></div>
    </div>
@endsection
