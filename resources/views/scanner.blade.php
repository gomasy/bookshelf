@extends('layouts.app')

@section('title', __('scanner.title'))

@section('head')
<script defer src="@asset('/assets/scanner.min.js')"></script>
@endsection

@section('content')
<div id="content"></div>
@endsection
