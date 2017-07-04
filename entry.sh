#!/usr/bin/env bash

. $(dirname $0)/scripts/default_vars.sh

. $(dirname $0)/scripts/functions.sh

echo "Repos: ${REPO_PATH}";

# loop each dir in ./repos
# stuck here, need to get a list of the dirs as an array and iterate the array elements
for PRJ_PATH in ${REPO_PATH}; do

    echo "Project: ${PRJ_PATH}";

    # loop each dir of PRJ_PATH...
    for GITREPODIR in ${PRJ_PATH}/*; do

        # is the current dir contains a ./.git ?
        if [ -d $GITREPODIR ];
        then
            if [ ! -d $GITREPODIR/.git ];
            then
                continue
            fi

            # SET REPO_NAME
            REPO_NAME=`basename ${GITREPODIR}`

            # EXEC VIDEO OUTPUT
            exec_gource;
        fi
    done

done

echo Completed all repositories