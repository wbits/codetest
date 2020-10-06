FROM php:7.4-apache

RUN apt-get update && \
    apt-get install -y --no-install-recommends git zip unzip

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer
