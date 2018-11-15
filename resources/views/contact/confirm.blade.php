@extends('layouts.app')

@section('title', 'お問い合わせ')

@section('content')
<h2>問合せ内容</h2>
<form action="/contact/submit" method="post">
    <table class="table" border="2">
        <tr>
            <td>名前</td>
            <td style="word-wrap:break-word;">
                {{ $request->name }}
                <input type="hidden" name="name" value="{{ $request->name }}">
            </td>
        </tr>
        <tr>
            <td>メールアドレス</td>
            <td style="word-wrap:break-word;">
                {{ $request->mail }}
                <input type="hidden" name="mail" value="{{ $request->mail }}">
            </td>
        </tr>
        <tr>
            <td>問い合わせ内容</td>
            <td>
                <?php $words = wordwrap($request->inquiry, 65, '<br>', true); echo $words; ?>
                <input type="hidden" name="inquiry" value="{{ $request->inquiry }}">
            </td>
        </tr>
        {{ csrf_field() }}
    </table>
    <div style="text-align:center;">
        <input class="btn btn-info" type="submit" value="送信" >
    </div>
</form>
@endsection
