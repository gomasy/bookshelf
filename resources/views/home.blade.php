@extends('layouts.app')

@section('js')
        <script src="@asset('/assets/home.min.js')"></script>
@endsection

@section('content')
            <div class="container" id="content">
                <h2 class="title">本の管理をもっと簡単に、もっと便利に</h2>
                <span class="lead">お手持ちの書籍をISBNコードで管理し、ブラウザ上で表示・検索ができるシステムです</span>
                <hr class="half">
                <div class="row">
                    <div class="col-sm-4">
                        <i class="fa fa-barcode icon" aria-hidden="true"></i>
                        <h3>バーコードで登録</h3>
                        <p>ISBN コードから書籍情報を検索し登録することが可能です</p>
                    </div>
                    <div class="col-sm-4">
                        <i class="fa fa-list icon" aria-hidden="true"></i>
                        <h3>リストで管理</h3>
                        <p>登録した書籍は一覧で管理でき、編集も可能です</p>
                    </div>
                    <div class="col-sm-4">
                        <i class="fa fa-code icon" aria-hidden="true"></i>
                        <h3>オープンソース</h3>
                        <p>コードは公開されているので誰でも無料で利用することが可能です</p>
                    </div>
                </div>
            </div>
@endsection
