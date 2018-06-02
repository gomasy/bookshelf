@extends('layouts.app')

@section('title', __('home.title'))

@section('head')
<script src="@asset('/assets/home.min.js')"></script>
@endsection

@section('content')
<main class="container">
    <h2>{{ __('home.catchcopy') }}</h2>
    <h3>{{ __('home.lead') }}</h3>
    <section class="row">
        <a class="btn btn-primary" href="/register">今すぐ登録</a>
    </section>
    <hr>
    <section class="row">
        <div class="col-sm-4">
            <i class="fa fa-barcode" aria-hidden="true"></i>
            <h4>バーコードで登録</h4>
            <p>ISBN コードから書籍情報を検索し登録することが可能です</p>
        </div>
        <div class="col-sm-4">
            <i class="fa fa-list" aria-hidden="true"></i>
            <h4>リストで管理</h4>
            <p>登録した書籍は一覧で管理でき、編集も可能です</p>
        </div>
        <div class="col-sm-4">
            <i class="fa fa-github" aria-hidden="true"></i>
            <h4>オープンソース</h4>
            <p>コードは公開されているので誰でも無料で利用することが可能です</p>
        </div>
    </section>
</main>
@endsection
