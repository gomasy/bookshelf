# BooksManager
シンプルな蔵書管理ツール  
動作には PHP7.x + Composer + DB(MySQL or PostgreSQL or SQLite) が必要となります。  

## デプロイ
    $ git clone https://github.com/Gomasy/BooksManager.git .
    $ cp .env.sample .env
    $ php artisan key:generate
    $ php artisan make:migrate

## サンプル
[Books Manager](https://books.gomasy.jp/)
