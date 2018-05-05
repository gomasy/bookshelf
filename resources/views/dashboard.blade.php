@extends('layouts.app')

@section('title', __('dashboard.title'))

@section('head')
<script defer src="@asset('/assets/dashboard.min.js')"></script>
@endsection

@section('navbar')
<div id="register"></div>
@endsection

@section('content')
<div id="content"></div><div id="footer">@foreach (__('dashboard.credits') as $context){!! $context !!}<br>@endforeach</div>
@endsection
