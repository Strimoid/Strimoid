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

ENV PATH $PATH:/src:/src/vendor/bin
WORKDIR /src

RUN a2enmod rewrite
COPY config/docker/apache.conf $APACHE_CONFDIR/sites-available/000-default.conf

COPY . /src
COPY --from=assets /src/public/assets /src/public/assets

RUN composer install --no-interaction --no-progress

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
