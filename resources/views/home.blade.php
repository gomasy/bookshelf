@extends('layouts.app')

@section('title', __('home.title'))

@section('js')
<script src="@asset('/assets/home.min.js')"></script>
@endsection

@section('content')
<div id="entrance">
    <h2>{{ __('home.catchcopy') }}</h2>
    <span class="lead">{{ __('home.lead') }}</span>
    <hr>
    <div class="row">
        <div class="col-sm-4">
            <i class="glyphicon glyphicon-barcode" aria-hidden="true"></i>
            <h3>{{ __('home.row.barcode.title') }}</h3>
            <p>{{ __('home.row.barcode.text') }}</p>
        </div>
        <div class="col-sm-4">
            <i class="glyphicon glyphicon-list" aria-hidden="true"></i>
            <h3>{{ __('home.row.list.title') }}</h3>
            <p>{{ __('home.row.list.text') }}</p>
        </div>
        <div class="col-sm-4">
            <i class="glyphicon glyphicon-globe" aria-hidden="true"></i>
            <h3>{{ __('home.row.opensource.title') }}</h3>
            <p>{{ __('home.row.opensource.text') }}</p>
        </div>
    </div>
</div>
@endsection
