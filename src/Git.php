<?php
declare(strict_types=1);
namespace dje\AutoGourcer;

use \Bitbucket\API\User\Repositories;
use \Bitbucket\API\Authentication\Basic;

/**
 * Class Git
 * @package davidjeddy\AutoGourcer
 */
class Git
{
    private $host;
    private $org;
    private $user;
    private $pass;
    public $repoData;

    /**
     * @return $this
     */
    public function getRepoList()
    {
        $returnData = '';

        if ($this->host === 'bitbucket.org') {
            $returnData = $this->getRepoListFromBitBucket();
        }

        if (empty($returnData)) {
            $returnData = $this->emptyHostResponse();
        }

        $this->setRepoData($returnData);

        return $this;
    }

    /**
     * @param string $uri
     * @param string $slug
     * @return Git
     */
    public function clone(string $slug): self
    {
        $command    = '';
        $errorCode  = 0;

        $uri = $this->buildHostURL();

        try {
            // check if folder exists
            if (!\file_exists("/auto_gourcer/repos/{$slug}/")) {
                $command = "git clone {$uri} /auto_gourcer/repos/{$slug} > /var/log/auto-gourcer/git.log";
            }

            if (\file_exists("/auto_gourcer/repos/{$slug}/")) {
                // fetch all remote branch
                $command = "cd /auto_gourcer/repos/{$slug} && git fetch --all /var/log/auto-gourcer/git.log && cd ../";
            }

            // TODO remove password so it does not show up in logs
            echo "Git command: {$command}.\n";

            // fetch all remote branch
            \exec($command, $responseData, $errorCode);
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
    public function checkout($path = null, $branch = null): self
    {
        if ($branch === null) {
            $branch = $this->checkoutLatestchanges($path);
        }

        echo ("Checking out branch {$branch} of repo {$path} .\n");
        \exec ("cd {$path} && git checkout {$branch} 2>> /var/log/auto-gourcer/git.log");

        return $this;
    }

    // private methods

    /**
     * This is a bit convoluted: Change to repo dir. print reflog, get hash of most recent entry, return hash string.
     *
     * @source https://gist.github.com/jasonrudolph/1810768
     * @fork https://gist.github.com/davidjeddy/2b5d9362fb0728a53977364b2a22ab44
     * @param string $path
     * @return string
     */
    private function checkoutLatestChanges(string $path): string
    {
        \exec ('cd ' . $path . ' && for branch in `git branch -r | grep -v HEAD`;do echo -e `git show --format="%ci %cr" $branch | head -n 1` \\ $branch; done | sort -r', $responseData, $errorCode);

        // parse the string and get the latest branch text
        $branch = $responseData[0];
        $branch = \explode(' ', $branch);
        $branch = $branch[\count($branch) - 1];
        $branch = \explode('/', $branch);
        $branch = $branch[\count($branch) - 1];

        return $branch;
    }

    /**
     * @return string
     */
    private function getRepoListFromBitBucket()
    {
        // the output of this if()... block should be a json string
        $bbr = new Repositories();
        $bbr->setCredentials(new Basic ($this->user, $this->pass));
        return $bbr->get()->getContent();
    }

    /**
     * @param string $repoFile
     * @return string
     */
    private function emptyHostResponse(string $repoFile = 'repos.json'): string
    {
        if (\file_exists("/var/log/auto-gourcer/{$repoFile}")) {
            // if no response from remote, use logged data
            return (string)\file_get_contents("/var/log/auto-gourcer/{$repoFile}");
        }

        return '';
    }

    /**
     * @param $paramData
     * @return array
     * @throws \Exception
     */
    private function jsonDecode($paramData): array
    {
        $paramData = \json_decode($paramData, true);

        if (\json_last_error()) {
            throw new \Exception(\json_last_error_msg());
        }

        return $paramData;
    }

    /**
     * @return string
     */
    private function buildHostURL(): string
    {
        if ($this->host === 'bitbucket.org') {
            // replace with .env values
            return "https://" .$this->user . ":" . $this->pass ."@" . $this->host . "/" . $this->org;
        }

        return '';
    }

    // getter/setter methods

    /**
     * @param $paramData
     * @return Git
     * @throws \Exception
     */
    public function setHost($paramData): self
    {
        if (!is_string($paramData)) {
            throw new \Exception('$this->host must be a string.');
        }

        $this->host = $paramData;

        return $this;
    }

    /**
     * @param $paramData
     * @return Git
     * @throws \Exception
     */
    public function setOrg($paramData = ''): self
    {
        if (!is_string($paramData)) {
            throw new \Exception('Must be a string.');
        }

        if ($paramData !== null) {
            $this->org = $paramData . '/';
        }

        return $this;
    }

    /**
     * @param $paramData
     * @return Git
     * @throws \Exception
     */
    public function setUser($paramData): self
    {
        if (!is_string($paramData)) {
            throw new \Exception('$this->user must be a string.');
        }

        $this->user = $paramData;

        return $this;
    }

    /**
     * @param $paramData
     * @return Git
     * @throws \Exception
     */
    public function setPass($paramData = null): self
    {
        if (!is_string($paramData) || $paramData === null ) {
            throw new \Exception('$this->pass must be a string.');
        }

        $this->pass = $paramData;

        return $this;
    }

    /**
     * @param string $paramData
     * @return Git
     * @throws \Exception
     */
    public function setRepoData(string $paramData): self
    {
        try {
            $paramData = $this->jsonDecode($paramData);
            \file_put_contents("/var/log/auto-gourcer/repos.json", \json_encode($this->repoData));
            \usort($paramData, "sortInReverseOrder");
            $this->repoData = $paramData;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }
}
