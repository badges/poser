FROM circleci/php:8.0-fpm-browsers

USER root

# install system packages
RUN apt-get update \
    && apt-get -y install git wget \
    && apt-get install -qqy libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# configure php packages
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/

# install php requirements
RUN docker-php-ext-install zip iconv gd

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
