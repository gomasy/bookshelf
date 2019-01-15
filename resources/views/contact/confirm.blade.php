@extends('layouts.app')

@section('title', 'お問い合わせ')

@section('content')
<script src="https://www.google.com/recaptcha/api.js"></script>
<main class="contact">
    <div class="col-md-8 col-md-offset-2">
        <div class="row">
            <div class="panel panel-warning">
                <div class="panel-heading">送信内容の確認</div>
                <div class="panel-body">
                    <form role="form" action="/contact/submit" method="post">
                        <div class="form-group">
                            <label class="control-label">お名前</label>
                            <input class="form-control" type="text" name="name" value="{{ $request->name }}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="control-label">メールアドレス</label>
                            <input class="form-control" type="text" name="mail" value="{{ $request->email }}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="control-label">お問い合わせ内容</label>
                            <textarea class="form-control" name="inquiry" rows="12" disabled>{{ $request->inquiry }}</textarea>
                        </div>
                        {{ csrf_field() }}
                        @include('elements/recaptcha')
                        <br>
                        <input class="btn btn-block btn-danger" type="submit" value="送信" >
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
