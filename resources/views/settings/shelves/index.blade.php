@extends('settings.index')

@section('head')
<script defer src="@asset('assets/shelves.*.js')"></script>
@endsection

@section('setting-content')
<div class="panel panel-default">
    <div class="panel-heading">本棚</div>
    <div class="panel-body">
        <div id="shelves"></div>
    </div>
</div>
@endsection
