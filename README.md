Books Manager
============
[![Build Status](https://travis-ci.org/Gomasy/books-manager.svg?branch=master)](https://travis-ci.org/Gomasy/books-manager)
[![Coverage Status](https://coveralls.io/repos/github/Gomasy/books-manager/badge.svg?branch=master)](https://coveralls.io/github/Gomasy/books-manager?branch=master)
[![Dependency Status](https://gemnasium.com/badges/github.com/Gomasy/books-manager.svg)](https://gemnasium.com/github.com/Gomasy/books-manager)
[![Maintainability](https://api.codeclimate.com/v1/badges/52a84dc6faeb6b53f343/maintainability)](https://codeclimate.com/github/Gomasy/books-manager/maintainability)

## これなに？
シンプルな蔵書管理ツール

## 必要なもの
* php >= 7.1.3
* composer (php dependency manager)
* npm (nodejs package manager)
* Database (MySQL / PostgreSQL / MSSQL)
* MTA (なくてもいいけど一部機能が動作しません)

## 素でデプロイ
    $ git clone https://github.com/Gomasy/books-manager.git .

    // Install php packages.
    $ composer install --no-dev

    // Install nodejs modules and setting up a webpack.
    $ npm install
    $ npm run build

    // Setting up a Laravel.
    $ cp .env.sample .env
    $ php artisan key:generate
    $ php artisan migrate

## Docker を利用してデプロイ
    $ docker run -d -p 80:80 gomasy/books-manager

## サンプル
* https://books.gomasy.jp/
