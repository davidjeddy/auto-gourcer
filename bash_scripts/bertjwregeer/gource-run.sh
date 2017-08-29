#!/usr/bin/env sh

# $1 path to repositories
# $2 path to output

LOCATION=`dirname $0`

gource_custom()
{
    gource -1280x720 -a 0.1 -s 0.5 --key --colour-images -r 30 --highlight-users --log-format custom --highlight-dirs --path $1 -r 30 -o - | ffmpeg -y -r 30 -f image2pipe -vcodec ppm -i - -vcodec libx264 -preset ultrafast -crf 1 -threads 0 -b 0 $2
}

gource_path()
{
    gource -1280x720 -a 0.2 -s 1 --key --colour-images -r 30 --highlight-users --highlight-dirs --path $1 -r 30 -o - | ffmpeg -y -r 30 -f image2pipe -vcodec ppm -i - -vcodec libx264 -preset ultrafast -crf 1 -threads 0 -b 0 $2
}


if [ $# -ne 2 ];
then
    echo "Not enough parameters."
    exit 1
fi

if [ ! -d $1 ];
then
    echo "Path to repositories is not a directory ..."
    exit 2
fi

if [ ! -d $2 ];
then
    mkdir $2

    if [ ! -d $2 ];
    then
        echo "Error! Unable to create output directory."
        exit 3
    fi
fi

echo Creating multiple repositories output
$LOCATION/gource-genmulti.sh $1 $2/combined.gource

echo Running gource and outputting to $2/combined.mp4

#gource_custom $2/combined.gource $2/combined.mp4

echo Completed combined video

echo Creating individual repositories

for GITREPODIR in $1/*; do
    if [ -d $GITREPODIR ];
    then
        if [ ! -d $GITREPODIR/.git ];
        then
            continue
        fi

        NAME=`basename $GITREPODIR`
        echo Running gource and outputting to $2/$NAME.mp4
        gource_path $GITREPODIR $2/$NAME.mp4
    fi
done

echo Completed all repositories