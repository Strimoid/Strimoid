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

EXPOSE 8000
VOLUME /src/storage

COPY ./docker-entrypoint.sh /
ENTRYPOINT ["docker-entrypoint.sh"]

WORKDIR /src
COPY . /src

COPY --from=assets /src/public/assets /src

RUN composer install -n
ENV PATH $PATH:/src:/src/vendor/bin

CMD ["artisan", "serve", "--host", "0.0.0.0"]
