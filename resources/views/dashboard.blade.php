@extends('layouts.app')

@section('title', __('dashboard.title'))

@section('js')
<script src="@asset('/assets/dashboard.min.js')"></script>
@if (session('statusCode'))
<script>
function showResult(provider, messages) {
@switch (session('statusCode'))
    @case (200)
    provider(messages.add.success, { type: 'success' });
    @break

    @case (404)
    provider(messages.not_exist, { type: 'warning' });
    @break

    @case (409)
    provider(messages.add.failure, { type: 'danger' });
    @break
@endswitch
}
</script>
@endif
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
<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-hover table-striped" id="main">
            <thead>
                <tr>
                    <th>{{ __('validation.attributes.title') }}</th>
                    <th>{{ __('validation.attributes.volume') }}</th>
                    <th>{{ __('validation.attributes.authors') }}</th>
                    <th>{{ __('validation.attributes.published_date') }}</th>
                    <th>{{ __('validation.attributes.ndl_url') }}</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="footer">
@foreach (__('dashboard.credits') as $context)
    {!! $context !!}<br>
@endforeach
</div>

<div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{{ __('dashboard.edit') }}</h4>
            </div>
            <form class="form-horizontal" id="form-edit" role="form" method="POST" action="/edit">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{ __('validation.attributes.title') }}</label>
                        <div class="col-sm-9">
                            <input class="form-control" id="input-title" type="text" name="title" value="" maxlength="255" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{ __('validation.attributes.volume') }}</label>
                        <div class="col-sm-9">
                            <input class="form-control" id="input-volume" type="text" name="volume" value="" maxlength="255">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{ __('validation.attributes.authors') }}</label>
                        <div class="col-sm-9">
                            <input class="form-control" id="input-authors" type="text" name="authors" value="" maxlength="255" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{ __('validation.attributes.published_date') }}</label>
                        <div class="col-sm-9">
                            <input class="form-control" id="input-published_date" type="date" name="published_date" value="" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{ csrf_field() }}
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('dashboard.cancel') }}</button>
                    <button type="submit" class="btn btn-info" id="btn-edit-confirm">{{ __('dashboard.ok') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{{ __('dashboard.delete') }}</h4>
            </div>
            <div class="modal-body">
                {{ __('dashboard.confirm') }}
            </div>
            <div class="modal-footer">
                <form id="form-delete" role="form" method="POST" action="/delete">
                    {{ csrf_field() }}
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('dashboard.cancel') }}</button>
                    <button type="submit" class="btn btn-danger" id="btn-delete-confirm">{{ __('dashboard.ok') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
