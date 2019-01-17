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
    <div class="panel-footer">
        <p>
            注: 本棚を中身ごと削除しなかった場合はデフォルトに移動されます。<br>
            同じ本がある場合は<strong>マージされます（デフォルト優先）。</strong>
        </p>
    </div>
</div>
@endsection
