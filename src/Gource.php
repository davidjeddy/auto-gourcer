<?php
declare(strict_types=1);
namespace davidjeddy\AutoGourcer;

/**
 * Class Gource
 * @package davidjeddy\AutoGourcer
 */
class Gource
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function render()
    {
        $command = "\
xvfb-run -a -s -screen 0 480x360 gource \
    --background-image /auto_gourcer/background/background.png \
    --start-date 2017-09-01 \
    --path /auto_gourcer/repo/core \
    --user-image-dir /auto_gourcer/avatars/ \
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
    -r 15 \
    -f image2pipe \
    -vcodec ppm \
    -i - \
    -vcodec libx264 \
    -preset ultrafast \
    -pix_fmt yuv420p \
    -crf 1 \
    -threads 0 \
    -bf 0 /auto_gourcer/renders/core.mp4";

        exec($command, $returnData, $errorCode);

        if ($errorCode !== 0 ) {
            exit($errorCode);
        }

        return true;
    }
}
