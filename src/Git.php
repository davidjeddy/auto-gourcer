<?php
declare(strict_types=1);
namespace dje\AutoGourcer;

use \Bitbucket\API\User\Repositories;
use \Bitbucket\API\Authentication\Basic;
use dje\AutoGourcer\RemoteSources\BaseHost;

/**
 * Class Git
 * @package davidjeddy\AutoGourcer
 */
class Git
{
    /**
     * @var
     */
    private $org;

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
     * @class \RemoteSources\BaseHost
     */
    protected $hostClass = null;

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
     * This is a bit convoluted: Change to repo dir. print `git reflog`, get hash of most recent entry,
     * ... return commit hash string value.
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
        $returnData = "https://" .$this->user . ":" . $this->pass ."@" . $this->host . "/"
            . ($this->org ? $this->org . '/' : null);

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
        return "2>> {$this->logDir}/auto-gourcer/git.log";
    }

    // getter/setter methods

    /**
     * @param BaseHost $class
     * @return $this
     */
    public function setHostClass(BaseHost $class)
    {
        $this->hostClass = $class;

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
                return ($a['utc_last_updated'] <= $b['utc_last_updated']) ? 1 : -1;
            });
            $this->repoData = $paramData;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $this;
    }

    /**
     * @return BaseHost
     */
    public function getHostClass(): BaseHost
    {
        return $this->hostClass;
    }
}
