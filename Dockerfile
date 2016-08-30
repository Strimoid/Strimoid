FROM php:fpm-alpine

EXPOSE 8000
VOLUME /src/storage

# Install Alpine Linux packages
RUN apk update && apk add autoconf git icu-dev imagemagick-dev openssl-dev

# Install PHP extensions
RUN docker-php-ext-install exif intl openssl pcntl pdo pdo_mysql

RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS libtool && \
    pecl install apcu && \
    pecl install imagick && \
    docker-php-ext-enable apcu imagick && \
    apk del .phpize-deps

# Copy source files
COPY . /src
WORKDIR /src

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Environment variables
ENV MYSQL_HOST mariadb

ENTRYPOINT ["/src/artisan"]
CMD ["serve", "--host", "0.0.0.0"]
