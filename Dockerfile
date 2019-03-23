FROM centos:7
LABEL maintainer="Gomasy <nyan@gomasy.jp>"

RUN yum -y update && \
    yum -y install epel-release && \
    curl -O https://rpms.remirepo.net/enterprise/remi-release-7.rpm && \
    rpm -Uvh remi-release-7.rpm && \
    rm -f remi-release-7.rpm && \
    curl -sL https://dl.yarnpkg.com/rpm/yarn.repo > /etc/yum.repos.d/yarn.repo && \
    yum -y --enablerepo=remi-php72 install composer git mariadb-server nginx php-fpm php-mysql yarn && \
    yum clean all && \
    sed -i "s/user = apache/user = nginx/" /etc/php-fpm.d/www.conf && \
    sed -i "s/group = apache/group = nginx/" /etc/php-fpm.d/www.conf

ADD docker/my.cnf /etc/my.cnf.d/addon.cnf
ADD docker/nginx.conf /etc/nginx/nginx.conf

RUN mysql_install_db --user=mysql --basedir=/usr --datadir=/var/lib/mysql && \
    (mysqld_safe --basedir=/usr &); \
    cd /opt && \
    curl -sL https://github.com/Gomasy/books-manager/archive/master.tar.gz | tar xvfz - && \
    cd books-manager-master && \
    cp .env.example .env && \
    chown -R nginx. . && \
    composer install --no-dev && \
    echo "CREATE DATABASE homestead; GRANT ALL ON homestead.* TO homestead@localhost IDENTIFIED BY 'secret';" | mysql -u root && \
    ./artisan migrate && \
    yarn install --pure-lockfile && \
    yarn build && \
    rm -rf ~/.{cache,config,composer,local,yarn,yarnrc}

ADD docker/start.sh /start.sh
CMD [ "/start.sh" ]

EXPOSE 80
