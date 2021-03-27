#!/bin/sh
chown -R www-data:www-data /src/bootstrap /src/storage/app /src/storage/framework
exec "$@"
