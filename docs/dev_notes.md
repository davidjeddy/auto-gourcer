Working command/s:

xvfb-run -a -s '-screen 0 1920x1080x24' \
gource --default-user-image './avatars/Default.jpg' --user-image-dir './avatars/' --path '/auto-gourcer/repos/hotels/' --start-date '2017-12-01' --viewport '1920x1080' --output-ppm-stream - | \
ffmpeg -y -r 30 -f image2pipe -vcodec ppm -i - -vcodec libx264 -preset ultrafast -pix_fmt yuv420p -crf 1 -threads 0 -bf 0 /auto-gourcer/renders/hotels.mp4
