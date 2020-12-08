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
FROM quay.io/strimoid/php:7.4

EXPOSE 80

ENV PATH $PATH:/src:/src/vendor/bin
WORKDIR /src

COPY config/docker/php/prod.ini /usr/local/etc/php/conf.d/custom.ini
COPY config/docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

COPY . /src
COPY --from=assets /src/public/assets /src/public/assets

RUN composer install --no-interaction --no-progress

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
