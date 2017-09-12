<?php
declare(strict_types=1);
namespace davidjeddy\AutoGourcer;

/**
 * Class Git
 * @package davidjeddy\AutoGourcer
 */
class Git
{
    /**
     * @param string $uri
     * @param string $slug
     * @return bool
     * @throws \Exception
     */
    public function clone(string $uri, string $slug)
    {
        $command    = '';
        $errorCode  = 0;

        // check if folder exists
        if (!file_exists("/auto_gourcer/repos/{$slug}/")) {
            $command = "git clone {$uri} /auto_gourcer/repos/{$slug}  >> /auto_gourcer/logs/git.log";
        }

        if (file_exists("/auto_gourcer/repos/{$slug}/")) {
            // fetch all remote branch
            $command = "cd /auto_gourcer/repos/{$slug} && git fetch --all >> /auto_gourcer/logs/git.log && cd ../";

        }

        echo "Command: {$command}.\n";
        // fetch all remote branch
        exec($command, $responseData, $errorCode);

        if ($errorCode !== 0 ) {
            throw new \Exception($errorCode);
        }

        return true;
    }
}
