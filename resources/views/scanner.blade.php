@extends('layouts.app')

@section('title', __('scanner.title'))

@section('head')
<script src="@asset('/assets/scanner.min.js')"></script>
@endsection

@section('content')
<div id="content"></div>
@endsection
