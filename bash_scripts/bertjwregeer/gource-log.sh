#!/usr/bin/env sh

# $1 is path to git directory
# $2 is project name

gource --output-custom-log - --path $1 | sed -E "s/\|([^|]+)$/\|\/$2\1/"