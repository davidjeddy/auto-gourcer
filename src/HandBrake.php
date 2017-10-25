<?php

namespace dje\AutoGourcer;

/**
 * Class HandBrake
 * @package dje\AutoGourcer
 */
class HandBrake
{
    /**
     * @param string $param
     * @return bool
     */
    public function transcodeAll(string $param): bool
    {
        $dirFiles = $param;

        foreach ($dirFiles as $key => $value) {
            $value = \explode('.', $param);
            $value[0] = $value[0] . '-2';
            $value = \implode('.', $value);

            $this->transcode($value);
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
            $command = "HandBrakeCLI -i ./renders/funi-setup.mp4 -e x264 -q 15 -o ./renders/funi-setup-3.mp4";

            echo "Transcoding rendered video...\n";

            \exec($command, $returnData, $errorCode);

            return true;
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }

        return true;
    }
}
