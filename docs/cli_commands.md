Working command/s:

xvfb-run -a -s '-screen 0 1920x1080x24' \
gource \
    --default-user-image './avatars/Default.jpg' \
    --user-image-dir './avatars/' \
    --path '/auto-gourcer/repos/hotels/' \
    --start-date '2017-12-01' \
    --viewport '1920x1080' \
    --output-ppm-stream - | \
ffmpeg -y -r 30 -f image2pipe -vcodec ppm -i - -vcodec libx264 -preset ultrafast -pix_fmt yuv420p -crf 1 -threads 0 -bf 0 /auto-gourcer/renders/hotels.mp4

#artworks made by Bai Yemeng. The series is Mafia, the Brigade of Knowledge Seekers

Working
xvfb-run -a -s '-screen 0 1920x1080x24' gource \
            --background 000000 \
            --path './repos/core/' \
            --output-framerate 30 \
            --output-ppm-stream - \
            --start-date '2017-12-07'\
            --default-user-image './avatars/Default.jpg' \
            --viewport '1920x1080'\
            --user-image-dir './avatars/' | ffmpeg -y -r 30 -f image2pipe -vcodec ppm -i - -vcodec libx264 \
            -preset ultrafast -pix_fmt yuv420p -crf 1 -threads 0 -bf 0 ./renders/core.mp4

REPO=./viking-signage-webapp
xvfb-run -a -s '-screen 0 1920x1080x24' \

REPO=./webcli
gource \
    --auto-skip-seconds 1 \
    --background 000000 \
    --default-user-image './avatars/Default.jpg' \
    --key \
    --output-ppm-stream - \
    --output-framerate 30 \
    --path ${REPO} \
    --start-date '2017-01-01' \
    --seconds-per-day 2.4 \
    --stop-date '2017-12-31' \
    --title ${REPO} \
    --user-image-dir './avatars/' \
    --viewport '1920x1080' | \
ffmpeg -y -r 30 -f image2pipe -vcodec ppm -i - -vcodec libx264 -preset ultrafast -pix_fmt yuv420p -crf 1 -threads 0 -bf 0 ./${REPO}.mp4


ls -a
shopt -s dotglob
shopt -s nullglob
array=(*/)
for dir in "${array[@]}"; do echo "$dir"; done
for dir in */; do
    echo "Starting rendering of ${dir} project.";
    REPO=./${dir}
    gource \
        --auto-skip-seconds 1 \
        --background 000000 \
        --default-user-image './../avatars/Default.jpg' \
        --key \
        --output-ppm-stream - \
        --output-framerate 30 \
        --path ${REPO} \
        --start-date '2017-01-01' \
        --seconds-per-day 2.4 \
        --stop-date '2017-12-31' \
        --title ${dir} \
        --user-image-dir './../avatars/' \
        --viewport '1920x1080' | \
    ffmpeg -y -r 30 -f image2pipe -vcodec ppm -i - -vcodec libx264 -preset ultrafast -pix_fmt yuv420p -crf 1 -threads 0 -bf 0 ./${dir}.mp4
    HandBrakeCLI -i ./${dir}.mp4 -e x264 -q 15 -o ./_${dir}.mp4
    rm ${dir}.mp4
    mv _${dir}.mp4 ${dir}.mp4
    echo "...completed.\n";
done
