FROM php:apache

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli

RUN curl -I http://deb.debian.org/debian-security/dists/bookworm-security/InRelease

RUN apt-get update --fix-missing

RUN apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev


RUN docker-php-ext-configure gd --with-webp --with-jpeg && \
    docker-php-ext-install gd

RUN a2enmod rewrite