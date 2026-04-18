<?php
declare(strict_types=1);
namespace davidjeddy\AutoGourcer;

class HandBrake
{
    /**
     * Use handbrake, if installed, to trans-code the ffmpeg mp4 to a h254 (default)
     * @param string $source
     * @param string $options
     */
    public function transCode(string $source, string $output, string $options = "-e x264 -q 20 -B 160")
    {
        \exec("HandBrakeCLI -i {$source} -o {$output} {$options}");
    }
}