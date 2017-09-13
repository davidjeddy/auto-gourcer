<?php
declare(strict_types=1);
namespace davidjeddy\AutoGourcer;

include_once ('../vendor/autoload.php');
include_once ('./Git.php');
include_once ('./Gource.php');

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

        $this->host = getenv('HOST');
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
            if ($this->host === 'BitBucket') {
                // replace with .env values
                $repoUrl = "https://{getenv('user')}:{getenv('PASS')}@bitbucket.org/" . (getenv('ORG') ? getenv('ORG')
                    . '/' :  null) . "{$this->repoData[$i]['slug']}.git";
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
        $user = getenv('USER');
        $pass = getenv('PASS');
        $responseData = [];

        if ($this->host === 'BitBucket') {
            $bbr = new \Bitbucket\API\User\Repositories();
            $bbr->setCredentials( new \Bitbucket\API\Authentication\Basic($user, $pass) );
            $responseData = $bbr->get();
        }

        // TODO move response handling to another method, return valid array
        if (empty($responseData)) {
            // if no response from remote, use logged data
            $responseData = \file_get_contents("{$this->basePath}/logs/repos.json");
        }

        $responseData = \json_decode($responseData, true);

        if (json_last_error()) {
            echo \json_last_error_msg();
            exit(1);
        }

        usort($responseData, "sortInReverseOrder");

        $this->repoData = $responseData;

        return $this;
    }
}
