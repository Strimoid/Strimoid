### ---------------------
### web assets stage
### ---------------------
FROM node:alpine AS assets

WORKDIR /src
COPY package.json /src
RUN npm install -q

COPY . /src
RUN npm run build

### ---------------------
### final stage
### ---------------------
FROM quay.io/strimoid/php

EXPOSE 80
VOLUME /src/storage

COPY ./docker-entrypoint.sh /
ENTRYPOINT ["docker-entrypoint.sh"]

RUN a2enmod rewrite
COPY config/docker/apache.conf $APACHE_CONFDIR/sites-available/000-default.conf

WORKDIR /src
COPY . /src

COPY --from=assets /src/public/assets /src

RUN composer install -n
ENV PATH $PATH:/src:/src/vendor/bin

CMD ["apache2-foreground"]
