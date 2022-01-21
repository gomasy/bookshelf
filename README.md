Books Manager
============
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FGomasy%2Fbookshelf.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2FGomasy%2Fbookshelf?ref=badge_shield)
[![Coverage Status](https://coveralls.io/repos/github/Gomasy/bookshelf/badge.svg?branch=master)](https://coveralls.io/github/Gomasy/bookshelf?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/c07e881ac5b0fe7a7c2d/maintainability)](https://codeclimate.com/github/Gomasy/bookshelf/maintainability)

## これなに？
シンプルな蔵書管理ツール

## 必要なもの
* php >= 7.1.3
* composer (php dependency manager)
* yarn (nodejs package manager)
* Database (MySQL / PostgreSQL / MSSQL)
* MTA (なくてもいいけど一部機能が動作しません)

## 素でデプロイ
    $ git clone https://github.com/Gomasy/bookshelf.git .

    // Install php packages.
    $ composer install --no-dev

    // Install nodejs modules and setting up a webpack.
    $ yarn install --pure-lockfile
    $ yarn build

    // Setting up a Laravel.
    $ cp .env.sample .env
    $ php artisan key:generate
    $ php artisan migrate

## サンプル
* https://books.gomasy.jp/
