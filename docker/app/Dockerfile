# Get the base image
FROM php:apache

# Set working directory
WORKDIR /app
COPY ./app/ .

# Install the zip extension
RUN apt-get update && \
apt-get install -y zlib1g-dev libzip-dev && \
docker-php-ext-configure zip && \
docker-php-ext-install zip

# Install MySQLi
RUN docker-php-ext-install pdo pdo_mysql mysqli && \
docker-php-ext-enable mysqli

# Required to generate images
RUN apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libexif-dev && \
docker-php-ext-install exif && \
docker-php-ext-configure gd --with-webp --with-jpeg && \
docker-php-ext-install gd

# Required to generate XLSX files
RUN apt-get install -y libonig-dev && \
docker-php-ext-install mbstring

# Required to convert number to words
RUN apt-get install -y libicu-dev && \
docker-php-ext-install intl

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
composer update

# Install NPM dependencies
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
	apt-get install -y nodejs && \
	rm -rf node_modules && \
	npm install && \
	npm run build