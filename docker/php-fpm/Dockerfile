FROM php:8.2-fpm

RUN apt-get update && apt-get install -y wget git unzip zlib1g-dev libzmq3-dev

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql @composer intl

RUN pecl install xdebug && docker-php-ext-enable xdebug

ADD ./php.ini /usr/local/etc/php/conf.d/php.ini


ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /var/www
