# Get the base image
FROM php:apache

# Set working directory
WORKDIR /var/www/html
COPY . /var/www/html

# Install the zip extension
RUN apt-get update && \
	apt-get install -y zlib1g-dev libzip-dev && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip

# Setup dependencies
RUN apt-get update --fix-missing
RUN curl -I http://deb.debian.org/debian-security/dists/bookworm-security/InRelease

# Install MySQLi
RUN docker-php-ext-install pdo \
	pdo_mysql \
	mysqli

RUN docker-php-ext-enable mysqli

# Handle images
RUN apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libexif-dev \
    && docker-php-ext-install exif
RUN docker-php-ext-configure gd --with-webp --with-jpeg \
	&& docker-php-ext-install gd

# Required to generate XLSX files
RUN apt-get install -y libonig-dev
RUN docker-php-ext-install mbstring

# Required to convert number to words
RUN apt-get install -y libicu-dev && \
    docker-php-ext-install intl

# Install Composer and dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer update

# Enable .htaccess rewrite
RUN a2enmod rewrite
