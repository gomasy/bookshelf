#!/bin/sh

cd /opt/books
if [[ ! -e ".key.lock" ]]; then
    ./artisan key:generate
    touch .key.lock
fi

mysqld_safe --basedir=/usr &
nginx
mkdir -p /run/php-fpm && php-fpm --nodaemonize
