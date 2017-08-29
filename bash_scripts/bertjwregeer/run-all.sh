#!/usr/bin/env sh

cd ~gource

tmux has-session -t "gource"

if [ $? -eq 0 ];
then
    echo Attaching to current session
else
    tmux new-session -d -s "gource" "tcsh"
    tmux new-window -d -t gource:1 "Xvfb $DISPLAY -screen 0 1280x720x24 -ac"
fi

tmux new-window -d -t gource:2 "./bin/gource-run.sh ./repositories/ ./output/"