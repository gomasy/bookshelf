FROM centos:7
LABEL maintainer="Gomasy <nyan@gomasy.jp>"

RUN yum -y update && \
    yum -y install epel-release && \
    curl -O https://rpms.remirepo.net/enterprise/remi-release-7.rpm && \
    rpm -Uvh remi-release-7.rpm && \
    rm -f remi-release-7.rpm && \
    yum -y --enablerepo=remi-php72 install composer git mariadb-server nginx npm php-fpm php-mysql && \
    yum clean all && \
    sed -ie "s/user = apache/user = nginx/g" /etc/php-fpm.d/www.conf && \
    sed -ie "s/group = apache/group = nginx/g" /etc/php-fpm.d/www.conf

ADD docker/my.cnf /etc/my.cnf.d/addon.cnf
ADD docker/nginx.conf /etc/nginx/nginx.conf
ADD docker/start.sh /start.sh

RUN mysql_install_db --user=mysql --basedir=/usr --datadir=/var/lib/mysql && \
    (mysqld_safe --basedir=/usr &); \
    sleep 5 && \
    echo "CREATE DATABASE homestead; GRANT ALL ON homestead.* TO homestead@localhost IDENTIFIED BY 'secret';" | mysql -u root && \
    git clone --depth=1 https://github.com/Gomasy/BooksManager.git /opt/books && \
    cd /opt/books && \
    cp .env.example .env && \
    chown -R nginx. . && \
    composer install --no-dev && \
    ./artisan key:generate && \
    ./artisan migrate && \
    (npm install || node node_modules/node-sass/scripts/install.js) && \
    npm run build && \
    rm -rf ~/.{composer,npm}

EXPOSE 80
CMD [ "/start.sh" ]
