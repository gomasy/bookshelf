@extends('layouts.app')

@section('css')
        <link href="@asset('/assets/home.min.css')" rel="stylesheet">
        <link href="@asset('/assets/core.min.css')" rel="stylesheet">
@endsection

@section('js')
        <script src="@asset('/assets/core.min.js')"></script>
@endsection

@section('content')
            <div class="container" id="top-page">
                <h2 class="top-title">本の管理をもっと簡単に、もっと便利に</h2>
                <span class="lead">お手持ちの書籍をISBNコードで管理し、ブラウザ上で表示・検索ができるシステムです</span>
            </div>
@endsection
