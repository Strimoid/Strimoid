#!/bin/bash
set -e

exec dockerize \
    -wait "tcp://$DB_HOST:$DB_PORT" \
    -wait "tcp://$REDIS_HOST:$REDIS_PORT" \
    -timeout 3m \
    "$@"
