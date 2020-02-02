#!/usr/bin/env bash

function composer {
    local DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" > /dev/null && pwd )"
    local IMAGE="docker.gitlab.libeo.com/docker/php:7.2-composer"

    mkdir -p "${DIR}"/../../.composer
    docker pull "$IMAGE" &> /dev/null
    docker run --rm -u $(id -u):$(id -g) \
        -v "${DIR}"/../../.composer:/.composer \
        -v "${DIR}"/../../:/src \
        -w /src \
        -it "$IMAGE" "$@"
}
