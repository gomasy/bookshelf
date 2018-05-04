@extends('layouts.app')

@section('title', 'Service unavailable')

@section('js')
<script src="@asset('/assets/core.min.js')"></script>
@endsection

@section('content')
<div class="panel panel-default">
    <div class="panel-body">
        {{ json_decode(file_get_contents(storage_path('framework/down')), true)['message'] }}
    </div>
</div>
@endsection
