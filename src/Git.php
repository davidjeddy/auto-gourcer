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
    /**
     * @var string
     */
    private $host = 'bitbucket.org';

    /**
     * @var
     */
    private $org;

    /**
     * @var
     */
    private $user;

    /**
     * @var
     */
    private $pass;

    /**
     * @var
     */
    public $repoData;

    /**
     * @var string
     */
    private $logDir = '/var/log';

    /**
     * @return $this
     * @throws \Exception
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
            if (!\file_exists("./repos/{$slug}/")) {
                $command = "git clone {$uri}{$slug} ./repos/{$slug} 2>> {$this->logDir}/auto-gourcer/git.log";
            }

            if (\file_exists("./repos/{$slug}/")) {
                // fetch all remote branch
                $command = "cd ./repos/{$slug} && git fetch --all 2>> {$this->logDir}/auto-gourcer/git.log && cd ../";
            }

            // fetch all remote branch
            \exec($command, $responseData, $errorCode);
        } catch (\Throwable $e) {
            echo $e->getMessage() . "\n";
        }

        return $this;
    }

    /**
     * @param $path
     * @param null $branch
     * @return Git
     * @throws \Exception
     */
    public function checkout($path, $branch = null): self
    {
        if ($branch === null) {
            $branch = $this->checkoutLatestchanges($path);
        }

        \exec ("cd {$path} && git checkout {$branch} 2>> {$this->logDir}/auto-gourcer/git.log");

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
     * @throws \Exception
     */
    private function checkoutLatestChanges(string $path): string
    {
        if (!file_exists($path)) {
            throw new \Exception("While attempting to checkout repository at {$path}; no path was found.");
        }

        \exec ('cd ' . $path . ' && for branch in `git branch -r | grep -v HEAD`;do echo -e `git show --format="%ci %cr" $branch | head -n 1` \\ $branch; done | sort -r', $responseData, $errorCode);

        $branch = $this->outputToBranchName($responseData);

        return $branch;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getRepoListFromBitBucket()
    {
        $returnData = '';

        try {
            // the output of this if()... block should be a json string
            $bbr = new Repositories();
            $bbr->setCredentials(new Basic ($this->user, $this->pass));
            $returnData = $bbr->get()->getContent();

            if (empty($returnData)) {
                throw new \Exception('No valid response from ' . $this->host . '. Most likely cause is invalid credentials.');
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $returnData;

    }

    /**
     * @param string $repoFile
     * @return string
     */
    private function emptyHostResponse(string $repoFile = 'repos.json'): string
    {
        if (\file_exists("{$this->logDir}/auto-gourcer/{$repoFile}")) {
            // if no response from remote, use logged data
            return (string)\file_get_contents("{$this->logDir}/auto-gourcer/{$repoFile}");
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
        try {
            $paramData = \json_decode($paramData, true);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $paramData;
    }

    /**
     * @return string
     */
    private function buildHostURL(): string
    {
        $returnData = '';

        if ($this->host === 'bitbucket.org') {
            $returnData = "https://" .$this->user . ":" . $this->pass ."@" . $this->host . "/"
                . ($this->org ? $this->org . '/' : null);

        }

        return $returnData;
    }

    /**
     * @param array $paramData
     * @return string
     */
    private function outputToBranchName(array $paramData): string
    {
        // parse the string and get the latest branch text
        $branch = $paramData[0];
        $branch = \explode(' ', $branch);
        $branch = $branch[\count($branch) - 1];
        $branch = \explode('/', $branch);
        $branch = $branch[\count($branch) - 1];

        return $branch;
    }
    // getter/setter methods

    /**
     * @param string $paramData
     * @return Git
     */
    public function setHost(string $paramData): self
    {
        $this->host = $paramData;

        return $this;
    }

    /**
     * @param string $paramData
     * @return Git
     * @throws \Exception
     */
    public function setOrg(string $paramData): self
    {
        $this->org = $paramData;

        return $this;
    }

    /**
     * @param string $paramData
     * @return Git
     */
    public function setUser(string $paramData): self
    {
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
     */
    public function setRepoData(string $paramData): self
    {
        try {
            $paramData = $this->jsonDecode($paramData);
            \file_put_contents("{$this->logDir}/auto-gourcer/repos.json", \json_encode($this->repoData));
            \usort($paramData, function ($a, $b) {
                return ($a['utc_last_updated'] <=> $b['utc_last_updated']);
            });
            $this->repoData = $paramData;
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $this;
    }
}
