BooksManager
============
[![Build Status](https://travis-ci.org/Gomasy/BooksManager.svg?branch=master)](https://travis-ci.org/Gomasy/BooksManager)
[![Coverage Status](https://coveralls.io/repos/github/Gomasy/BooksManager/badge.svg?branch=master)](https://coveralls.io/github/Gomasy/BooksManager?branch=master)
[![Dependency Status](https://gemnasium.com/badges/github.com/Gomasy/BooksManager.svg)](https://gemnasium.com/github.com/Gomasy/BooksManager)
[![Maintainability](https://api.codeclimate.com/v1/badges/52a84dc6faeb6b53f343/maintainability)](https://codeclimate.com/github/Gomasy/BooksManager/maintainability)

## これなに？
シンプルな蔵書管理ツール

## 必要なもの
* php >= 7.1
* composer (php dependency manager)
* npm (nodejs package manager)
* Database (MySQL / PostgreSQL / MSSQL)
* MTA (なくてもいいけど一部機能が動作しません)
* Linux サーバを自力で運用できる程度の知識

## デプロイ
    $ git clone https://github.com/Gomasy/BooksManager.git .

    // Install php packages.
    $ composer install --no-dev

    // Install nodejs modules and setting up a webpack.
    $ npm install
    $ npm run build

    // Setting up a Laravel.
    $ cp .env.sample .env
    $ php artisan key:generate
    $ php artisan migrate

## サンプル
* https://books.gomasy.jp/
