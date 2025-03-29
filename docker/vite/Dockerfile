# Get the base image
FROM php:apache

# Set working directory
WORKDIR /app
COPY ./app/ .

# Install NPM dependencies
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
	apt-get install -y nodejs && \
	rm -rf node_modules && \
	npm install && \
	npm run build