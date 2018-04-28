@extends('layouts.app')

@section('title', __('dashboard.title'))

@section('js')
<script src="@asset('/assets/dashboard.min.js')"></script>
@endsection

@section('navbar')
<li class="nav-item">
    <form class="form-inline" id="register" role="form" method="POST" action="/create">
        <input class="form-control" type="text" name="code" placeholder="{{ __('dashboard.placeholder') }}" required>
        {{ csrf_field() }}
        <button class="btn btn-info" type="submit">{{ __('dashboard.register') }}</button>
        <button class="btn btn-secondary" id="btn-scan" type="button">{{ __('dashboard.scan') }}</button>
    </form>
</li>
@endsection

@section('content')
<div id="app"></div>

<div class="footer">
@foreach (__('dashboard.credits') as $context)
    {!! $context !!}<br>
@endforeach
</div>
@endsection
