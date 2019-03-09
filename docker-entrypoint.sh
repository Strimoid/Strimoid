#!/bin/bash
set -e

chown -R www-data:www-data /src/storage

exec dockerize \
    -timeout 3m \
    "$@"
