#!/bin/sh

mkdir -p /run/php

php-fpm7.2 --nodaemonize &
nginx
