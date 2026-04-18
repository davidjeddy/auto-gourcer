<?php
declare(strict_types=1);
namespace davidjeddy\AutoGourcer;

include_once ('/auto_gourcer/vendor/autoload.php');
include_once ('/auto_gourcer/src/Git.php');
include_once ('/auto_gourcer/src/Gource.php');

class AutoGourcer
{
    /**
     * @var string
     */
    public $basePath = '/auto_gourcer';

    /**
     * @var array|false|string
     */
    private $host = '';

    /**
     * @var int
     */
    public $repoCount = 1;

    /**
     * @var array|\Buzz\Message\MessageInterface
     */
    private $repoData = [];

    /**
     * AutoGourcer constructor.
     * @param null $basePath
     */
    public function __construct($basePath = null)
    {
        $dotenv = new \Dotenv\Dotenv($this->basePath);
        $dotenv->load();
        $this->host = strtolower(getenv('HOST'));
    }

    /**
     * Execute the actual logic to get `stuff` done
     */
    public function run()
    {
        $repoUrl = '';

        $this->getRepoList();

        for ($i = 0; $i < $this->repoCount; $i++) {
            if ($i > $this->repoCount) {
                break;
            }

            echo "Host is {$this->host}.\n";

            if ($this->host === 'bitbucket.org') {
                // replace with .env values
                $repoUrl = "https://" . getenv('GITUSER') . ":" . getenv('GITPASS') . "@" . getenv('HOST') . "/"
                    . getenv('ORG') . "/{$this->repoData[$i]['slug']}.git";
            }

            $gitClass       = new Git();
            $gourceClass    = new Gource();

            // clone repo
            $gitClass->clone($repoUrl, $this->repoData[$i]['slug']);
            $gitClass->checkout("{$this->basePath}/repos/{$this->repoData[$i]['slug']}");

            // render repo visualization
            $filePath = "{$this->basePath}/renders/{$this->repoData[$i]['slug']}.mp4";
            if ($gourceClass->doesNewRenderExist($filePath) === false) {
                $gourceClass->dryRun = true;
                $gourceClass->render();
            }
        }
    }

    /**
     * @return $this
     */
    public function getRepoList()
    {
        if ($this->host === 'bitbucket.org') {
            $bbr = new \Bitbucket\API\User\Repositories();
            $bbr->setCredentials(new \Bitbucket\API\Authentication\Basic(getenv('USERAUTH'), getenv('PASS')));
            $this->repoData = $bbr->get()->getContent();

            if (!$this->repoData) {
                throw new \Exception('No data returned from host.');
            }

            $this->repoData = $this->handleResponse($bbr->get()->getContent(), 'json');
        }

        // if not data returned from host, try to use the last data set saved in ./logs/*
        if (empty($this->repoData)) {
            // if no response from remote, use logged data
            $this->repoData = \file_get_contents("{$this->basePath}/logs/repos.json");
        }

        $this->repoData = $this->sortPayload($this->repoData);

        return $this;
    }

    /**
     * @param $data
     * @param string $type
     * @return array
     */
    private function handleResponse($data, string $type): array
    {
        try {
            if ($type === 'json') {
                $responseData = \json_decode($data, true);

                if (json_last_error()) {
                    throw new \Exception(\json_last_error_msg());
                }

                return $responseData;
            }

        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param array $responseData
     * @return array
     */
    private function sortPayload(array $responseData)
    {
        usort($responseData, "sortInReverseOrder");

        return $responseData;
    }
}
