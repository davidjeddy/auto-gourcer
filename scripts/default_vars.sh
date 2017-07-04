#!/usr/bin/env bash

# export default values
if [ !${FPS} ]
then
    export FPS=60
fi

if [ !${OUTPUT_PATH} ]
then
    export OUTPUT_PATH=/output
fi

if [ !${REPO_PATH} ]
then
    export REPO_PATH=/repos
fi

if [ !${REPO_NAME} ]
then
    export REPO_NAME=naabs
fi

if [ !${SCREEN_RES} ]
then
    export SCREEN_RES=1920x1080x24
fi

if [ !${START_DATE} ]
then
    export START_DATE=2014-01-14\ 18:00:00
fi

if [ !${STOP_DATE} ]
then
    export STOP_DATE=2014-01-16\ 19:00:00
fi