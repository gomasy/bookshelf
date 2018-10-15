<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'メールアドレスまたはパスワードが一致しません。',
    'throttle' => 'ログイン試行回数が多すぎます。:seconds 秒後に再度お試しください。',
    'register' => [
        'title' => '登録',
        'header' => 'ユーザー登録',
    ],
    'sign' => [
        'in' => 'ログイン',
        'out' => 'ログアウト',
        'remember' => 'ログイン状態を記憶',
    ],
    'verify' => [
        'title' => 'メールアドレスの確認',
        'header' => 'メールアドレスの確認が必要です',
        'message' => '確認リンクが入力されたメールアドレスに送信されました。<br>メールが届かない場合は下記ボタンをクリックして、別のメールをリクエストしてください。',
        'resend' => '再送信',
    ],
];
