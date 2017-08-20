BooksManager
============
![License](https://img.shields.io/github/license/Gomasy/BooksManager.svg)
[![Build Status](https://travis-ci.org/Gomasy/BooksManager.svg?branch=master)](https://travis-ci.org/Gomasy/BooksManager)

## これなに？
シンプルな蔵書管理ツール  

## 必要なもの
php >= 7.1  
composer (php dependency manager)  
npm (nodejs package manager)

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
[Books Manager](https://books.gomasy.jp/)
