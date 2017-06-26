#!/usr/bin/env bash

# export default values
export OUTPUT_PATH=/output
export PATH_BASE=/auto_gourcer
export REPO_PATH=/repos
export REPO_NAME=naabs
export SCREEN_RES=1920x1080x24
export START_DATE=2014-01-14\ 18:00:00
export STOP_DATE=2014-01-16\ 19:00:00
export FPS=60

exec_gource()
{
    # --background-image ${PATH_BASE}/background/background.png \
    xvfb-run -a -s "-screen 0 ${SCREEN_RES}" gource \
    --start-date "${START_DATE}" \
    --stop-date "${STOP_DATE}" \
    --path ${PATH_BASE}${REPO_PATH} \
    --user-image-dir ${PATH_BASE}/avatars/ \
    --seconds-per-day 0.5 \
    --max-user-speed 50 \
    --hash-seed 42 \
    --auto-skip-seconds 300 \
    --frameless \
    --hide mouse,filenames,progress \
    --fullscreen \
    --output-ppm-stream - \
    | ffmpeg \
    -y \
    -r ${FPS} \
    -f image2pipe \
    -vcodec ppm \
    -i - \
    -vcodec libx264 \
    -preset ultrafast \
    -pix_fmt yuv420p \
    -crf 1 \
    -threads 0 \
    -bf 0 ${PATH_BASE}${OUTPUT_PATH}/${REPO_NAME}.mp4
    # add output encoding options here
}

exec_gource;