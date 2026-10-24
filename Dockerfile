# Base image
FROM dunglas/frankenphp:1-php8.4 AS base

# Install PHP extensions
RUN install-php-extensions \
    mysqli \
    pdo \
    pdo_mysql \
    gd \
    intl \
    zip \
    opcache

RUN apt-get update && apt-get install -y \
    default-mysql-client \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Install Composer
RUN install-php-extensions \
    @composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Install pnpm
RUN npm install -g pnpm@12

# Build step
FROM base AS build

# Copy application files
COPY ./app /var/www/html

# Install Composer dependencies
RUN composer install --no-interaction --prefer-dist --no-progress --optimize-autoloader

# Install frontend dependencies and build
RUN pnpm install --frozen-lockfile \
    && pnpm run build