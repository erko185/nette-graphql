FROM php:8.2-apache

RUN apt-get update
RUN apt-get install --yes --force-yes cron nano g++ gettext libicu-dev git openssl libc-client-dev libkrb5-dev libxml2-dev libfreetype6-dev libgd-dev libmcrypt-dev bzip2 libbz2-dev libtidy-dev libcurl4-openssl-dev libz-dev libmemcached-dev libxslt-dev libzip-dev zip unzip

RUN a2enmod rewrite

RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-enable pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD vhost-default.conf /etc/apache2/sites-enabled/000-default.conf

WORKDIR /var/www/html/

COPY ../www/ ./
