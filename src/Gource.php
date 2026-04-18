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
    public $slug = '';

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
    public $basePath = '/auto_gourcer';

    /**
     * @var string
     */
    public $startDate = '2018-09-01';

    /**
     * Do not re-render the repo video if the render is less than X seconds old. Default is 200s short of a day
     *
     * @param string $filePath
     * @param int $secondsAgo
     * @return Gource
     */
    public function doesNewRenderExist(string $filePath , int $secondsAgo = 7000): self
    {
        echo __METHOD__ . " : Does {$filePath} already exist? ";

        if (\file_exists($filePath) && \filemtime($filePath) > (\time() - $secondsAgo)) {
            echo "Yes. Not rendering new output for now.\n";
            return $this;
        }

        echo "No. Rendering new video.\n";
        return $this;
    }

    /**
     * @param string $xvfb
     * @param string $gource
     * @param string $ffmpeg
     * @return self
     */
    public function render(string $xvfb =  null, string $gource =  null, string $ffmpeg =  null): self
    {
        // todo abstract these three parts into classes
        if ($xvfb === null) {
            $xvfb = "-a -s '-screen 0 {$this->resolution}x24'";
        }

        if ($gource === null) {
            $gource = "--path '{$this->basePath}/repos/{$this->slug}/' --user-image-dir '{$this->basePath}/avatars/' --start-date '{$this->startDate}' --viewport '{$this->resolution}' --output-framerate {$this->frameRate} --default-user-image '{$this->basePath}/avatars/Default.jpg' --output-ppm-stream - ";
        }

        if ($ffmpeg === null) {
            $ffmpeg = "-y -r {$this->frameRate} -f image2pipe -vcodec ppm -i - -vcodec libx264 -preset ultrafast -pix_fmt yuv420p -crf 1 -threads 0 -bf 0 {$this->basePath}/renders/{$this->slug}.mp4";
        }

        $command = "xvfb-run {$xvfb} gource {$gource} | ffmpeg {$ffmpeg}"; // 2>> {$this->basePath}/logs/gource.log";

        echo ("Running rendering command `{$command}`.\n");

        if ($this->dryRun === true) {
            echo "`dryRun` set to true, will not render output.\n";
            return $this;
        }

        echo "Executing rendering...\n";
        \exec($command, $returnData, $errorCode);

        // remove file if rendering fails
        if ($errorCode !== 0 && file_exists("{$this->basePath}/renders/{$this->slug}.mp4")) {
            \exec("rm {$this->basePath}/renders/{$this->slug}.mp4");
        }

        return $this;
    }
}