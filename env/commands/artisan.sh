#!/usr/bin/env bash

function artisan {
    local DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" > /dev/null && pwd )"

    docker exec -u app \
        -w /var/www \
        -it statut_php-fpm php artisan "$@"
}
