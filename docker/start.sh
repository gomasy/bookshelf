mkdir /run/php-fpm

mysqld_safe --basedir=/usr &
nginx
php-fpm --nodaemonize
