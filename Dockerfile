FROM php:alpine
COPY . /usr/src/strimoid
WORKDIR /usr/src/strimoid
EXPOSE 8000

# Install Alpine Linux packages
RUN apk update && apk add icu-dev libmcrypt-dev

# Install PHP extensions
RUN docker-php-ext-install intl mcrypt pdo pdo_mysql

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

CMD MYSQL_HOST=mariadb php artisan serve
