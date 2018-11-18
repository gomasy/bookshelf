@extends('layouts.app')

@section('title', 'お問い合わせ')

@section('content')
<main class="contact">
    <div class="col-md-8 col-md-offset-2">
        <div class="row">
            <div class="panel panel-warning">
                <div class="panel-heading">お問い合わせ</div>
                <div class="panel-body">
                    <form action="/contact" method="post">
                        <div class="form-group">
                            <label class="control-label">お名前</label>
                            <input class="form-control" type="text" name="name" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label">メールアドレス</label>
                            <input class="form-control" type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label">お問い合わせ内容</label>
                            <textarea class="form-control" name="inquiry" rows="12" placeholder="お問い合わせ内容をご記入ください" required></textarea>
                        </div>
                        {{ csrf_field() }}
                        <input class="btn btn-block btn-info" type="submit" value="確認">
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
