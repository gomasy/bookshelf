@extends('layouts.app')

@section('content')
            <div class="container">
                <div class="row">
                    <div id="site-title">Books Manager</div>
                    <div id="site-nav">
                        アカウントお持ちの方は左上「ログイン」ボタンから<br>
                        新規登録の方は「新規登録」からお進みください
                    </div>
                    <div id="site-desc">BooksManagerとは？</div>
                    <div class="col-xs-6 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                ①お手持ちの書籍をISBNコードで管理し、ブラウザ上で整列表示・検索ができるシステムです
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                ② 直接入力またはスマートフォンアプリのバーコードリーダによって、ISBNコードをシステムに取り込みます
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                ③取り込まれたISBNコードをもとに、国立国会図書館の検索システムから、作品名・著者名などのデータを取得し保存します
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                ④ブラウザ上でデータの編集・削除も可能です
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
