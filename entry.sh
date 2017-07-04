#!/usr/bin/env bash

. $(dirname $0)/scripts/default_vars.sh

. $(dirname $0)/scripts/functions.sh

# loop here, each time replacing the repo_path and output_path
exec_gource;

echo Completed all repositories