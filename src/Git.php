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
     * @return Git
     */
    public function clone(string $uri, string $slug): self
    {
        $command    = '';

        try {
            // check if folder exists
            if (!file_exists("/auto_gourcer/repos/{$slug}/")) {
                $command = "git clone {$uri} /auto_gourcer/repos/{$slug} > /auto_gourcer/logs/git.log";
            }

            if (file_exists("/auto_gourcer/repos/{$slug}/")) {
                // fetch all remote branch
                $command = "cd /auto_gourcer/repos/{$slug} && git fetch --all > /auto_gourcer/logs/git.log && cd ../";
            }

            echo "Git command: {$command}.\n";
            // fetch all remote branch
            exec($command, $responseData, $errorCode);

            if ($errorCode !== 0) {
                throw new \Exception('Error code {$errorCode} returned.');
            }

        } catch (\Throwable $e) {
            echo $e->getMessage() . "\n";
        }

        return $this;
    }

    /**
     * @param null $path
     * @param string $branch
     * @return Git
     */
    public function checkout($path = null, $branch = 'latest'): self
    {
        if ($branch === 'latest') {
            // @source https://gist.github.com/jasonrudolph/1810768
            exec ('cd ' . $path . ' && for branch in `git branch -r | grep -v HEAD`;do echo -e `git show --format="%ci %cr" $branch | head -n 1` \\ $branch; done | sort -r', $responseData, $errorCode);

            // parse the string and get the latest branch text
            $branch = $responseData[0];
            $branch = explode(' ', $branch);
            $branch = $branch[count($branch) - 1];
            $branch = explode('/', $branch);
            $branch = $branch[count($branch) - 1];
        }

        // exec git command
        echo ("Checking out branch {$branch} of repo {$path} .\n");
        exec ("cd {$path} && git checkout {$branch} 2>> /auto_gourcer/logs/git.log");

        return $this;
    }
}