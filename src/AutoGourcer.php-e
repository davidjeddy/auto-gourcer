<?php
declare(strict_types=1);
namespace davidjeddy\AutoGourcer;

include_once '/auto_gourcer/vendor/autoload.php';

use \Bitbucket\API\User\Repositories;
use \Bitbucket\API\Authentication\Basic;
use \Dotenv\Dotenv;
use \davidjeddy\AutoGourcer\Git;
use \davidjeddy\AutoGourcer\Gource;

class AutoGourcer
{
    /**
     * @var string
     */
    public $basePath = '/auto_gourcer';

    /**
     * @var string
     */
    private $host = '';

    /**
     * @var int
     */
    public $repoCount = 1;

    /**
     * @var array
     */
    private $repoData = [];

    /**
     * @var array
     */
    private $depLib = [];

    /**
     * AutoGourcer constructor.
     * @param array $configArray
     * @param \davidjeddy\AutoGourcer\Git $gitClass
     * @param \davidjeddy\AutoGourcer\Gource $gourceClass
     */
    public function __construct(
        $configArray = [],
        Git $gitClass,
        Gource $gourceClass
    ) {
        // load ENV handler
        $dotenv = new Dotenv($this->basePath);
        $dotenv->load();

        // class properties
        foreach ($configArray as $key => $value) {
            $this->{$key} = $value;
        }

        $this->host = \strtolower(getenv('HOST'));
        echo "Host is {$this->host}.\n";

        // dependant classes
        $this->depLib['Git']    = new $gitClass();
        $this->depLib['Gource'] = new $gourceClass();
    }

    /**
     *
     */
    public function run()
    {
        $repoUrl = '';

        $this->getRepoList();

        for ($i = 0; $i < $this->repoCount; $i++) {
            if ($i > $this->repoCount) {
                break;
            }

            $repoSlug = $this->repoData[$i]['slug'];

            if ($this->host === 'bitbucket.org') {
                // replace with .env values
                $repoUrl = "https://" . getenv('GITUSER') . ":" . getenv('GITPASS') ."@" .getenv('HOST') . "/" . getenv('ORG') . "/{$repoSlug}.git";
            }

            // Git commands
            $this->depLib['Git']->clone($repoUrl, $repoSlug);
            $this->depLib['Git']->checkout("{$this->basePath}/repos/{$repoSlug}");

            // Gourcer commands
            $this->depLib['Gource']->slug = $repoSlug;
            $this->depLib['Gource']->render("{$this->basePath}/renders/{$repoSlug}.mp4");
        }
    }

    /**
     * @return mixed
     */
    public function getRepoList()
    {
        // the output of this if()... block should be a json string
        if ($this->host === 'bitbucket.org') {
            $bbr = new Repositories();
            $bbr->setCredentials(new Basic (getenv('BBUSER'), getenv('BBPASS')));
            $this->repoData = $bbr->get()->getContent();
        }

        if (empty($this->repoData)) {
            $this->repoData = $this->emptyHostResponse();
        }

        return $this->jsonDecode()->sortArray();
    }

    // private methods

    /**
     * @param string $repoFile
     * @return bool|string
     */
    private function emptyHostResponse(string $repoFile = 'repos.json')
    {
        if (\file_exists("{$this->basePath}/logs/{$repoFile}")) {
            // if no response from remote, use logged data
            return \file_get_contents("{$this->basePath}/logs/{$repoFile}");
        }

        return '';
    }

    /**
     * @return AutoGourcer
     * @throws \Exception
     */
    private function jsonDecode(): self
    {
        $this->repoData = \json_decode($this->repoData, true);

        if (\json_last_error()) {
            throw new \Exception(\json_last_error_msg());
        }

        return $this;
    }

    /**
     * @return AutoGourcer
     * @throws \Exception
     */
    private function sortArray(): self
    {
        try {
            \usort($this->repoData, "sortInReverseOrder");
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }
}
