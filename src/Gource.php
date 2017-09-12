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
     * @var bool
     */
    public $dryRun = false;

    /**
     * @var string
     */
    public $slug = 'm3-api';

    /**
     * @var int
     */
    public $frameRate = 30;

    /**
     * @var string
     */
    public $resolution = '640x480';

    /**
     * @var string
     */
    public $output = './test.mp4';

    /**
     * @var string
     */
    public $repoPath = './';

    /**
     * @var string
     */
    public $basePath = '/auto_gourcer';

    /**
     * @var string
     */
    public $startDate = '2017-09-05';

    /**
     * @param string $xvfb
     * @param string $gource
     * @param string $ffmpeg
     * @return bool
     */
    public function render(string $xvfb =  null, string $gource =  null, string $ffmpeg =  null)
    {
        // todo abstract these three parts into classes
        if ($xvfb === null) {
            $xvfb = "-a -s '-screen 0 {$this->resolution}x24'";
        }

        if ($gource === null) {
            $gource = "--path '{$this->basePath}/repos/{$this->slug}/' --user-image-dir '{$this->basePath}/avatars/' --start-date '{$this->startDate}' --viewport '{$this->resolution}' --output-framerate {$this->frameRate} --output-ppm-stream - ";
        }

        if ($ffmpeg === null) {
            $ffmpeg = "-y -r {$this->frameRate} -f image2pipe -vcodec ppm -i - -vcodec libx264 -preset ultrafast -pix_fmt yuv420p -crf 1 -threads 0 -bf 0 {$this->basePath}/renders/{$this->slug}.mp4";
        }

        $command = "xvfb-run {$xvfb} gource {$gource} | ffmpeg {$ffmpeg} >> {$this->basePath}/logs/rendering.log";

        echo ("Running rendering command `{$command}`.\n");

        //exec($command, $returnData, $errorCode);

        return true;
    }
}
