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
    public $frameRate = null;

    /**
     * @var string
     */
    public $resolution = null;

    /**
     * @var string
     */
    public $basePath = null;

    /**
     * @var string
     */
    public $startDate = null;

    /**
     * Gource constructor.
     */
    public  function __construct()
    {
        // TODO read the .env values from GOURCE_*
        $this->startDate    = DATE('Y') . '-01-01';
        $this->resolution   = '1920x1080';
        $this->basePath     = '/auto_gourcer';
        $this->frameRate    = 60;
    }

    /**
     * Do not re-render the repo video if the render is less than X seconds old. Default is 200s short of a day
     *
     * @param string $filePath
     * @param int $secondsAgo
     * @return bool
     */
    public function doesNewRenderExist(string $filePath , int $secondsAgo = 7000): bool
    {
        echo __METHOD__ . " : Does {$filePath} already exist? ";

        if (\file_exists($filePath) && \filemtime($filePath) > (\time() - $secondsAgo)) {
            echo "Yes. Not rendering new output for now.\n";
            return true;
        }

        echo "No.\n";
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
            $gource = "--path '{$this->basePath}/repos/{$this->slug}/' --user-image-dir '{$this->basePath}/avatars/' --start-date '{$this->startDate}' --viewport '{$this->resolution}' --output-framerate {$this->frameRate} --default-user-image '{$this->basePath}/avatars/Default.jpg' --output-ppm-stream - ";
        }

        if ($ffmpeg === null) {
            $ffmpeg = "-y -r {$this->frameRate} -f image2pipe -vcodec ppm -i - -vcodec libx264 -preset ultrafast -pix_fmt yuv420p -crf 1 -threads 0 -bf 0 {$this->basePath}/renders/{$this->slug}.mp4";
        }

        $command = "xvfb-run {$xvfb} gource {$gource} | ffmpeg {$ffmpeg}"; // 2>> {$this->basePath}/logs/gource.log";

        echo ("Running rendering command `{$command}`.\n");

        if ($this->dryRun === true) {
            echo "`dryRun` set to true, will not render output.\n";
            return true;
        }

        echo "Executing rendering...\n";
        \exec($command, $returnData, $errorCode);

        // remove file if rendering fails
        if ($errorCode !== 0 && file_exists("{$this->basePath}/renders/{$this->slug}.mp4")) {
            \exec("rm {$this->basePath}/renders/{$this->slug}.mp4");
        }

        return true;
    }
}