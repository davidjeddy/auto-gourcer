<?php
declare(strict_types=1);
namespace dje\AutoGourcer;

use \Bitbucket\API\User\Repositories;
use \Bitbucket\API\Authentication\Basic;
use dje\AutoGourcer\RemoteHost\BaseHost;

/**
 * Class Git
 * @package davidjeddy\AutoGourcer
 */
class Git
{
    /**
     * @var
     */
    protected $remoteHost;

    /**
     * @var
     */
    public $repoData;

    /**
     * @var string
     */
    private $logDir = '/var/log';

    /**
     * @var string
     */
    private $repoDir = './repos';

    /**
     * @return $this
     * @throws \Exception
     */
    public function getRepoList()
    {
        $returnData = $this->getRepoListFromBitBucket();

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
        $uri = $this->buildHostURL();

        try {
            // default is to clone the repo...
            $command = "git clone {$uri}{$slug} {$this->repoDir}/{$slug}" . $this->logOutput();

            // ... but if the repo exists fetch all the branches instead.
            if (\file_exists("{$this->repoDir}/{$slug}/")) {
                $command = "cd {$this->repoDir}/{$slug} && git fetch --all" . $this->logOutput() . "&& cd ../";
            }

            // fetch all remote branch
            \exec($command, $responseData, $errorCode);

            if ($errorCode !== 0) {
                throw new \Exception('Clone/Fetch command failed with code ' . $errorCode);
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }

        return $this;
    }

    /**
     * @param $path
     * @param string $branch
     * @return Git
     * @throws \Exception
     */
    public function checkout($path, string $branch = ''): self
    {
        if ($branch === '') {
            $branch = $this->checkoutLatestchanges($path);
        }

        \exec ("cd {$path} && git checkout {$branch}" . $this->logOutput());

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
            $bbr->setCredentials(new Basic ($this->remoteHost->user, $this->remoteHost->pass));
            $returnData = $bbr->get()->getContent();

            if (empty($returnData)) {
                throw new \Exception('No valid response from ' . $this->remoteHost::HOST . '. Most likely cause is invalid credentials.');
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
            return (string)file_get_contents("{$this->logDir}/auto-gourcer/{$repoFile}");
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
        $returnData = $this->remoteHost->protocol . "://" .$this->remoteHost->user . ":" . $this->remoteHost->pass ."@"
            . $this->remoteHost::HOST . "/" . ($this->remoteHost->org ? $this->remoteHost->org . '/' : null);

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

    /**
     * @return string
     */
    private function logOutput(): string
    {
        return " 2>> {$this->logDir}/auto-gourcer/git.log";
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
                return ($a['utc_last_updated'] <= $b['utc_last_updated']) ? 1 : -1;
            });
            $this->repoData = $paramData;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $this;
    }

    // getter / setters

    /**
     * @return mixed
     */
    public function getRemoteHost()
    {
        return $this->remoteHost;
    }

    /**
     * @param BaseHost $param
     * @return Git
     */
    public function setRemoteHost(BaseHost $param): self
    {
        $this->remoteHost = $param;

        return $this;
    }
}
