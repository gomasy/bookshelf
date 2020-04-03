FROM ubuntu:18.04
LABEL maintainer="Gomasy <nyan@gomasy.jp>"

RUN echo "Asia/Tokyo" > /etc/localtime && \
    apt update && \
    apt -y install curl && \
    curl -sL https://deb.nodesource.com/setup_12.x | bash - && \
    apt update && \
    apt -y install composer php7.2-fpm php7.2-intl php7.2-mbstring php7.2-mysql php7.2-xml nginx nodejs unzip && \
    npm install -g yarn && \
    rm -rf /var/www/html && \
    cd /var/www && \
    curl -sL https://github.com/Gomasy/books-manager/archive/master.tar.gz | tar xvfz - && \
    mv books-manager-master html && \
    chown -R www-data. . && \
    cd html && \
    composer install --no-dev && \
    yarn install --pure-lockfile && \
    yarn build && \
    composer clear-cache && \
    yarn cache clean && \
    npm uninstall -g yarn && \
    apt -y remove composer curl nodejs unzip yarn && \
    apt -y autoremove && \
    rm -rf /var/cache/apt && \
    rm -rf /var/lib/apt/lists/*

ADD docker/nginx.conf /etc/nginx/nginx.conf
ADD docker/start.sh /start.sh
CMD [ "/start.sh" ]

EXPOSE 80
