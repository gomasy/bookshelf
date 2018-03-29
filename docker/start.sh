#!/bin/sh

mysqld_safe --basedir=/usr &
nginx
mkdir /run/php-fpm && php-fpm --nodaemonize
