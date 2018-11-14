@extends('layouts.app')

@section('title', 'お問い合わせフォーム')

@section('content')
<main style="text-align: center;" class="contact">
    <form action="./" method="post">
        <div style="text-align: center;">名前</div>
        <input class="form-control" type="text" name="name" size="50" required><br>
        <div style="text-align: center;">メールアドレス</div>
        <input class="form-control" type="email" name="mail" size="50" required><br>
        <div style="text-align: center;">問合せ内容</div>
        <textarea class="form-control" name="inquiry" cols="50" rows="12" placeholder="お問い合わせ内容をご記入ください" required></textarea><br>
        {{ csrf_field() }}
        <input class="btn btn-info" type="submit" value="確認">
    </form>
</main>
@endsection
