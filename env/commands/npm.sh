#!/usr/bin/env bash

function npm {
    local DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" > /dev/null && pwd )"
    local IMAGE="docker.gitlab.libeo.com/docker/node:8"

    mkdir -p "${DIR}"/../../.npm
    docker pull ${IMAGE} &> /dev/null
    docker run --rm -u $(id -u):$(id -g) \
        --entrypoint npm \
        -v "${DIR}"/../../:/src \
        -v "${DIR}"/../../.npm:/.npm \
        -w /src \
        -it ${IMAGE} "$@"
}
