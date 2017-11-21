#!/usr/bin/env bash
export PWD=$(pwd)

# docker pull davidjeddy/auto-gourcer

docker run \
    -v $PWD/repos:/auto-gourcer/repos \
    -v $PWD/renders:/auto-gourcer/renders \
    --name=auto_gourcer \
    --rm \
    'davidjeddy/auto-gourcer'

docker stop --rm auto_gourcer
