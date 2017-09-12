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
    public $startDate = '2017-09-01';

    /**
     * Do not re-render the repo video if the render is less than X seconds old
     *
     * @param string $filePath
     * @param int $secondsAgo
     * @return bool
     */
    public function doesNewRenderExist(string $filePath = null, int $secondsAgo = 7200): bool
    {
        if ($filePath === null) {
            $filePath = $this->basePath . '/renders/' . $this->slug . '.mp4';
        }

        if (file_exists($filePath) && filemtime($filePath) > (time() - $secondsAgo)) {
            echo "New render exists, will not render.\n";
            return true;
        }

        return false;
    }

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

        $command = "xvfb-run {$xvfb} gource {$gource} | ffmpeg {$ffmpeg} 2>> {$this->basePath}/logs/gource.log";

        echo ("Running rendering command `{$command}`.\n");

        if ($this->dryRun === true) {
            echo "`dryRun` set to true, will not render output.";
            return true;
        }

        exec($command, $returnData, $errorCode);

        // remove file if rendering fails
        if ($errorCode !== 0) {
            exec("rm {$this->basePath}/renders/{$this->slug}.mp4");
        }

        return true;
    }
}
