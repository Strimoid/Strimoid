FROM php:7.1-fpm

EXPOSE 8000
VOLUME /src/storage

RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++

# Install PHP extensions
RUN docker-php-ext-install exif intl pcntl pdo pdo_mysql zip
RUN pecl install apcu imagick

# Install Dockerize
ENV DOCKERIZE_VERSION v0.3.0
RUN curl -SL https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    | tar xzC /usr/local/bin

# Copy source files
COPY . /src
WORKDIR /src

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install

# Environment variables
ENV MYSQL_HOST mariadb

ENTRYPOINT ["/src/artisan"]
CMD ["serve", "--host", "0.0.0.0"]
