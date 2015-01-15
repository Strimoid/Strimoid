FROM ubuntu:utopic
COPY . /usr/src/strimoid
WORKDIR /usr/src/strimoid
EXPOSE 9000

RUN apt-get update
RUN apt-get install -y -qq git curl wget php5-cli \
    php5-apcu php5-gd php5-geoip php5-gmp php5-json \
    php5-mcrypt php5-mongo php5-readline php5-sqlite \
    php5-curl

RUN php5enmod mcrypt

# Install composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

ENV DB_HOST $MONGO_PORT_27017_TCP_ADDR
ENV DB_PORT $MONGO_PORT_27017_TCP_PORT

CMD php -S 0.0.0.0:9000 public/index.php
