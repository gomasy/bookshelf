@extends('layouts.app')

@section('title', __('dashboard.title'))

@section('js')
<script defer src="@asset('/assets/dashboard.min.js')"></script>
@endsection

@section('navbar')
<div id="register"></div>
@endsection

@section('content')
<div id="table"></div><div id="modal"></div><div class="footer">@foreach (__('dashboard.credits') as $context){!! $context !!}<br>@endforeach</div>
@endsection
