FROM php:5.6-cli
COPY . /usr/src/strimoid
WORKDIR /usr/src/strimoid

RUN apt-get update -qq && apt-get install -y -qq git curl wget

# Install composer dependencies
RUN bash -c "wget http://getcomposer.org/composer.phar && mv composer.phar /usr/local/bin/composer"
RUN composer install

CMD [ "php", "-s", "public/index.php" ]
