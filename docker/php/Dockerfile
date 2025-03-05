#docker/php
FROM php:8.2-fpm

RUN apt update \
  && apt install -y libzip-dev git zip libpng-dev libxml2-dev qrencode

RUN docker-php-ext-install gd
RUN docker-php-ext-install zip
RUN apt install -y libc-client-dev libkrb5-dev \
    && PHP_OPENSSL=yes docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install intl
RUN docker-php-ext-configure intl
RUN docker-php-ext-install opcache

RUN docker-php-ext-configure gd

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/ \
    && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer

ENV PATH="~/.composer/vendor/bin:./vendor/bin:${PATH}"

RUN usermod -u 1000 www-data
RUN usermod -a -G www-data root
RUN mkdir -p /app
RUN chown -R www-data:www-data /app

WORKDIR /app
COPY php/xdebug.ini /usr/local/etc/php/conf.d/
COPY php/opcache.ini /usr/local/etc/php/conf.d/

EXPOSE 9001