FROM php:5.6-cli
COPY . /usr/src/strimoid
WORKDIR /usr/src/strimoid
CMD [ "php", "-s", "public/index.php" ]
