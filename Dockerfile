FROM php:alpine

COPY . /src
WORKDIR /src

EXPOSE 8000
VOLUME /src/storage

# Install Alpine Linux packages
RUN apk update && apk add icu-dev openssl-dev

# Install PHP extensions
RUN docker-php-ext-install intl openssl pdo pdo_mysql

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Add PHP-PM
RUN composer require react/http:dev-master
RUN composer require php-pm/php-pm:dev-master
RUN composer require php-pm/httpkernel-adapter:dev-master

# Environment variables
ENV MYSQL_HOST mariadb

CMD serve
ENTRYPOINT php artisan
