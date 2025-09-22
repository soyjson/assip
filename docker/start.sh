#!/bin/bash
# start php-fpm
php-fpm -D
# start nginx in foreground
nginx -g "daemon off;"
