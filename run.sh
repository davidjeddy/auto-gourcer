xvfb-run -a -s "-screen 0 480x360x24" gource \
--start-date "2014-01-14 18:00:00" \
--stop-date "2014-01-15 24:00:00" \
--frameless \
--hide mouse,filenames,progress \
--seconds-per-day 0.5 \
--max-user-speed 100 \
--hash-seed 42 \
--auto-skip-seconds 30 \
--path ./repos/naabs/ -f -o - | \
ffmpeg -y -r 60 -f image2pipe -vcodec ppm -i - -vcodec libx264 -preset ultrafast -pix_fmt yuv420p -crf 1 -threads 0 -bf 0 test.mp4
