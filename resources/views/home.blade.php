@extends('layouts.app')

@section('title', 'ホーム')

@section('content')
<main class="home">
    <section>
        <h2>本の管理をもっと簡単に、もっと便利に</h2>
        <h3>手持ちの書籍をISBNコードなどで管理し、ブラウザ上で簡単に表示・検索できるサービスです</h3>
        <a class="btn btn-primary" href="/register">今すぐ登録</a>
    </section>
    <hr>
    <section>
        <div class="col-sm-4">
            <div class="row">
                <i class="fas fa-barcode" aria-hidden="true"></i>
                <h4>バーコードで登録</h4>
                <p>ISBN コードから書籍情報を検索し登録することが可能です</p>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="row">
                <i class="fas fa-list" aria-hidden="true"></i>
                <h4>リストで管理</h4>
                <p>登録した書籍は一覧で管理でき、編集も可能です</p>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="row">
                <i class="fab fa-github" aria-hidden="true"></i>
                <h4>オープンソース</h4>
                <p>コードは公開されているので誰でも無料で利用することが可能です</p>
            </div>
        </div>
    </section>
</main>
@endsection
