@extends('layouts.app')

@section('title', 'メールアドレスの確認')

@section('content')
<main class="account-verify">
    <div class="col-md-6 col-md-offset-3">
        <div class="row">
            <div class="panel panel-info">
                <div class="panel-heading">メールアドレスの確認が必要です</div>
                <div class="panel-body">
                    <p>
                        確認リンクが入力されたメールアドレスに送信されました。<br>
                        メールが届かない場合は下記ボタンをクリックして、別のメールをリクエストしてください。
                    </p>
                    <div class="col-md-2">
                        <div class="row">
                            <a class="btn btn-block btn-primary" href="{{ route('verification.resend') }}">再送信</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
