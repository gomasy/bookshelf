FROM php:7.4-apache

RUN set -ex; \
	apt-get update; \
	apt-get install -y --no-install-recommends gnupg2; \
	curl -sSL https://deb.nodesource.com/setup_16.x | sh -; \
	curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -; \
	echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
		fonts-ipafont-gothic \
		git \
		nodejs \
		yarn \
	; \
	\
	savedAptMark="$(apt-mark showmanual)"; \
	apt-get install -y --no-install-recommends \
		libfreetype6-dev \
		libjpeg-dev \
		libicu-dev \
		libpng-dev \
		libzip-dev \
	; \
	docker-php-ext-configure gd \
		--with-freetype \
		--with-jpeg \
	; \
	docker-php-ext-install -j "$(nproc)" \
		gd \
		intl \
		pdo_mysql \
		zip \
	; \
	out="$(php -r 'exit(0);')"; \
	[ -z "$out" ]; \
	err="$(php -r 'exit(0);' 3>&1 1>&2 2>&3)"; \
	[ -z "$err" ]; \
	extDir="$(php -r 'echo ini_get("extension_dir");')"; \
	[ -d "$extDir" ]; \
	apt-mark auto '.*' > /dev/null; \
	apt-mark manual $savedAptMark; \
	ldd "$extDir"/*.so \
		| awk '/=>/ { print $3 }' \
		| sort -u \
		| xargs -r dpkg-query -S \
		| cut -d: -f1 \
		| sort -u \
		| xargs -rt apt-mark manual; \
	pecl install apcu; \
	docker-php-ext-enable \
		apcu \
		opcache \
	; \
	\
	{ \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=2'; \
		echo 'opcache.fast_shutdown=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini; \
	\
	{ \
		echo 'error_reporting = E_ERROR | E_WARNING | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_RECOVERABLE_ERROR'; \
		echo 'display_errors = Off'; \
		echo 'display_startup_errors = Off'; \
		echo 'log_errors = On'; \
		echo 'error_log = /dev/stderr'; \
		echo 'log_errors_max_len = 1024'; \
		echo 'ignore_repeated_errors = On'; \
		echo 'ignore_repeated_source = Off'; \
		echo 'html_errors = Off'; \
	} > /usr/local/etc/php/conf.d/error-logging.ini; \
	\
	a2enmod rewrite expires; \
	a2enmod remoteip; \
	{ \
		echo 'RemoteIPHeader X-Forwarded-For'; \
		echo 'RemoteIPInternalProxy 10.0.0.0/8'; \
		echo 'RemoteIPInternalProxy 172.16.0.0/12'; \
		echo 'RemoteIPInternalProxy 192.168.0.0/16'; \
		echo 'RemoteIPInternalProxy 169.254.0.0/16'; \
		echo 'RemoteIPInternalProxy 127.0.0.0/8'; \
	} > /etc/apache2/conf-available/remoteip.conf; \
	a2enconf remoteip; \
	find /etc/apache2 -type f -name '*.conf' -exec sed -ri 's/([[:space:]]*LogFormat[[:space:]]+"[^"]*)%h([^"]*")/\1%a\2/g' '{}' +; \
	\
	cd /usr/src; \
	curl -L https://github.com/Gomasy/bookshelf/archive/refs/heads/master.tar.gz | tar xfz -; \
	cd bookshelf-master; \
	curl -s https://getcomposer.org/installer | php; \
	php composer.phar install --prefer-dist --no-dev; \
	yarn install --pure-lockfile; \
	yarn build; \
	rm -rf $HOME/.composer composer.phar node_modules; \
	ln -s public html; \
	chown -R www-data. .; \
	\
	apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
		git \
		gnupg2 \
		nodejs \
		yarn \
	; \
	rm -rf /var/lib/apt/lists/*

COPY docker-entrypoint.sh /usr/local/bin/

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
