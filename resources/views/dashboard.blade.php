@extends('layouts.app')

@section('js')
@if (session('result'))
        <script>
            function showResult() {
@if (session('result')->status() === 404)
                alert('no book were found.');
@endif
            }
        </script>
@endif
@endsection

@section('navbar')
                            <li class="nav-item">
                                <form class="form-inline my-2 my-lg-0" id="form-register" role="form" method="POST" action="{{ route('create') }}">
                                    <input class="form-control mr-sm-2" type="text" name="code" placeholder="{{ __('home.placeholder') }}" required>
                                    {{ csrf_field() }}
                                    <button class="btn btn-info my-2 my-sm-0" type="submit">{{ __('auth.register') }}</button>
                                    <button class="btn btn-secondary my-2 my-sm-0" id="btn-scan" type="button">{{ __('home.scan') }}</button>
                                </form>
                            </li>
@endsection

@section('content')
            <div class="container">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table table-hover table-striped" id="main">
                                <thead>
                                    <tr>
                                        <th>{{ __('home.title') }}</th>
                                        <th>{{ __('home.volume') }}</th>
                                        <th>{{ __('home.authors') }}</th>
                                        <th>{{ __('home.published_date') }}</th>
                                        <th>{{ __('home.ndl_url') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-edit">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>編集</h4>
                        </div>
                        <form class="form-horizontal" id="form-edit" role="form" action="{{ route('edit') }}" method="POST">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">タイトル</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" id="input-title" type="text" name="title" value="" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">巻号</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" id="input-volume" type="text" name="volume" value="" maxlength="255">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">著者等</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" id="input-authors" type="text" name="authors" value="" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">出版日</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" id="input-published_date" type="text" name="published_date" value="" pattern="\d{4}-\d{2}-\d{2}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                {{ csrf_field() }}
                                <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                                <button type="submit" class="btn btn-info" id="btn-edit-confirm">決定</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-delete">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>削除</h4>
                        </div>
                        <div class="modal-body">
                            続行します、よろしいですか？
                        </div>
                        <div class="modal-footer">
                            <form id="form-delete" role="form" action="{{ route('delete') }}" method="POST">
                                {{ csrf_field() }}
                                <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                                <button type="submit" class="btn btn-danger" id="btn-delete-confirm">続行</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
@endsection
