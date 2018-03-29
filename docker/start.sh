#!/bin/sh

cd /opt/books
mysqld_safe --basedir=/usr &

if [[ ! -e ".env" ]]; then
    sleep 5

    echo "CREATE DATABASE homestead; GRANT ALL ON homestead.* TO homestead@localhost IDENTIFIED BY 'secret';" | mysql -u root
    cp .env.example .env
    ./artisan key:generate
    ./artisan migrate
fi

nginx
mkdir /run/php-fpm && php-fpm --nodaemonize
