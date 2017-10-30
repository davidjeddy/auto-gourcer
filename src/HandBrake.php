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
     * @param string $param
     * @param string $format
     * @return bool
     */
    public function transcodeAll(string $param = './renders/', string $format = 'mp4'): bool
    {
        $dirFiles = scandir($param);

        foreach ($dirFiles as $key => $fileName) {
            if (substring($fileName, 0, -3) === $format) {
                // transcode only files of the specified format

                $this->transcode($fileName);
            }
        }

        return true;
    }

    /**
     * @param string $param
     * @return bool
     */
    public function transcode(string $param = ''): bool
    {
        try {
            $command = "HandBrakeCLI -i $param -e x264 -q 15 -o transcoded_$param";

            echo "Transcoding rendered video...\n";

            \exec($command, $returnData, $errorCode);

            if ($errorCode === 0) {
                unlink($param);
                rename("transcoded_$param", $param);
            }

            return true;
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }

        return true;
    }
}
