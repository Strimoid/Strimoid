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
FROM quay.io/strimoid/php:8.5

ENV CADDY_GLOBAL_OPTIONS "auto_https off"
ENV SERVER_NAME :80
ENV PATH $PATH:/app:/app/vendor/bin

COPY config/docker/php/prod.ini /usr/local/etc/php/conf.d/custom.ini

COPY . /app
COPY --from=assets /src/public/assets /app/public/assets

RUN composer install --no-interaction --no-progress
