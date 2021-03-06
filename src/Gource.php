<?php
declare(strict_types=1);
namespace dje\AutoGourcer;

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
    private $slug = '';

    // Gource CLI options

    /**
     * @var int
     */
    private $frameRate = 30;

    /**
     * @var string
     */
    private $basePath = '/auto-gourcer';

    /**
     * @var string
     */
    private $resolution = '1920x1080';

    /**
     * @var string
     */
    private $startDate = '2017-09-01';

    /**
     * @var string
     */
    private $logDir = '/var/log';

    /**
     * Do not re-render the repo video if the render is less than X seconds old. Default is 200s short of a day
     *
     * @param string $filePath
     * @param int $secondsAgo
     * @return bool
     */
    public function doesNewRenderExist(string $filePath , int $secondsAgo = 7000): bool
    {
        echo "Does {$filePath} already exist? ";

        if (\file_exists($filePath) && \filemtime($filePath) > (\time() - $secondsAgo)) {
            echo "Yes. Not rendering new output for now.\n";
            return true;
        }

        echo "No.\n";
        return false;
    }

    /**
     * @param string $outputFilePath
     * @param string|null $xvfb
     * @param string|null $gource
     * @param string|null $ffmpeg
     * @return bool
     */
    public function render(string $outputFilePath, string $xvfb =  null, string $gource =  null, string $ffmpeg =  null)
    {
        if ($this->doesNewRenderExist($outputFilePath)) {
            return true;
        }

        if ($xvfb === null) {
            $xvfb = "-a -s '-screen 0 {$this->resolution}x24' ";
        }

        if ($gource === null) {
            $gource = "--path '{$this->basePath}/repos/{$this->slug}/' \
            --start-date '{$this->startDate}' \
            --viewport '{$this->resolution}' \
            --output-framerate {$this->frameRate} \
            --background 000000 \
            --output-ppm-stream - ";
        }

        if (file_exists("{$this->basePath}/avatars/Default.jpg")) {
            $gource .= "\
            --default-user-image '{$this->basePath}/avatars/Default.jpg' \
            --user-image-dir '{$this->basePath}/avatars/'";
        }

        if ($ffmpeg === null) {
            $ffmpeg = "-y -r {$this->frameRate} -f image2pipe -vcodec ppm -i - -vcodec libx264 -preset ultrafast \
            -pix_fmt yuv420p -crf 1 -threads 0 -bf 0 {$this->basePath}/renders/{$this->slug}.mp4 ";
        }

        $command = "xvfb-run {$xvfb}gource {$gource}| ffmpeg {$ffmpeg}2>> {$this->logDir}/auto-gourcer/gource.log";

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
            return false;
        }

        return true;
    }

    // getter/setters

    /**
     * @param string $param
     * @return Gource
     */
    public function setBasePath(string $param): self
    {
        $this->basePath = $param;

        return $this;
    }

    /**
     * @param int $param
     * @return Gource
     */
    public function setFramerate(int $param): self
    {
        $this->frameRate = $param;

        return $this;
    }

    /**
     * @param string $param
     * @return Gource
     */
    public function setResolution(string $param): self
    {
        $this->resolution = $param;

        return $this;
    }

    /**
     * @param string $param
     * @return Gource
     */
    public function setSlug(string $param): self
    {
        $this->slug = $param;

        return $this;
    }

    /**
     * @param string $param
     * @return Gource
     */
    public function setStartDate(string $param): self
    {
        $this->startDate = $param;

        return $this;
    }
}
