BooksManager
============
[![Build Status](https://travis-ci.org/Gomasy/BooksManager.svg?branch=master)](https://travis-ci.org/Gomasy/BooksManager)

## これなに？
シンプルな蔵書管理ツール  
動作には PHP7.x + Composer + DB(MySQL or PostgreSQL or MSSQL) が必要となります。  

## デプロイ
    $ git clone https://github.com/Gomasy/BooksManager.git .
    $ composer install --no-dev
    $ cp .env.sample .env
    $ php artisan key:generate
    $ php artisan migrate

## サンプル
[Books Manager](https://books.gomasy.jp/)
