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
    public function transcodeAll(string $param = './renders', string $format = 'mp4'): bool
    {
        $dirFiles = \exec('find $param  -printf "%f\n" | grep $format');
        $dirFiles = \explode('\n', $dirFiles);

        foreach ($dirFiles as $key => $fileName) {
            // remove old file, rename new file to the old's value if transcoding is successful
            $this->transcode($fileName);
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

            if (!$errorCode) {
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
