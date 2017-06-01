@extends('layouts.app')

@section('content')
            <div class="container">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">Service unavailable</div>
                        <div class="panel-body">
                            {{ json_decode(file_get_contents(storage_path('framework/down')), true)['message'] }}
                        </div>
                    </div>
                </div>
            </div>
@endsection
