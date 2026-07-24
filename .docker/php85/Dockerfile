FROM php:8.5-fpm

USER root

# Install all system libs
RUN apt update && apt install -y --no-install-recommends \
    git \
    wget \
    zlib1g-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && apt clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Configure GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install PHP reqs
RUN docker-php-ext-install zip iconv gd

# Install PIE
COPY --from=ghcr.io/php/pie:bin /pie /usr/local/bin/pie

# Install xdebug
RUN pie install xdebug/xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:2.10 /usr/bin/composer /usr/local/bin/composer
