FROM quay.io/strimoid/php

EXPOSE 8000
VOLUME /src/storage

# Copy source files
COPY . /src
WORKDIR /src

# Install Composer dependencies
RUN composer install -n

# Environment variables
ENV PATH $PATH:/src:/src/vendor/bin

COPY ./docker-entrypoint.sh /
ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["artisan", "serve", "--host", "0.0.0.0"]
