#!/bin/bash
set -e

exec dockerize \
    -wait "tcp://$DB_HOST:$DB_PORT" \
    -timeout 3m \
    "$@"
