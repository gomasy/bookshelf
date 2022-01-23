#!/usr/bin/env bash
set -Eeuo pipefail

if [[ "$1" == apache2* ]] || [ "$1" = 'php-fpm' ]; then
	uid="$(id -u)"
	gid="$(id -g)"
	if [ "$uid" = '0' ]; then
		case "$1" in
			apache2*)
				user="${APACHE_RUN_USER:-www-data}"
				group="${APACHE_RUN_GROUP:-www-data}"

				# strip off any '#' symbol ('#1000' is valid syntax for Apache)
				pound='#'
				user="${user#$pound}"
				group="${group#$pound}"
				;;
			*) # php-fpm
				user='www-data'
				group='www-data'
				;;
		esac
	else
		user="$uid"
		group="$gid"
	fi

	if [ ! -e index.php ]; then
		echo >&2 "Bookshelf not found in $PWD - copying now..."
		shopt -s dotglob

		cd /var/www
		rm -rf *
		cp -r /usr/src/bookshelf-master/* .

		if [ ! -e .env ]; then
			cp .env.example .env
			php artisan key:generate
		fi

		chown -R www-data. .
	fi
fi

exec "$@"
