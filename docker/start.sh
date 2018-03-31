#!/bin/sh

mkdir -p /run/php-fpm
cd /opt/BooksManager-master
if [[ ! -e ".key.lock" ]]; then
    ./artisan key:generate
    touch .key.lock
fi

mysqld_safe --basedir=/usr &
php-fpm --nodaemonize &
nginx
