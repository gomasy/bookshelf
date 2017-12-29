@extends('layouts.app')

@section('css')
        <link href="{{ asset(substr(json_decode(file_get_contents(dirname(__FILE__).'/../../../public/assets/manifest.json'), true)['home.css'], 2)) }}" rel="stylesheet">
        <link href="{{ asset(substr(json_decode(file_get_contents(dirname(__FILE__).'/../../../public/assets/manifest.json'), true)['core.css'], 2)) }}" rel="stylesheet">
@endsection

@section('js')
        <script src="{{ asset(substr(json_decode(file_get_contents(dirname(__FILE__).'/../../../public/assets/manifest.json'), true)['core.js'], 2)) }}"></script>
@endsection

@section('content')
            <div class="container" id="top-page">
                <h2 class="top-title">本の管理をもっと簡単に、もっと便利に</h2>
                <span class="lead">お手持ちの書籍をISBNコードで管理し、ブラウザ上で表示・検索ができるシステムです</span>
            </div>
@endsection
