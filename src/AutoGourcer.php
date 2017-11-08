<?php
declare(strict_types=1);
namespace dje\AutoGourcer;

/**
 * Class AutoGourcer
 * @package dje\AutoGourcer
 */
class AutoGourcer
{
    /**
     * @var string
     */
    protected $basePath = '/auto-gourcer';

    /**
     * @var int
     */
    private $repoCount = 1;

    /**
     * @var \dje\AutoGourcer\Git
     */
    private $gitClass;

    /**
     * @var \dje\AutoGourcer\Gource
     */
    private $gourceClass;

    /**
     * @var string
     */
    private $logDir = '/var/log';

    /**
     * @return $this
     * @throws \Exception
     */
    public function run()
    {
        $this->createLogDir();

        $this->gitClass->getRepoList();

        for ($i = 0; $i < $this->repoCount; $i++) {
            $repoSlug = $this->gitClass->repoData[$i]['slug'];

            // Git commands
            $this->gitClass->getRepoData($repoSlug)->checkout("{$this->basePath}/repos/{$repoSlug}");

            // Gourcer commands
            $this->gourceClass->setSlug($repoSlug)->render("{$this->basePath}/renders/{$repoSlug}.mp4");
        }

        return $this;
    }

    // getter and setters

    /**
     * @param Git $class
     * @return $this
     */
    public function setGit(Git $class)
    {
        $this->gitClass = $class;

        return $this;
    }

    /**
     * @param int $param
     * @return AutoGourcer
     */
    public function setRepoCount(int $param): self
    {
        $this->repoCount = $param;

        return $this;
    }

    /**
     * @param Gource $class
     * @return $this
     */
    public function setGource(Gource $class)
    {
        $this->gourceClass = $class;

        return $this;
    }

    // private methods

    /**
     *
     */
    private function createLogDir()
    {
        // if log dir does not exist, create it
        if (!file_exists("{$this->logDir}/auto-gourcer")) {
            mkdir("{$this->logDir}/auto-gourcer");
        }
    }
}
