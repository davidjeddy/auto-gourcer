<?php
declare(strict_types=1);
namespace dje\AutoGourcer;

class AutoGourcer
{
    /**
     * @var string
     */
    protected $basePath = '/auto_gourcer';

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
     * @return $this
     */
    public function run()
    {
        $this->createLogDir();

        $repoUrl = '';

        $this->gitClass->getRepoList();

        for ($i = 0; $i < $this->repoCount; $i++) {
            if ($i > $this->repoCount) {
                break;
            }

            $repoSlug = $this->gitClass->repoData[$i]['slug'];

            // Git commands
            $this->gitClass->clone($repoSlug);
            $this->gitClass->checkout("{$this->basePath}/repos/{$repoSlug}");

            // Gourcer commands
            $this->gourceClass->setSlug($repoSlug);
            $this->gourceClass->render("{$this->basePath}/renders/{$repoSlug}.mp4");
        }

        return $this;
    }

    // getter and setters

    /**
     * @param Git $class
     * @return $this
     * @throws \Exception
     */
    public function setGit(\dje\AutoGourcer\Git $class)
    {
        if (!$class instanceof \dje\AutoGourcer\Git) {
            throw new \Exception('Git dependency must inherit from class \dje\AutoGourcer\Git');
        }

        $this->gitClass = $class;

        return $this;
    }

    /**
     * @param int $param
     * @return AutoGourcer
     */
    public function setCount(int $param): self
    {
        $this->repoCount = $param;

        return $this;
    }

    /**
     * @param Gource $class
     * @return $this
     * @throws \Exception
     */
    public function setGource(\dje\AutoGourcer\Gource $class)
    {
        if (!$class instanceof \dje\AutoGourcer\Gource) {
            throw new \Exception('Gource dependency must inherit from class \dje\AutoGourcer\Gource');
        }

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
        if (!file_exists('/var/log/auto-gourcer')) {
            exec('mkdir /var/log/auto-gourcer');
        }
    }
}
