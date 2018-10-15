@extends('layouts.app')

@section('title', __('auth.verify.title'))

@section('head')
<script src="@asset('/assets/general.min.js')"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading">{{ __('auth.verify.header') }}</div>
            <div class="panel-body">
                <p>{!! __('auth.verify.message') !!}</p>
                <div class="row">
                    <div class="col-md-2">
                        <a class="btn btn-block btn-primary" href="{{ route('verification.resend') }}">{{ __('auth.verify.resend') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
