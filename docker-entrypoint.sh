#!/bin/bash
set -e

! chown -R www-data:www-data /src/bootstrap /src/storage/app /src/storage/framework

exec dockerize -timeout 3m "$@"
