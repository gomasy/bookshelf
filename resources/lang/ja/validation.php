<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attributeは承認しなければなりません。',
    'active_url'           => ':attributeは正しい URL ではありません。',
    'after'                => ':attributeは :date 以降の日付でなければなりません。',
    'after_or_equal'       => ':attributeは :date 以降の日付でなければなりません。',
    'alpha'                => ':attributeは英字のみにしてください。',
    'alpha_dash'           => ':attributeは英数字とハイフンのみにしてください。',
    'alpha_num'            => ':attributeは英数字のみにしてください。',
    'array'                => ':attributeは配列でなければなりません。',
    'before'               => ':attributeは :date 以前の日付でなければなりません。',
    'before_or_equal'      => ':attributeは :date 以前の日付でなければなりません。',
    'between'              => [
        'numeric' => ':attributeは :min〜:max でなければなりません。',
        'file'    => ':attributeは :min〜:max KB でなければなりません。',
        'string'  => ':attributeは :min〜:max 文字でなければなりません。',
        'array'   => ':attributeは :min〜:max 個でなければなりません。',
    ],
    'boolean'              => ':attributeは "はい" か "いいえ" でなければなりません。',
    'confirmed'            => ':attributeが一致していません。',
    'date'                 => ':attributeが正しい日付ではありません。',
    'date_format'          => ':attributeが ":format" 書式と一致していません。',
    'different'            => ':attributeは:otherと違うものでなければなりません。',
    'digits'               => ':attributeは :digits 桁でなければなりません。',
    'digits_between'       => ':attributeは :min〜:max 桁でなければなりません。',
    'dimensions'           => ':attributeが正しい画像サイズではありません。',
    'distinct'             => ':attributeの値が重複しています。',
    'email'                => ':attributeは正しいメールアドレスでなければなりません。',
    'exists'               => ':attributeは正しくありません。',
    'file'                 => ':attributeはファイルでなければなりません。',
    'filled'               => ':attributeは値を持たなければなりません。',
    'gt'                   => [
        'numeric' => ':attributeは :value よりも大きくなければなりません。',
        'file'    => ':attributeは :value KB よりも大きくなければなりません。',
        'string'  => ':attributeは :value 文字よりも大きくなければなりません。',
        'array'   => ':attributeは :value 個よりも大きくなければなりません。',
    ],
    'gte'                  => [
        'numeric' => ':attributeは :value 以上でなければなりません。',
        'file'    => ':attributeは :value KB 以上でなければなりません。',
        'string'  => ':attributeは :value 文字以上でなければなりません。',
        'array'   => ':attributeは :value 個以上でなければなりません。',
    ],
    'image'                => ':attributeは画像でなければなりません。。',
    'in'                   => ':attributeが正しくありません。',
    'in_array'             => ':attributeが:other内に存在しません。',
    'integer'              => ':attributeは整数でなければなりません。',
    'ip'                   => ':attributeは正しい IP アドレスでなければなりません。',
    'ipv4'                 => ':attributeは正しい IPv4 アドレスでなければなりません。',
    'ipv6'                 => ':attributeは正しい IPv6 アドレスでなければなりません。',
    'json'                 => ':attributeは正しい JSON 文字列でなければなりません。',
    'lt'                   => [
        'numeric' => ':attributeは :value よりも小さくなければなりません。',
        'file'    => ':attributeは :value KB よりも小さくなければなりません。',
        'string'  => ':attributeは :value 文字よりも小さくなければなりません。',
        'array'   => ':attributeは :value 個よりも小さくなければなりません。',
    ],
    'lte'                  => [
        'numeric' => ':attributeは :value 以下でなければなりません。',
        'file'    => ':attributeは :value KB 以下でなければなりません。',
        'string'  => ':attributeは :value 文字以下でなければなりません。',
        'array'   => ':attributeは :value 個以下でなければなりません。',
    ],
    'max'                  => [
        'numeric' => ':attributeは :max を超えてはなりません。',
        'file'    => ':attributeは :max KB を超えてはなりません。',
        'string'  => ':attributeは :max 文字を超えてはなりません。',
        'array'   => ':attributeは :max 個を超えてはなりません。',
    ],
    'mimes'                => ':attributeのファイルタイプは :values でなければなりません。',
    'mimetypes'            => ':attributeのファイルタイプは :values でなければなりません。',
    'min'                  => [
        'numeric' => ':attributeは少なくとも :min でなければなりません。',
        'file'    => ':attributeは少なくとも :min KB でなければなりません。',
        'string'  => ':attributeは少なくとも :min 文字でなければなりません。',
        'array'   => ':attributeは少なくとも :min 個でなければなりません。',
    ],
    'not_in'               => ':attributeは正しくありません。',
    'not_regex'            => ':attributeのフォーマットが不正です。',
    'numeric'              => ':attributeは数字でなければなりません。',
    'present'              => ':attributeは存在しなければなりません。',
    'regex'                => ':attributeのフォーマットが不正です。',
    'required'             => ':attributeは必須です。',
    'required_if'          => ':otherが:valuesの場合、:attributeは必須です。',
    'required_unless'      => ':otherが:valuesでない限り、:attributeは必須です。',
    'required_with'        => ':valuesが存在する場合、:attributeは必須です。',
    'required_with_all'    => ':valuesが存在する場合、:attributeは必須です。',
    'required_without'     => ':valuesが存在しない場合、:attributeは必須です。',
    'required_without_all' => ':valuesが存在しない場合、:attributeは必須です。',
    'same'                 => ':attributeと:otherは一致していません。',
    'size'                 => [
        'numeric' => ':attributeは :size でなければなりません。',
        'file'    => ':attributeは :size KB でなければなりません。',
        'string'  => ':attributeは :size 文字でなければなりません',
        'array'   => ':attributeは :size 個含まれていなければなりません。',
    ],
    'string'               => ':attributeは文字列でなければなりません。',
    'timezone'             => ':attributeは正しいタイムゾーンでなければなりません。',
    'unique'               => 'この:attributeはすでに存在します。',
    'uploaded'             => ':attributeのアップロードに失敗しました。',
    'url'                  => ':attributeのフォーマットが不正です。',
    'uuid'                 => ':attributeは正しい UUID でなければなりません。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        // account
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password-confirm' => 'パスワード確認',

        // register book
        'code' => '入力コード',

        // edit book
        'title' => 'タイトル',
        'volume' => '巻号',
        'authors' => '著者等',
        'published_date' => '出版日',
        'ndl_url' => 'URL',
    ],

];
