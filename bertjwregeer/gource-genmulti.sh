#!/usr/bin/env sh

# $1 directory containing git sub-directories
# $2 output file

for GITREPODIR in $1/*; do
    if [ -d $GITREPODIR ];
    then
        if [ ! -d $GITREPODIR/.git ];
        then
            continue
        fi

        NAME=`basename $GITREPODIR`
        echo Generating logs for: $NAME from $GITREPODIR
        (cd $GITREPODIR; git pull) 2>&1 1>/dev/null
        `dirname $0`/gource-log.sh $GITREPODIR $NAME >> $2.tmp
    fi
done

echo "Sorting output file"

sort -u --output=$2 $2.tmp
rm -f $2.tmp

echo "Sorted."