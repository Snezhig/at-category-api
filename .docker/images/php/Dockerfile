FROM composer:2.3 as composer

FROM php:8.1-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apt update -y && apt upgrade -y
RUN apt install -y git wget zip
RUN install-php-extensions gd xdebug-3.1.0 zip

RUN usermod -u 1000 -aG www-data www-data
RUN chown www-data:www-data /var/www