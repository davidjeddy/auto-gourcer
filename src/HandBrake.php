<?php
declare(strict_types=1);
namespace dje\AutoGourcer;

/**
 * Class HandBrake
 * @package dje\AutoGourcer
 */
class HandBrake
{

    /**
     * @var
     */
    public $path = './renders';

    /**
     * @var
     */
    public $file = '';

    /**
     * @var
     */
    public $ext = 'mp4';

    /**
     * @return bool
     * @throws \Exception
     */
    public function transcodeAll(): bool
    {
        $dirFiles = glob("$this->path/*.mp4");
        foreach ($dirFiles as $filePathName) {
            // remove old file, rename new file to the old's value if transcoding is successful
            $this->transcode($filePathName);
        }

        return true;
    }

    /**
     * @param string $source
     * @return bool
     * @throws \Exception
     */
    public function transcode(string $source): bool
    {
        echo "Transcoding rendered $source video...";

        try {
            $command = "HandBrakeCLI -i $source -e x264 -q 15 -o {$source}_";

            \exec($command, $returnData, $errorCode);
            \unlink($source);
            \rename("{$source}_", "$source");
        } catch (\Exception $e) {
            throw $e;
        }

        echo "completed.\n";

        return true;
    }
}
