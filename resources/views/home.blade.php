@extends('layouts.app')

@section('title', __('home.title'))

@section('head')
<script src="@asset('/assets/home.min.js')"></script>
        <meta property="og:title" content="{{ config('app.name') }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ config('app.url') }}">
        <meta property="og:image" content="/assets/icon.png">
@endsection

@section('content')
<div id="entrance">
    <h2>{{ __('home.catchcopy') }}</h2>
    <span class="lead">{{ __('home.lead') }}</span>
    <hr>
    <div class="row">
        <div class="col-sm-4">
            <i class="fa fa-barcode" aria-hidden="true"></i>
            <h3>{{ __('home.row.barcode.title') }}</h3>
            <p>{{ __('home.row.barcode.text') }}</p>
        </div>
        <div class="col-sm-4">
            <i class="fa fa-list" aria-hidden="true"></i>
            <h3>{{ __('home.row.list.title') }}</h3>
            <p>{{ __('home.row.list.text') }}</p>
        </div>
        <div class="col-sm-4">
            <i class="fa fa-github" aria-hidden="true"></i>
            <h3>{{ __('home.row.opensource.title') }}</h3>
            <p>{{ __('home.row.opensource.text') }}</p>
        </div>
    </div>
</div>
@endsection
