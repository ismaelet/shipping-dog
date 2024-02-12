# Get the base image
FROM php:apache

# Set working directory
WORKDIR /var/www/html

# Setup dependencies
RUN apt-get update --fix-missing
RUN curl -I http://deb.debian.org/debian-security/dists/bookworm-security/InRelease

# Install MySQLi
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql

# Handle images
RUN apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libexif-dev \
    && docker-php-ext-install exif
RUN docker-php-ext-configure gd --with-webp --with-jpeg \
	&& docker-php-ext-install gd

# Required to generate XLSX files
RUN apt-get install -y libonig-dev
RUN docker-php-ext-install mbstring

# Enable htaccess rewrite
RUN a2enmod rewrite